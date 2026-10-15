/**
 * ShopEasy PWA Installation & Service Worker Manager
 */

(function () {
    let isAppInstalledOnSystem = false;

    const isIOSDevice = () => {
        return (
            ['iPad Simulator', 'iPhone Simulator', 'iPod Simulator', 'iPad', 'iPhone', 'iPod'].includes(navigator.platform) ||
            (navigator.userAgent.includes('Mac') && 'ontouchend' in document)
        );
    };

    const isAppInstalled = () => {
        return (
            window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true ||
            document.referrer.includes('android-app://') ||
            isAppInstalledOnSystem
        );
    };

    function updateButtonsInstalledState() {
        document.querySelectorAll('.pwa-install-trigger').forEach((btn) => {
            btn.setAttribute('data-installed', 'true');
            btn.setAttribute('title', 'Already installed — open ShopEasy from your app icon');
        });
    }

    // 1. Service Worker Registration (immediate, non-blocking)
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker
            .register('/sw.js', { scope: '/' })
            .then((registration) => {
                console.log('ShopEasy PWA Service Worker registered with scope:', registration.scope);
            })
            .catch((error) => {
                console.warn('ShopEasy PWA Service Worker registration failed:', error);
            });
    }

    // 2. Detect if app is already installed via W3C getInstalledRelatedApps
    if ('getInstalledRelatedApps' in navigator) {
        navigator.getInstalledRelatedApps().then((apps) => {
            if (apps && apps.length > 0) {
                isAppInstalledOnSystem = true;
                updateButtonsInstalledState();
                console.log('ShopEasy PWA is already installed on this device:', apps);
            }
        }).catch((err) => {
            console.debug('getInstalledRelatedApps check:', err);
        });
    }

    // 3. Ensure beforeinstallprompt is safely stored
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        window.deferredPWAInstallPrompt = e;
        console.log('ShopEasy PWA beforeinstallprompt event captured and ready.');
    });

    window.addEventListener('pwa-prompt-available', (e) => {
        if (e.detail) {
            window.deferredPWAInstallPrompt = e.detail;
        }
    });

    // 4. Listen for successful installation (do not auto-open or redirect the browser tab)
    window.addEventListener('appinstalled', () => {
        window.deferredPWAInstallPrompt = null;
        isAppInstalledOnSystem = true;
        updateButtonsInstalledState();
        if (typeof showToast === 'function') {
            showToast(
                'Installation Complete',
                'ShopEasy has been installed successfully. Please open ShopEasy manually from your desktop, Start Menu, or home-screen icon.'
            );
        }
        closePwaModal();
    });

    // 5. Global trigger handler invoked by all install buttons across the app
    window.triggerPwaInstall = function () {
        // If already installed, show clear message
        if (isAppInstalled()) {
            const alreadyInstalledMsg = 'Already installed — open ShopEasy from your app icon.';
            if (typeof showToast === 'function') {
                showToast('Already installed', alreadyInstalledMsg);
            } else {
                alert(alreadyInstalledMsg);
            }
            return;
        }

        // Direct browser prompt if event was captured
        if (window.deferredPWAInstallPrompt) {
            const promptEvent = window.deferredPWAInstallPrompt;
            promptEvent.prompt();
            promptEvent.userChoice
                .then((choiceResult) => {
                    if (choiceResult && choiceResult.outcome === 'accepted') {
                        if (typeof showToast === 'function') {
                            showToast(
                                'Installation Confirmed',
                                'ShopEasy has been added to your device. Please open ShopEasy manually from your desktop, Start Menu, or home-screen icon.'
                            );
                        }
                    } else {
                        console.log('User dismissed the PWA install prompt');
                    }
                    window.deferredPWAInstallPrompt = null;
                })
                .catch((err) => {
                    console.warn('PWA install prompt error:', err);
                    window.deferredPWAInstallPrompt = null;
                    openPwaModal('general');
                });
        } else if (isIOSDevice()) {
            // iOS Safari requires manual "Add to Home Screen"
            openPwaModal('ios');
        } else {
            // Fallback for browsers that don't support or haven't fired beforeinstallprompt
            openPwaModal('general');
        }
    };

    // Modal helpers
    window.openPwaModal = function (type = 'general') {
        const modal = document.getElementById('pwaInstallModal');
        const iosTab = document.getElementById('pwaIosInstructions');
        const generalTab = document.getElementById('pwaGeneralInstructions');

        if (!modal) return;

        if (type === 'ios' || isIOSDevice()) {
            if (iosTab) iosTab.classList.remove('hidden');
            if (generalTab) generalTab.classList.add('hidden');
        } else {
            if (iosTab) iosTab.classList.add('hidden');
            if (generalTab) generalTab.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    window.closePwaModal = function () {
        const modal = document.getElementById('pwaInstallModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    };

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closePwaModal();
        }
    });
})();
