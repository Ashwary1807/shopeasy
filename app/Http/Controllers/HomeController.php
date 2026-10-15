<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the ShopEasy storefront homepage with sample data.
     */
    public function index(): View
    {
        // 1. Hero Banner Data
        $hero = [
            'badge' => '🔥 Exclusive Summer Deals',
            'title' => 'Upgrade Your Lifestyle with ShopEasy',
            'subtitle' => 'Explore thousands of premium products at unbeatable prices. From cutting-edge electronics to trendsetting fashion, shop with confidence today.',
            'cta_primary' => 'Shop Now',
            'cta_secondary' => 'Browse Categories',
            'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1200&q=80',
        ];

        // 2. Trust Perks / Value Highlights
        $perks = [
            [
                'icon' => 'fa-truck-fast',
                'title' => 'Free & Fast Shipping',
                'description' => 'Free delivery on all orders over $49',
            ],
            [
                'icon' => 'fa-shield-halved',
                'title' => 'Secure Checkout',
                'description' => 'Protected by 256-bit SSL encryption',
            ],
            [
                'icon' => 'fa-rotate-left',
                'title' => '30-Day Easy Returns',
                'description' => 'Hassle-free money-back guarantee',
            ],
            [
                'icon' => 'fa-headset',
                'title' => '24/7 Priority Support',
                'description' => 'Dedicated assistance anytime you need',
            ],
        ];

        // 3. Featured Categories
        $categories = [
            [
                'name' => 'Electronics',
                'item_count' => '120+ Products',
                'icon' => 'fa-laptop',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Trending',
            ],
            [
                'name' => 'Fashion & Apparel',
                'item_count' => '350+ Products',
                'icon' => 'fa-shirt',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Popular',
            ],
            [
                'name' => 'Home & Living',
                'item_count' => '85+ Products',
                'icon' => 'fa-couch',
                'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80',
                'badge' => 'New',
            ],
            [
                'name' => 'Audio & Gadgets',
                'item_count' => '94+ Products',
                'icon' => 'fa-headphones',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Hot',
            ],
            [
                'name' => 'Footwear & Sports',
                'item_count' => '110+ Products',
                'icon' => 'fa-shoe-prints',
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Sale',
            ],
            [
                'name' => 'Watches & Accessories',
                'item_count' => '75+ Products',
                'icon' => 'fa-clock',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Classic',
            ],
        ];

        // 4. Products loaded dynamically via Eloquent
        try {
            $query = Product::where('status', 'active');
            if (Schema::hasTable('product_reviews')) {
                $query->withCount(['reviews as approved_reviews_count' => fn($q) => $q->where('status', 'approved')]);
            }
            $products = $query->latest()
                ->take(8)
                ->get();
        } catch (\Throwable $e) {
            // Graceful fallback for empty state if migration has not been run yet
            $products = collect();
        }

        // 5. Promo Banner Data
        $promo = [
            'tag' => 'LIMITED TIME OFFER',
            'title' => 'Get Up To 50% Off On Selected Gadgets & Accessories',
            'description' => 'Enjoy 50% off products in Audio & Gadgets, Electronics, and Watches & Accessories (Min. $30 eligible order, max discount $25). Copy the code and apply it during checkout!',
            'coupon_code' => 'SHOPEASY50',
            'button_text' => 'Claim Discount',
            'terms' => '50% off eligible categories • Min. $30 order • Max discount $25',
        ];

        return view('home', compact('hero', 'perks', 'categories', 'products', 'promo'));
    }
}
