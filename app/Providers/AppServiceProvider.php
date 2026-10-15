<?php

namespace App\Providers;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            if (session('admin_authenticated') && Schema::hasTable('admin_notifications')) {
                $view->with([
                    'unreadAdminNotificationsCount' => AdminNotification::unread()->count(),
                    'recentAdminNotifications' => AdminNotification::recent(6)->get(),
                ]);
            } else {
                $view->with([
                    'unreadAdminNotificationsCount' => 0,
                    'recentAdminNotifications' => collect(),
                ]);
            }
        });
    }
}
