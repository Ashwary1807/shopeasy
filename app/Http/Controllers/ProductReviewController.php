<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReviewController extends Controller
{
    /**
     * Store a newly created product review.
     */
    public function store(Request $request, int $productId): RedirectResponse
    {
        $user = $request->user();

        // 1. Find the active product
        $product = Product::where('status', 'active')->findOrFail($productId);

        // 2. Strict Verified Purchase Check: Must have an order with status exactly 'delivered' containing this product
        $hasDeliveredOrder = $user->orders()
            ->where('status', 'delivered')
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();

        if (!$hasDeliveredOrder) {
            return back()->with('error', 'Only customers who have purchased and received this product (Order status: Delivered) can submit a review.');
        }

        // 3. Duplicate Review Prevention: Check if customer already reviewed this product
        $existingReview = $user->reviews()->where('product_id', $productId)->first();
        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product. You can update your existing review below.');
        }

        // 4. Server-Side Validation
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:5|max:2000',
        ], [
            'rating.required' => 'Please select a star rating from 1 to 5.',
            'rating.between' => 'Star rating must be between 1 and 5 stars.',
            'comment.required' => 'Please write a review comment.',
            'comment.min' => 'Your review comment must be at least 5 characters.',
            'comment.max' => 'Your review comment cannot exceed 2000 characters.',
            'title.max' => 'Your review headline cannot exceed 150 characters.',
        ]);

        // 5. Persist inside transaction and recalculate cached rating
        DB::transaction(function () use ($user, $product, $validated) {
            $user->reviews()->create([
                'product_id' => $product->id,
                'rating' => (int) $validated['rating'],
                'title' => !empty($validated['title']) ? trim($validated['title']) : null,
                'comment' => trim($validated['comment']),
                'is_verified_purchase' => true,
                'status' => 'approved', // Auto-approved for verified buyers
            ]);

            $product->recalculateRating();
        });

        return back()->with('success', 'Thank you! Your verified review has been published successfully.');
    }

    /**
     * Update an existing product review authored by the authenticated customer.
     */
    public function update(Request $request, int $reviewId): RedirectResponse
    {
        $user = $request->user();

        // 1. Scoped query: Customer can only update their own review (Anti-IDOR)
        $review = $user->reviews()->findOrFail($reviewId);

        // 2. Server-Side Validation
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:5|max:2000',
        ], [
            'rating.required' => 'Please select a star rating from 1 to 5.',
            'rating.between' => 'Star rating must be between 1 and 5 stars.',
            'comment.required' => 'Please write a review comment.',
            'comment.min' => 'Your review comment must be at least 5 characters.',
            'comment.max' => 'Your review comment cannot exceed 2000 characters.',
            'title.max' => 'Your review headline cannot exceed 150 characters.',
        ]);

        // 3. Update inside transaction and recalculate
        DB::transaction(function () use ($review, $validated) {
            $review->update([
                'rating' => (int) $validated['rating'],
                'title' => !empty($validated['title']) ? trim($validated['title']) : null,
                'comment' => trim($validated['comment']),
            ]);

            $review->product->recalculateRating();
        });

        return back()->with('success', 'Your review has been updated successfully.');
    }

    /**
     * Delete an existing product review authored by the authenticated customer.
     */
    public function destroy(Request $request, int $reviewId): RedirectResponse
    {
        $user = $request->user();

        // 1. Scoped query: Customer can only delete their own review (Anti-IDOR)
        $review = $user->reviews()->findOrFail($reviewId);
        $product = $review->product;

        // 2. Delete inside transaction and recalculate
        DB::transaction(function () use ($review, $product) {
            $review->delete();
            $product->recalculateRating();
        });

        return back()->with('success', 'Your review has been removed successfully.');
    }
}
