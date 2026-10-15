<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display the current session-based wishlist with verified active products.
     */
    public function index(): View
    {
        $wishlistIds = session()->get('wishlist', []);
        if (!is_array($wishlistIds)) {
            $wishlistIds = [];
        }

        // Retrieve only active products matching wishlist IDs
        $products = Product::whereIn('id', $wishlistIds)
            ->where('status', 'active')
            ->get();

        // Keep session wishlist array synchronized with currently existing active products
        session()->put('wishlist', $products->pluck('id')->all());

        return view('wishlist.index', compact('products'));
    }

    /**
     * Toggle a product in or out of the session wishlist.
     */
    public function toggle(Request $request, int $id): JsonResponse|RedirectResponse
    {
        // 1. Verify that product exists and is active
        $product = Product::where('status', 'active')->find($id);

        if (!$product) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is no longer available.',
                ], 404);
            }

            return back()->with('error', 'This product is no longer available.');
        }

        // 2. Retrieve existing wishlist
        $wishlist = session()->get('wishlist', []);
        if (!is_array($wishlist)) {
            $wishlist = [];
        }

        // 3. Toggle membership
        if (in_array($id, $wishlist, true)) {
            $wishlist = array_values(array_diff($wishlist, [$id]));
            $action = 'removed';
            $message = "Removed \"{$product->name}\" from your wishlist.";
        } else {
            $wishlist[] = $id;
            $wishlist = array_values(array_unique($wishlist));
            $action = 'added';
            $message = "Added \"{$product->name}\" to your wishlist!";
        }

        session()->put('wishlist', $wishlist);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'count' => count($wishlist),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove an item from the session wishlist.
     */
    public function remove(Request $request, int $id): RedirectResponse
    {
        $wishlist = session()->get('wishlist', []);
        if (is_array($wishlist)) {
            $wishlist = array_values(array_diff($wishlist, [$id]));
            session()->put('wishlist', $wishlist);
        }

        return back()->with('success', 'Item removed from your wishlist.');
    }

    /**
     * Clear all items from the session wishlist.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('wishlist');

        return back()->with('success', 'Your wishlist has been cleared.');
    }
}
