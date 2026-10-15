@extends('layouts.app')

@section('title', 'Register - ShopEasy')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">

        <!-- Contextual Header -->
        <div class="text-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 mx-auto flex items-center justify-center text-white shadow-lg shadow-brand-500/25 mb-4">
                <i class="fa-solid fa-user-plus text-2xl"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Create Account
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">
                Join ShopEasy for fast, secure checkout, order history, and personalized shopping.
            </p>
        </div>

        @if(session('info'))
            <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-900 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-cart-shopping text-brand-600 text-base mt-0.5"></i>
                <div class="text-xs">
                    <p class="font-bold">{{ session('info') }}</p>
                    <p class="text-indigo-700 mt-0.5">Once your account is created, you will be redirected back to checkout with your cart intact.</p>
                </div>
            </div>
        @endif

        <!-- Registration Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl p-6 sm:p-8">
            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4" novalidate>
                @csrf

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Full Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user text-sm"></i>
                        </span>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            placeholder="e.g. Alex Johnson"
                            class="w-full pl-10 pr-4 py-3 text-sm bg-slate-50 border @error('name') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all"
                        >
                    </div>
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Email Address -->
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

                <!-- Password -->
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
                            placeholder="Minimum 6 characters"
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

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Confirm Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock-check text-sm"></i>
                        </span>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            placeholder="Re-enter your password"
                            class="w-full pl-10 pr-11 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all"
                        >
                        <button
                            type="button"
                            onclick="togglePasswordVisibility('password_confirmation', this)"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none"
                            title="Show/Hide password"
                        >
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-3.5 px-6 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group"
                    >
                        <i class="fa-solid fa-user-plus text-xs group-hover:scale-110 transition-transform"></i>
                        <span>Create Account &amp; Proceed</span>
                    </button>
                </div>
            </form>

            <!-- Login Callout -->
            <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-700 ml-1 hover:underline">
                        Log in here
                    </a>
                </p>
            </div>
        </div>

        <p class="text-[11px] text-center text-slate-400">
            <i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> 256-Bit SSL Encrypted &amp; Secure Registration
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
