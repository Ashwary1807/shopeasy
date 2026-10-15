@extends('layouts.app')

@section('title', 'Saved Addresses - ShopEasy')

@section('content')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('account.index') }}" class="hover:text-brand-600 transition-colors">My Account</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-brand-600 font-bold">Saved Addresses</span>
        </nav>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('account.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-600 transition-colors mb-2">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Back to My Account</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Saved Delivery Addresses
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Manage your delivery locations for fast, seamless checkout.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $addresses->count() >= $maxLimit ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                    {{ $addresses->count() }} of {{ $maxLimit }} used
                </span>

                @if($canAddMore)
                    <a
                        href="{{ route('account.addresses.create') }}"
                        class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold rounded-xl transition-all shadow-sm inline-flex items-center gap-2"
                    >
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Add New Address</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Flash Notification Banners -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 animate-in fade-in">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 animate-in fade-in">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-center gap-3 animate-in fade-in">
                <i class="fa-solid fa-circle-info text-brand-600 text-base"></i>
                <span class="font-medium">{{ session('info') }}</span>
            </div>
        @endif

        <!-- Addresses List Grid -->
        @if($addresses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($addresses as $address)
                    <div class="bg-white rounded-3xl border {{ $address->is_default ? 'border-brand-500 shadow-md ring-2 ring-brand-100' : 'border-slate-200/90 shadow-sm' }} p-6 sm:p-7 flex flex-col justify-between transition-all hover:border-brand-300">

                        <div>
                            <!-- Header: Label Pill & Default Status -->
                            <div class="flex items-center justify-between gap-3 pb-4 mb-4 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    @php
                                        $labelIcons = [
                                            'Home' => 'fa-solid fa-house text-brand-600',
                                            'Work' => 'fa-solid fa-briefcase text-indigo-600',
                                            'Other' => 'fa-solid fa-location-dot text-slate-600',
                                        ];
                                        $iconClass = $labelIcons[$address->label] ?? 'fa-solid fa-location-dot text-slate-600';
                                    @endphp
                                    <span class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-xs">
                                        <i class="{{ $iconClass }}"></i>
                                    </span>
                                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800">
                                        {{ $address->label }}
                                    </span>
                                </div>

                                @if($address->is_default)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1 shadow-xs">
                                        <i class="fa-solid fa-star text-[9px]"></i> Default Address
                                    </span>
                                @endif
                            </div>

                            <!-- Recipient Details -->
                            <div class="space-y-1 mb-4">
                                <h3 class="text-base font-extrabold text-slate-900">
                                    {{ $address->recipient_name }}
                                </h3>
                                <p class="text-xs text-slate-500 flex items-center gap-1.5 font-medium">
                                    <i class="fa-solid fa-phone text-slate-400 text-[11px]"></i>
                                    <span>{{ $address->phone }}</span>
                                </p>
                            </div>

                            <!-- Physical Address Lines -->
                            <div class="text-xs text-slate-600 space-y-0.5 leading-relaxed bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                                <p class="font-medium text-slate-800">{{ $address->address_line1 }}</p>
                                @if($address->address_line2)
                                    <p class="text-slate-600">{{ $address->address_line2 }}</p>
                                @endif
                                <p class="text-slate-600">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                <p class="text-slate-500 font-semibold">{{ $address->country }}</p>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="pt-5 mt-5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                @if(!$address->is_default)
                                    <form action="{{ route('account.addresses.default', $address->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            class="text-xs font-bold text-brand-600 hover:text-brand-700 hover:underline inline-flex items-center gap-1 cursor-pointer"
                                        >
                                            <i class="fa-regular fa-star text-[11px]"></i>
                                            <span>Set as Default</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-400 font-semibold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-check text-emerald-500"></i> Active for checkout
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('account.addresses.edit', $address->id) }}"
                                    class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors inline-flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="px-3 py-1.5 rounded-xl border border-rose-200 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors inline-flex items-center gap-1.5 cursor-pointer"
                                    >
                                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-10 sm:p-14 text-center max-w-xl mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 mx-auto flex items-center justify-center text-2xl mb-4 shadow-sm">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800">No Saved Addresses Yet</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5 mb-6 leading-relaxed">
                    You haven't added any delivery addresses to your account yet. Add your home or office address for fast, 1-click checkout.
                </p>
                <a
                    href="{{ route('account.addresses.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold rounded-xl text-xs shadow-sm transition-colors"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add Your First Address</span>
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
