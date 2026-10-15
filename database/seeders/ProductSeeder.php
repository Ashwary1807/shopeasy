<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Uses updateOrCreate to prevent duplicates if executed multiple times.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless ANC Headphones Pro',
                'description' => 'Premium noise-cancelling over-ear wireless headphones with deep bass and 40-hour battery life.',
                'category' => 'Audio & Gadgets',
                'original_price' => 129.99,
                'price' => 89.99,
                'stock' => 25,
                'status' => 'active',
                'rating' => 4.8,
                'reviews_count' => 142,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Best Seller',
                'badge_color' => 'bg-amber-500',
            ],
            [
                'name' => 'Smart Fitness Watch Series 5',
                'description' => 'Waterproof smartwatch with continuous heart rate monitoring, GPS tracking, and OLED touch display.',
                'category' => 'Electronics',
                'original_price' => 199.99,
                'price' => 149.50,
                'stock' => 18,
                'status' => 'active',
                'rating' => 4.9,
                'reviews_count' => 98,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Featured',
                'badge_color' => 'bg-emerald-600',
            ],
            [
                'name' => 'Ultra Speed Running Sneakers',
                'description' => 'Lightweight athletic sneakers featuring responsive foam cushioning and breathable mesh fabric.',
                'category' => 'Footwear & Sports',
                'original_price' => 119.00,
                'price' => 79.99,
                'stock' => 30,
                'status' => 'active',
                'rating' => 4.7,
                'reviews_count' => 215,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Hot Deal',
                'badge_color' => 'bg-rose-500',
            ],
            [
                'name' => 'Classic Leather Minimalist Backpack',
                'description' => 'Handcrafted genuine leather backpack with dedicated 15-inch laptop sleeve and water-resistant lining.',
                'category' => 'Fashion & Apparel',
                'original_price' => 89.99,
                'price' => 64.99,
                'stock' => 15,
                'status' => 'active',
                'rating' => 4.6,
                'reviews_count' => 76,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80',
                'badge' => 'New Arrival',
                'badge_color' => 'bg-indigo-600',
            ],
            [
                'name' => 'Polarized Aviator Sunglasses',
                'description' => 'Classic metal frame sunglasses with 100% UV400 polarized anti-glare protection.',
                'category' => 'Watches & Accessories',
                'original_price' => 59.99,
                'price' => 39.99,
                'stock' => 40,
                'status' => 'active',
                'rating' => 4.5,
                'reviews_count' => 54,
                'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Trending',
                'badge_color' => 'bg-purple-600',
            ],
            [
                'name' => 'Mechanical Gaming Keyboard RGB',
                'description' => 'Customizable RGB mechanical gaming keyboard with tactile red switches and detachable braided cable.',
                'category' => 'Electronics',
                'original_price' => 109.99,
                'price' => 74.99,
                'stock' => 22,
                'status' => 'active',
                'rating' => 4.9,
                'reviews_count' => 320,
                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Popular',
                'badge_color' => 'bg-blue-600',
            ],
            [
                'name' => 'Barista Touch Espresso Machine',
                'description' => 'Compact countertop Italian espresso maker with 15-bar pressure pump and integrated milk steam frother.',
                'category' => 'Home & Living',
                'original_price' => 249.99,
                'price' => 189.99,
                'stock' => 10,
                'status' => 'active',
                'rating' => 4.8,
                'reviews_count' => 88,
                'image' => 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Top Rated',
                'badge_color' => 'bg-emerald-600',
            ],
            [
                'name' => 'Vintage Denim Oversized Jacket',
                'description' => 'Relaxed-fit vintage washed denim jacket made from 100% durable cotton with buttoned chest pockets.',
                'category' => 'Fashion & Apparel',
                'original_price' => 95.00,
                'price' => 59.99,
                'stock' => 16,
                'status' => 'active',
                'rating' => 4.7,
                'reviews_count' => 119,
                'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?auto=format&fit=crop&w=600&q=80',
                'badge' => 'Sale',
                'badge_color' => 'bg-rose-500',
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
