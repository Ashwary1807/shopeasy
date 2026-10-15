<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display the full catalog of products with pagination, search, and category filtering.
     */
    public function index(Request $request): View
    {
        // 1. Base query: active products only
        $query = Product::where('status', 'active');

        if (Schema::hasTable('product_reviews')) {
            $query->withCount(['reviews as approved_reviews_count' => fn($q) => $q->where('status', 'approved')]);
        }

        // 2. Search query filter (matches name, description, or category)
        $search = trim($request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // 3. Filter by category if requested
        $selectedCategory = $request->query('category');
        if (!empty($selectedCategory)) {
            $query->where('category', $selectedCategory);
        }

        // 4. Paginate with 6 products per page and preserve query string parameters
        $products = $query->latest()->paginate(6)->withQueryString();

        // 5. Retrieve distinct categories present in active products for filter pills
        $categories = Product::where('status', 'active')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('shop.index', compact('products', 'categories', 'selectedCategory', 'search'));
    }

    /**
     * Display single product details page with specs, reviews, and related products.
     */
    public function show(int $id): View
    {
        $product = Product::where('status', 'active')->findOrFail($id);

        // Fetch up to 4 related products in the same category (excluding current product)
        $relatedProducts = Product::where('status', 'active')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $reviews = collect();
        $ratingBreakdown = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        $canReview = false;
        $userReview = null;

        if (Schema::hasTable('product_reviews')) {
            $reviews = $product->reviews()
                ->where('status', 'approved')
                ->with('user')
                ->latest('id')
                ->get();

            foreach ($reviews as $rev) {
                $star = (int) $rev->rating;
                if (isset($ratingBreakdown[$star])) {
                    $ratingBreakdown[$star]++;
                }
            }

            if (auth()->check()) {
                $user = auth()->user();
                $userReview = $reviews->firstWhere('user_id', $user->id)
                    ?? $product->reviews()->where('user_id', $user->id)->first();

                // Strict Verified Purchase: customer must have an order with status 'delivered' containing this product
                $canReview = $user->orders()
                    ->where('status', 'delivered')
                    ->whereHas('items', function ($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })
                    ->exists();
            }
        }

        return view('shop.show', compact('product', 'relatedProducts', 'reviews', 'ratingBreakdown', 'canReview', 'userReview'));
    }
}
