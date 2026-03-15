<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;

class StripePaymentController extends Controller
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected OrderTransactionRepository $orderTransactionRepository
    ) {}

    /**
     * Render Stripe hosted payment page for the just-created order.
     */
    public function checkout(int $order)
    {
        $placedOrderId = (int) session('order_id');

        if (! $placedOrderId || $placedOrderId !== $order) {
            abort(403);
        }

        $order = $this->orderRepository->findOrFail($order);

        return view('shop::checkout.stripe', compact('order'));
    }

    /**
     * Create Stripe payment intent in test/live mode depending on key.
     */
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|integer',
        ]);

        $placedOrderId = (int) session('order_id');

        if (! $placedOrderId || $placedOrderId !== (int) $validated['order_id']) {
            return response()->json([
                'message' => 'Invalid order reference.',
            ], 422);
        }

        $order = $this->orderRepository->findOrFail((int) $validated['order_id']);

        $stripeSecret = (string) env('STRIPE_SECRET');
        $stripeKey = (string) env('STRIPE_KEY');

        if (empty($stripeSecret) || empty($stripeKey)) {
            return response()->json([
                'message' => 'Stripe credentials are missing.',
            ], 422);
        }

        $stripe = new StripeClient($stripeSecret);

        $intent = $stripe->paymentIntents->create([
            'amount' => max(1, (int) round(((float) $order->grand_total) * 100)),
            'currency' => strtolower((string) ($order->order_currency_code ?: 'usd')),
            'metadata' => [
                'order_id' => (string) $order->id,
                'increment_id' => (string) $order->increment_id,
            ],
        ]);

        return response()->json([
            'publishable_key' => $stripeKey,
            'client_secret' => $intent->client_secret,
            'order_id' => $order->id,
            'success_url' => route('shop.stripe.payment.success', [
                'order_id' => $order->id,
                'payment_intent' => $intent->id,
            ]),
        ]);
    }

    /**
     * Verify Stripe payment status, then create invoice + transaction.
     */
    public function paymentSuccess(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|integer',
            'payment_intent' => 'required|string',
        ]);

        $order = $this->orderRepository->findOrFail((int) $validated['order_id']);

        $stripe = new StripeClient((string) env('STRIPE_SECRET'));

        $intent = $stripe->paymentIntents->retrieve($validated['payment_intent'], []);

        if ($intent->status !== 'succeeded') {
            return redirect()->route('shop.checkout.onepage.success')
                ->with('error', 'Payment is not completed yet.');
        }

        if ((string) ($intent->metadata->order_id ?? '') !== (string) $order->id) {
            return redirect()->route('shop.checkout.onepage.success')
                ->with('error', 'Payment validation failed for this order.');
        }

        $invoice = $order->invoices()->first();

        if (! $invoice && $order->canInvoice()) {
            $invoice = $this->invoiceRepository->create(
                $this->prepareInvoiceData($order),
                'paid',
                'processing'
            );
        }

        if ($invoice) {
            $alreadyLogged = $order->transactions()
                ->where('transaction_id', $intent->id)
                ->exists();

            if (! $alreadyLogged) {
                $this->orderTransactionRepository->create([
                    'transaction_id' => $intent->id,
                    'status' => $intent->status,
                    'type' => 'capture',
                    'amount' => (float) ($intent->amount_received / 100),
                    'payment_method' => 'stripe',
                    'data' => json_decode(json_encode($intent), true),
                    'invoice_id' => $invoice->id,
                    'order_id' => $order->id,
                ]);
            }
        }

        session()->flash('order_id', $order->id);

        return redirect()->route('shop.checkout.onepage.success');
    }

    /**
     * Build invoice payload for full order capture.
     */
    protected function prepareInvoiceData($order): array
    {
        $invoiceData = ['order_id' => $order->id, 'invoice' => ['items' => []]];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
