@extends('layouts.app')

@section('title', 'Customer Login - ShopEasy')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">

        <!-- Contextual Header -->
        <div class="text-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 mx-auto flex items-center justify-center text-white shadow-lg shadow-brand-500/25 mb-4">
                <i class="fa-solid fa-user-lock text-2xl"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Welcome Back
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">
                Log in to your ShopEasy account to complete your checkout and manage orders.
            </p>
        </div>

        <!-- Checkout Notice Banner if redirected from checkout or intent -->
        @if(session('info'))
            <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-900 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-cart-shopping text-brand-600 text-base mt-0.5"></i>
                <div class="text-xs">
                    <p class="font-bold">{{ session('info') }}</p>
                    <p class="text-indigo-700 mt-0.5">Your shopping cart is preserved and will be ready for you immediately after logging in.</p>
                </div>
            </div>
        @endif

        <!-- Login Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl p-6 sm:p-8">
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-5" novalidate>
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="e.g. alex@example.com"
                            class="w-full pl-10 pr-4 py-3 text-sm bg-slate-50 border @error('email') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all"
                        >
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Password Input with Toggle -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Enter your password"
                            class="w-full pl-10 pr-11 py-3 text-sm bg-slate-50 border @error('password') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all"
                        >
                        <button
                            type="button"
                            onclick="togglePasswordVisibility('password', this)"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none"
                            title="Show/Hide password"
                        >
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600 hover:text-slate-800">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500/20"
                        >
                        <span>Remember me on this browser</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3.5 px-6 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group"
                >
                    <i class="fa-solid fa-right-to-bracket text-xs group-hover:translate-x-0.5 transition-transform"></i>
                    <span>Log In to Account</span>
                </button>
            </form>

            <!-- Registration Callout -->
            <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    New to ShopEasy?
                    <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-700 ml-1 hover:underline">
                        Create an account
                    </a>
                </p>
            </div>
        </div>

        <p class="text-[11px] text-center text-slate-400">
            <i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> 256-Bit SSL Encrypted &amp; Secure Authentication
        </p>

    </div>
</div>

<script>
    function togglePasswordVisibility(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
