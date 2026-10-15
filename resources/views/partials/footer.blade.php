<footer class="bg-slate-900 text-slate-300 pt-16 pb-10 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800">

            <!-- Brand Overview -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white shadow-md">
                        <i class="fa-solid fa-bag-shopping text-lg"></i>
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight text-white">
                        Shop<span class="text-brand-500">Easy</span>
                    </span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                    ShopEasy is your trusted destination for quality essentials, premium gadgets, and trending fashion. Enjoy seamless shopping with guaranteed secure delivery.
                </p>
                <div class="flex items-center space-x-3 pt-2">
                    <a href="javascript:void(0)" onclick="showDemoNotice('Social links are preview only')" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="https://www.instagram.com/rajan_tiwarii/" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors" title="Instagram (@rajan_tiwarii)">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="https://wa.me/919455682673" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors" title="WhatsApp (+91 94556 82673)">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="showDemoNotice('Social links are preview only')" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors">
                        <i class="fa-brands fa-linkedin-in text-sm"></i>
                    </a>
                </div>
                <p class="text-xs text-slate-400 font-medium pt-1">
                    made by - <span class="text-slate-200 font-bold">Rajan Tiwari</span>
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white text-sm font-bold tracking-wider uppercase mb-4">Quick Links</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="{{ route('shop.index') }}" class="hover:text-white transition-colors">All Products</a></li>
                    <li><a href="{{ route('home') }}#categories" class="hover:text-white transition-colors">Featured Categories</a></li>
                    <li><a href="{{ route('home') }}#promotions" class="hover:text-white transition-colors">Special Offers</a></li>
                    <li>
                        <button type="button" onclick="triggerPwaInstall()" class="hover:text-white transition-colors text-left flex items-center gap-1.5 cursor-pointer text-brand-300 hover:text-brand-200 font-medium">
                            <i class="fa-solid fa-mobile-screen-button text-xs text-brand-400"></i>
                            <span>Install ShopEasy App</span>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h4 class="text-white text-sm font-bold tracking-wider uppercase mb-4">Customer Care</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="javascript:void(0)" onclick="showDemoNotice('Help & Support page will be added in upcoming steps')" class="hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="javascript:void(0)" onclick="showDemoNotice('Order Tracking will be added with database integration')" class="hover:text-white transition-colors">Track Order</a></li>
                    <li><a href="javascript:void(0)" onclick="showDemoNotice('Shipping Policy is mock')" class="hover:text-white transition-colors">Shipping & Returns</a></li>
                    <li><a href="javascript:void(0)" onclick="showDemoNotice('Privacy Policy is mock')" class="hover:text-white transition-colors">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Newsletter Subscription (Visual Mock) -->
            <div>
                <h4 class="text-white text-sm font-bold tracking-wider uppercase mb-4">Stay Updated</h4>
                <p class="text-xs text-slate-400 mb-3">Subscribe to receive exclusive deals and weekly product launches.</p>
                <form onsubmit="event.preventDefault(); showDemoNotice('Newsletter subscription is a visual preview in Step 1');" class="space-y-2">
                    <div class="relative">
                        <input
                            type="email"
                            placeholder="Enter your email"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                        >
                    </div>
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-500 text-white rounded-lg text-xs font-semibold tracking-wide transition-colors shadow-sm"
                    >
                        Subscribe
                    </button>
                </form>
                <span class="block text-[11px] text-slate-500 mt-2"><i class="fa-solid fa-lock text-[10px] mr-1"></i> No spam, unsubscribe anytime.</span>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <div>
                <p>&copy; {{ date('Y') }} ShopEasy Inc. All rights reserved. Built with Laravel 12 &amp; Blade.</p>
            </div>
            <!-- Payment Badges (Visual) -->
            <div class="flex items-center gap-3 text-slate-400 text-lg">
                <i class="fa-brands fa-cc-visa hover:text-white transition-colors" title="Visa"></i>
                <i class="fa-brands fa-cc-mastercard hover:text-white transition-colors" title="Mastercard"></i>
                <i class="fa-brands fa-cc-paypal hover:text-white transition-colors" title="PayPal"></i>
                <i class="fa-brands fa-cc-apple-pay hover:text-white transition-colors" title="Apple Pay"></i>
            </div>
        </div>

    </div>
</footer>
