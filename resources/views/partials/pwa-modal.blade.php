<!-- PWA Installation Modal Dialog -->
<div
    id="pwaInstallModal"
    class="hidden fixed inset-0 z-[100] items-center justify-center overflow-y-auto p-4 sm:p-6"
    aria-labelledby="pwaModalTitle"
    role="dialog"
    aria-modal="true"
>
    <!-- Dark Backdrop -->
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity" onclick="closePwaModal()"></div>

    <!-- Modal Box -->
    <div class="relative z-10 w-full max-w-lg transform overflow-hidden rounded-3xl bg-white p-6 sm:p-8 text-left shadow-2xl transition-all border border-slate-200 my-8 mx-auto">

        <!-- Header with Brand Icon -->
        <div class="flex items-start justify-between gap-4 mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-brand-500/25 shrink-0">
                    <i class="fa-solid fa-bag-shopping text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight" id="pwaModalTitle">
                        Install ShopEasy App
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Enjoy fast 1-tap access, offline catalog browsing, and instant updates.
                    </p>
                </div>
            </div>
            <button
                type="button"
                onclick="closePwaModal()"
                class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors shrink-0 cursor-pointer"
                title="Close"
            >
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- iOS Safari Instructions -->
        <div id="pwaIosInstructions" class="space-y-4">
            <div class="p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-indigo-900 text-xs flex items-center gap-2.5">
                <i class="fa-brands fa-apple text-base text-indigo-600"></i>
                <span class="font-semibold">Apple iOS Safari Installation</span>
            </div>

            <ol class="space-y-3 text-xs sm:text-sm text-slate-700">
                <li class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-brand-600 text-white font-extrabold flex items-center justify-center text-xs shrink-0 mt-0.5">1</span>
                    <div>
                        <p class="font-bold text-slate-900">Tap the Share Button</p>
                        <p class="text-slate-500 text-xs mt-0.5">
                            Tap the <strong class="text-slate-800 font-semibold"><i class="fa-solid fa-arrow-up-from-bracket mx-1 text-brand-600"></i> Share</strong> icon in the Safari bottom navigation bar.
                        </p>
                    </div>
                </li>

                <li class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-brand-600 text-white font-extrabold flex items-center justify-center text-xs shrink-0 mt-0.5">2</span>
                    <div>
                        <p class="font-bold text-slate-900">Select "Add to Home Screen"</p>
                        <p class="text-slate-500 text-xs mt-0.5">
                            Scroll down the options list and tap <strong class="text-slate-800 font-semibold"><i class="fa-regular fa-square-plus mx-1 text-brand-600"></i> Add to Home Screen</strong>.
                        </p>
                    </div>
                </li>

                <li class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-brand-600 text-white font-extrabold flex items-center justify-center text-xs shrink-0 mt-0.5">3</span>
                    <div>
                        <p class="font-bold text-slate-900">Confirm Installation</p>
                        <p class="text-slate-500 text-xs mt-0.5">
                            Tap <strong class="text-slate-800 font-semibold">"Add"</strong> in the top-right corner. The ShopEasy app icon will appear directly on your home screen.
                        </p>
                    </div>
                </li>
            </ol>
        </div>

        <!-- Android & Desktop Instructions -->
        <div id="pwaGeneralInstructions" class="space-y-4">
            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-950 text-xs flex items-start gap-2.5">
                <i class="fa-solid fa-circle-info text-base text-amber-600 mt-0.5 shrink-0"></i>
                <div>
                    <p class="font-bold">Already installed — open ShopEasy from your app icon</p>
                    <p class="text-amber-800 mt-0.5">
                        If ShopEasy was installed previously, launch it directly from your Desktop, Start Menu, or mobile home-screen icon. In your browser address bar, you can also click the <strong>"Open in app"</strong> icon (<i class="fa-solid fa-arrow-up-right-from-square text-amber-700"></i>).
                    </p>
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-100 border border-slate-200 text-slate-800 text-xs flex items-center gap-2.5">
                <i class="fa-solid fa-laptop text-base text-slate-600"></i>
                <span class="font-semibold">Desktop & Android Browser Installation</span>
            </div>

            <ol class="space-y-3 text-xs sm:text-sm text-slate-700">
                <li class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-brand-600 text-white font-extrabold flex items-center justify-center text-xs shrink-0 mt-0.5">1</span>
                    <div>
                        <p class="font-bold text-slate-900">Address Bar Install Icon</p>
                        <p class="text-slate-500 text-xs mt-0.5">
                            Look for the <strong class="text-slate-800 font-semibold">Install Icon</strong> (<i class="fa-solid fa-desktop text-brand-600"></i> or <i class="fa-solid fa-arrow-down-to-bracket text-brand-600"></i>) on the right side of your browser's address bar.
                        </p>
                    </div>
                </li>

                <li class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="w-6 h-6 rounded-full bg-brand-600 text-white font-extrabold flex items-center justify-center text-xs shrink-0 mt-0.5">2</span>
                    <div>
                        <p class="font-bold text-slate-900">Or Open Browser Menu</p>
                        <p class="text-slate-500 text-xs mt-0.5">
                            Click your browser's triple-dot menu (<i class="fa-solid fa-ellipsis-vertical text-slate-700"></i>) and choose <strong class="text-slate-800 font-semibold">"Install ShopEasy..."</strong>, <strong class="text-slate-800 font-semibold">"Save and share" &rarr; "Install ShopEasy"</strong> (Chrome), or <strong class="text-slate-800 font-semibold">"Apps" &rarr; "Install this site as an app"</strong> (Edge).
                        </p>
                    </div>
                </li>
            </ol>
        </div>

        <!-- Security & Safe Offline Notice -->
        <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] text-slate-500 flex items-start gap-2">
            <i class="fa-solid fa-shield-halved text-emerald-600 text-xs mt-0.5 shrink-0"></i>
            <span>
                Progressive Web App (PWA): Installs directly via your browser with zero APK downloads or file installations. Secure, private, and lightweight.
            </span>
        </div>

        <!-- Actions -->
        <div class="mt-6">
            <button
                type="button"
                onclick="closePwaModal()"
                class="w-full py-3 px-5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all cursor-pointer"
            >
                Got It, Thanks!
            </button>
        </div>

    </div>
</div>
