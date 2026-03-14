<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Marketing\Repositories\SearchTermRepository;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\SearchRepository;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SearchTermRepository $searchTermRepository,
        protected SearchRepository $searchRepository
    ) {}

    /**
     * Index to handle the view loaded with the search results
     *
     * @return View
     */
    public function index()
    {
        $this->validate(request(), [
            'query' => ['sometimes', 'required', 'string', 'regex:/^[^\\\\]+$/u'],
        ]);

        $searchTerm = $this->searchTermRepository->findOneWhere([
            'term' => request()->query('query'),
            'channel_id' => core()->getCurrentChannel()->id,
            'locale' => app()->getLocale(),
        ]);

        if ($searchTerm?->redirect_url) {
            return redirect()->to($searchTerm->redirect_url);
        }

        $query = request()->query('query');

        $suggestion = null;

        if (
            ! request()->has('suggest')
            || request()->query('suggest') !== '0'
        ) {
            $searchEngine = core()->getConfigData('catalog.products.search.engine') === 'elastic'
                ? core()->getConfigData('catalog.products.search.storefront_mode')
                : 'database';

            $suggestion = $this->searchRepository
                ->setSearchEngine($searchEngine)
                ->getSuggestions($query);
        }

        return view('shop::search.index', [
            'query' => $query,
            'suggestion' => $suggestion,
            'params' => [
                'sort' => request()->query('sort'),
                'limit' => request()->query('limit'),
                'mode' => request()->query('mode'),
            ],
        ]);
    }

    /**
     * Upload image for product search with machine learning.
     *
     * @return string
     */
    public function upload()
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        return $this->searchRepository->uploadSearchImage(request()->all());
    }

    /**
     * Live search suggestions for autocomplete.
     */
    public function suggestions(Request $request): JsonResponse
    {
        $request->validate([
            'query' => ['required', 'string', 'min:2', 'regex:/^[^\\]+$/u'],
        ]);

        $query = (string) $request->input('query');

        $products = collect();

        if (
            config('scout.driver') === 'meilisearch'
            && class_exists('Laravel\\Scout\\Searchable')
            && method_exists(Product::class, 'search')
        ) {
            $products = Product::search($query)
                ->take(8)
                ->get();
        }

        if ($products->isEmpty()) {
            $products = Product::query()
                ->with('product_flats')
                ->whereHas('product_flats', function ($builder) use ($query) {
                    $builder->where('name', 'like', '%'.$query.'%');
                })
                ->limit(8)
                ->get();
        }

        $suggestions = $products->map(function ($product) {
            $flat = $product->product_flats
                ->firstWhere('channel', core()->getCurrentChannelCode())
                ?? $product->product_flats->first();

            return [
                'id' => $product->id,
                'name' => $flat?->name,
            ];
        })->filter(fn ($item) => ! empty($item['name']))->values();

        return response()->json([
            'data' => $suggestions,
        ]);
    }
}
