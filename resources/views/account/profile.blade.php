@extends('layouts.app')

@section('title', 'My Profile - ShopEasy')

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('account.index') }}" class="hover:text-brand-600 transition-colors">My Account</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-brand-600 font-bold">Edit Profile</span>
        </nav>

        <!-- Back Link & Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('account.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-600 transition-colors mb-2">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Back to My Account</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Account Profile
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Manage your personal details and account preferences.
                </p>
            </div>

            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-regular fa-id-badge"></i>
            </div>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 animate-in fade-in">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Alert -->
        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 animate-in fade-in">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Left Info Panel (Read-only Metadata) -->
            <div class="space-y-6">
                <!-- User Card -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 text-center">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white text-3xl font-extrabold shadow-md shadow-brand-500/25 mx-auto mb-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h2 class="text-base font-extrabold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $user->email }}</p>

                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-center">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-[9px]"></i> Active Customer
                        </span>
                    </div>
                </div>

                <!-- Account Information Summary -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Account Details</h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Member Since</span>
                            <span class="font-bold text-slate-800">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Recent' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Account Type</span>
                            <span class="font-bold text-slate-800">Customer</span>
                        </div>
                        <div class="flex items-center justify-between py-1">
                            <span class="text-slate-500">Security</span>
                            <span class="font-bold text-emerald-600 flex items-center gap-1">
                                <i class="fa-solid fa-lock text-[10px]"></i> Protected
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                    <h2 class="text-base font-extrabold text-slate-900 mb-1">
                        Personal Information
                    </h2>
                    <p class="text-xs text-slate-500 mb-6">
                        Update your display name and email address associated with your customer account.
                    </p>

                    <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Full Name Field -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
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
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    placeholder="Enter your full name"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border @error('name') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                                >
                            </div>
                            @error('name')
                                <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <!-- Email Address Field -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                    <i class="fa-regular fa-envelope text-sm"></i>
                                </span>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    placeholder="name@example.com"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border @error('email') border-rose-400 bg-rose-50/40 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white text-slate-800 transition-all font-medium"
                                >
                            </div>
                            @error('email')
                                <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                            <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-circle-info text-[10px]"></i>
                                <span>Used for order confirmations and customer account sign-in.</span>
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                            <a
                                href="{{ route('account.index') }}"
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors text-center"
                            >
                                Cancel
                            </a>
                            <button
                                type="submit"
                                class="w-full sm:w-auto px-6 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold rounded-xl text-xs shadow-md hover:shadow transition-all inline-flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fa-solid fa-check text-xs"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
