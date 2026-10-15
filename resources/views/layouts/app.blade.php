<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ShopEasy - Premium Online Store')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- PWA Web App Manifest & Mobile App Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="theme-color" content="#4f46e5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="ShopEasy">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('icons/icon.svg') }}">
    <script>
        // Early capture 'beforeinstallprompt' to prevent missing the event before page scripts finish loading
        window.deferredPWAInstallPrompt = null;
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            window.deferredPWAInstallPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-prompt-available', { detail: e }));
        });
    </script>

    <!-- Tailwind CSS (via official Play CDN for simple, self-contained development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 text-white text-xs py-2 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-1 sm:gap-4 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Summer Sale: Enjoy up to <strong>50% off</strong> on selected collections!</span>
            </div>
            <div class="flex items-center gap-4 text-slate-300">
                <span><i class="fa-solid fa-phone text-xs mr-1 text-slate-400"></i> +1 (800) 123-4567</span>
                <span class="hidden md:inline">|</span>
                <span class="hidden md:inline"><i class="fa-solid fa-shield-halved text-xs mr-1 text-slate-400"></i> 100% Secure Checkout</span>
            </div>
        </div>
    </div>

    <!-- Header & Navigation -->
    @include('partials.navbar')

    <!-- Main Content Container -->
    <main class="flex-grow">
        <!-- Session Flash Notification Banners -->
        @if (session('success') || session('error') || session('info') || session('warning'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                @if (session('success'))
                    <div class="flex items-center justify-between p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm" role="alert">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1" title="Dismiss">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="flex items-center justify-between p-4 mb-4 text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 p-1" title="Dismiss">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="flex items-center justify-between p-4 mb-4 text-sm text-brand-900 rounded-2xl bg-brand-50 border border-brand-200 shadow-sm" role="alert">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-info text-brand-600 text-base"></i>
                            <span class="font-medium">{{ session('info') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-brand-500 hover:text-brand-800 p-1" title="Dismiss">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="flex items-center justify-between p-4 mb-4 text-sm text-amber-900 rounded-2xl bg-amber-50 border border-amber-200 shadow-sm" role="alert">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base"></i>
                            <span class="font-medium">{{ session('warning') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-800 p-1" title="Dismiss">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Simple Feedback Toast (Client-side interactive demo) -->
    <div id="toastNotification" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
            <i class="fa-solid fa-check text-sm"></i>
        </div>
        <div>
            <p id="toastTitle" class="text-sm font-semibold">Notification</p>
            <p id="toastMessage" class="text-xs text-slate-400">Action performed.</p>
        </div>
    </div>

    <!-- Mobile Menu and Interactive Demo Script -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        function showDemoNotice(actionName) {
            showToast(actionName, 'This is a visual preview in Step 1. Interactive functionality will be connected in next steps!');
        }

        function showToast(titleText, messageText, isSuccess = true) {
            const toast = document.getElementById('toastNotification');
            const title = document.getElementById('toastTitle');
            const message = document.getElementById('toastMessage');
            const iconContainer = toast ? toast.querySelector('div:first-child') : null;
            if (!toast || !title || !message) return;

            title.innerText = titleText;
            message.innerText = messageText;

            if (iconContainer) {
                if (isSuccess) {
                    iconContainer.className = 'w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0';
                    iconContainer.innerHTML = '<i class="fa-solid fa-check text-sm"></i>';
                } else {
                    iconContainer.className = 'w-8 h-8 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0';
                    iconContainer.innerHTML = '<i class="fa-solid fa-circle-exclamation text-sm"></i>';
                }
            }

            toast.classList.remove('translate-y-24', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');

            if (window.toastTimer) clearTimeout(window.toastTimer);
            window.toastTimer = setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-24', 'opacity-0');
            }, 3500);
        }

        function copyPromoCode(code) {
            if (!code) return;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(code).then(() => {
                    showToast('Code Copied!', 'Coupon ' + code + ' copied to clipboard! Paste it at cart or checkout.');
                }).catch(() => {
                    fallbackCopyPromoCode(code);
                });
            } else {
                fallbackCopyPromoCode(code);
            }
        }

        function fallbackCopyPromoCode(code) {
            try {
                const input = document.createElement('textarea');
                input.value = code;
                input.style.position = 'fixed';
                input.style.left = '-9999px';
                document.body.appendChild(input);
                input.focus();
                input.select();
                const success = document.execCommand('copy');
                document.body.removeChild(input);
                if (success) {
                    showToast('Code Copied!', 'Coupon ' + code + ' copied to clipboard! Paste it at cart or checkout.');
                } else {
                    showToast('Promo Code: ' + code, 'Please copy the code manually: ' + code, false);
                }
            } catch (e) {
                showToast('Promo Code: ' + code, 'Please copy the code manually: ' + code, false);
            }
        }
    </script>

    @stack('scripts')

    <!-- PWA Install Modal -->
    @include('partials.pwa-modal')

    <!-- PWA Installer Client Script -->
    <script src="{{ asset('js/pwa-installer.js') }}"></script>
</body>
</html>
