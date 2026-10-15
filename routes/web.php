<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Middleware\EnsureAdminAuthenticated;

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerAccountController;
use App\Http\Controllers\Customer\CustomerAddressController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Middleware\EnsureCustomerAuthenticated;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Customer Authentication Routes (Isolated from Admin)
Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

// Phase C: Interactive Shopping Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Phase 5: Coupon Application & Removal (Session-based, shared across Cart & Checkout)
Route::post('/checkout/coupon/apply', [CouponController::class, 'apply'])->name('checkout.coupon.apply');
Route::post('/checkout/coupon/remove', [CouponController::class, 'remove'])->name('checkout.coupon.remove');

// Phase D: Protected Checkout Flow & Customer Account Routes
Route::middleware(EnsureCustomerAuthenticated::class)->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Customer Account & Profile Routes
    Route::get('/account', [CustomerAccountController::class, 'dashboard'])->name('account.index');
    Route::get('/account/profile', [CustomerAccountController::class, 'profile'])->name('account.profile');
    Route::put('/account/profile', [CustomerAccountController::class, 'updateProfile'])->name('account.profile.update');

    // Customer Saved Addresses Routes
    Route::get('/account/addresses', [CustomerAddressController::class, 'index'])->name('account.addresses.index');
    Route::get('/account/addresses/create', [CustomerAddressController::class, 'create'])->name('account.addresses.create');
    Route::post('/account/addresses', [CustomerAddressController::class, 'store'])->name('account.addresses.store');
    Route::get('/account/addresses/{id}/edit', [CustomerAddressController::class, 'edit'])->name('account.addresses.edit');
    Route::put('/account/addresses/{id}', [CustomerAddressController::class, 'update'])->name('account.addresses.update');
    Route::delete('/account/addresses/{id}', [CustomerAddressController::class, 'destroy'])->name('account.addresses.destroy');
    Route::patch('/account/addresses/{id}/default', [CustomerAddressController::class, 'setDefault'])->name('account.addresses.default');

    // Customer Order Cancellation
    Route::post('/orders/{orderNumber}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Phase 4: Customer Product Reviews
    Route::post('/products/{id}/reviews', [ProductReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ProductReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');
});
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Phase E: My Orders & Guest Order Tracking Routes
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::post('/orders/lookup', [OrderController::class, 'lookup'])->name('orders.lookup');
Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');

// Wishlist Routes (Session-based)
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');

// Phase F & G: Admin Portal Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes (Protected by EnsureAdminAuthenticated)
    Route::middleware(EnsureAdminAuthenticated::class)->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Order Management Routes (Phase F)
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{orderNumber}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{orderNumber}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::patch('/orders/{orderNumber}/payment', [AdminOrderController::class, 'updatePayment'])->name('orders.payment');

        // Product & Inventory Management Routes (Phase G)
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
        Route::patch('/products/{id}/status', [AdminProductController::class, 'updateStatus'])->name('products.status');
        Route::patch('/products/{id}/stock', [AdminProductController::class, 'updateStock'])->name('products.stock');

        // New Order Notifications Routes
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::match(['get', 'patch'], '/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.markRead');
        Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    });
});
