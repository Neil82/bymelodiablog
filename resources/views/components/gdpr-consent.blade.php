<!-- GDPR Cookie Consent Banner -->
<div id="gdpr-banner" class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 shadow-lg z-50 transform translate-y-full transition-transform duration-300" style="display: none;">
    <div class="max-w-7xl mx-auto">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1">
                <p class="text-sm">
                    🍪 Utilizamos cookies para mejorar tu experiencia y mostrar anuncios personalizados. 
                    Al continuar navegando, aceptas nuestro uso de cookies.
                    <a href="/privacy-policy" class="underline hover:no-underline">Política de Privacidad</a>
                </p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-6 flex space-x-3">
                <button id="gdpr-accept" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Aceptar
                </button>
                <button id="gdpr-decline" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Rechazar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const banner = document.getElementById('gdpr-banner');
        const acceptBtn = document.getElementById('gdpr-accept');
        const declineBtn = document.getElementById('gdpr-decline');
        
        // Check if user has already made a choice
        const consentChoice = localStorage.getItem('gdpr-consent');
        
        if (!consentChoice) {
            // Show banner after 2 seconds
            setTimeout(() => {
                banner.style.display = 'block';
                setTimeout(() => {
                    banner.classList.remove('translate-y-full');
                }, 100);
            }, 2000);
        }
        
        // Handle accept
        acceptBtn.addEventListener('click', function() {
            localStorage.setItem('gdpr-consent', 'accepted');
            localStorage.setItem('gdpr-consent-date', new Date().toISOString());
            hideBanner();
            
            // Load AdSense if configured
            loadAdSense();
        });
        
        // Handle decline
        declineBtn.addEventListener('click', function() {
            localStorage.setItem('gdpr-consent', 'declined');
            localStorage.setItem('gdpr-consent-date', new Date().toISOString());
            hideBanner();
        });
        
        function hideBanner() {
            banner.classList.add('translate-y-full');
            setTimeout(() => {
                banner.style.display = 'none';
            }, 300);
        }
        
        function loadAdSense() {
            @php
                $adsenseClientId = App\Models\SiteSetting::get('adsense_client_id');
                $autoAdsEnabled = App\Models\SiteSetting::get('adsense_auto_ads', 'false') === 'true';
            @endphp
            
            @if($adsenseClientId)
                // Load AdSense script dynamically
                if (!document.querySelector('script[src*="adsbygoogle.js"]')) {
                    const script = document.createElement('script');
                    script.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsenseClientId }}';
                    script.crossOrigin = 'anonymous';
                    script.async = true;
                    document.head.appendChild(script);
                    
                    @if($autoAdsEnabled)
                        script.onload = function() {
                            (adsbygoogle = window.adsbygoogle || []).push({
                                google_ad_client: "{{ $adsenseClientId }}",
                                enable_page_level_ads: true
                            });
                        };
                    @endif
                }
                
                // Initialize any existing ad slots that need initialization
                if (window.adsbygoogle) {
                    const ads = document.querySelectorAll('.adsbygoogle[data-needs-init="true"]');
                    ads.forEach(ad => {
                        if (!ad.dataset.adsbygoogleStatus) {
                            ad.removeAttribute('data-needs-init');
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        }
                    });
                }
            @endif
        }
        
        // If user previously accepted, load AdSense
        if (consentChoice === 'accepted') {
            loadAdSense();
        }
    });
</script>