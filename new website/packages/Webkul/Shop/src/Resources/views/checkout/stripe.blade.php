<x-shop::layouts>
    <x-slot:title>
        Stripe Payment
    </x-slot>

    <div class="container px-[60px] max-lg:px-8 max-sm:px-4 py-10">
        <div class="mx-auto max-w-xl rounded-2xl border border-zinc-200 bg-white p-6">
            <h1 class="text-2xl font-medium">Complete your payment</h1>

            <p class="mt-2 text-sm text-zinc-600">
                Order #{{ $order->increment_id }}
            </p>

            <p class="mt-1 text-sm text-zinc-600">
                Amount: {{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}
            </p>

            <form id="stripe-payment-form" class="mt-6 space-y-4">
                <div id="card-element" class="rounded-xl border border-zinc-300 p-3"></div>

                <p id="card-error" class="text-sm text-red-600"></p>

                <button
                    type="submit"
                    class="primary-button rounded-2xl px-8 py-3 text-base"
                    id="stripe-pay-button"
                >
                    Pay now
                </button>
            </form>
        </div>
    </div>

    @pushOnce('scripts')
        <script src="https://js.stripe.com/v3/"></script>

        <script type="module">
            const form = document.getElementById('stripe-payment-form');
            const errorBox = document.getElementById('card-error');
            const payButton = document.getElementById('stripe-pay-button');

            if (! form) {
                throw new Error('Stripe form not found');
            }

            const paymentIntentResponse = await fetch("{{ route('shop.stripe.payment.intent') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    order_id: {{ (int) $order->id }},
                }),
            });

            const paymentIntentPayload = await paymentIntentResponse.json();

            if (! paymentIntentResponse.ok) {
                errorBox.textContent = paymentIntentPayload.message || 'Unable to initialize payment.';

                payButton.disabled = true;
                payButton.classList.add('opacity-50', 'cursor-not-allowed');

                throw new Error(errorBox.textContent);
            }

            const stripe = Stripe(paymentIntentPayload.publishable_key);
            const elements = stripe.elements();
            const cardElement = elements.create('card');

            cardElement.mount('#card-element');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                payButton.disabled = true;
                errorBox.textContent = '';

                const confirmation = await stripe.confirmCardPayment(paymentIntentPayload.client_secret, {
                    payment_method: {
                        card: cardElement,
                    },
                });

                if (confirmation.error) {
                    payButton.disabled = false;
                    errorBox.textContent = confirmation.error.message || 'Payment failed. Please retry.';

                    return;
                }

                if (confirmation.paymentIntent?.status === 'succeeded') {
                    window.location.href = paymentIntentPayload.success_url;
                }
            });
        </script>
    @endPushOnce
</x-shop::layouts>
