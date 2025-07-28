// Language Detection and Selector System
class LanguageDetector {
    constructor() {
        this.currentLanguage = null;
        this.availableLanguages = [];
        this.init();
    }

    async init() {
        await this.loadAvailableLanguages();
        this.createLanguageSelector();
        this.detectAndSetLanguage();
        this.bindEvents();
    }

    async loadAvailableLanguages() {
        try {
            const response = await fetch('/api/languages/available');
            const data = await response.json();
            this.availableLanguages = data.languages;
            this.currentLanguage = data.current;
        } catch (error) {
            console.error('Failed to load available languages:', error);
        }
    }

    async detectAndSetLanguage() {
        // Check if user has already selected a language
        if (localStorage.getItem('user_selected_language')) {
            return;
        }

        try {
            const response = await fetch('/api/languages/detect-browser', {
                headers: {
                    'Accept-Language': navigator.language + ',' + navigator.languages.join(',')
                }
            });
            const data = await response.json();

            if (data.detected && data.detected !== this.currentLanguage) {
                this.showLanguageSuggestion(data.language);
            }
        } catch (error) {
            console.error('Failed to detect browser language:', error);
        }
    }

    createLanguageSelector() {
        const selector = document.getElementById('language-selector');
        if (!selector) return;

        let html = `
            <div class="language-dropdown relative">
                <button class="language-toggle flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span class="flag text-lg">${this.getCurrentLanguageFlag()}</span>
                    <span class="language-name">${this.getCurrentLanguageName()}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="language-menu absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 hidden z-50">
        `;

        this.availableLanguages.forEach(lang => {
            const isActive = lang.code === this.currentLanguage;
            html += `
                <a href="/language/switch/${lang.code}" 
                   class="language-option flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors ${isActive ? 'bg-blue-50 dark:bg-blue-900' : ''}"
                   data-lang="${lang.code}">
                    <span class="flag text-lg">${lang.flag_icon}</span>
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">${lang.native_name}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${lang.name}</div>
                    </div>
                    ${isActive ? '<svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' : ''}
                </a>
            `;
        });

        html += `
                </div>
            </div>
        `;

        selector.innerHTML = html;
    }

    showLanguageSuggestion(detectedLanguage) {
        // Create language suggestion banner
        const banner = document.createElement('div');
        banner.className = 'language-suggestion fixed top-0 left-0 right-0 bg-blue-600 text-white px-4 py-3 z-50 shadow-lg';
        banner.innerHTML = `
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-lg">${detectedLanguage.flag_icon}</span>
                    <span>We detected your browser language is <strong>${detectedLanguage.native_name}</strong>. Would you like to switch?</span>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="accept-language px-4 py-1 bg-white text-blue-600 rounded hover:bg-gray-100 transition-colors text-sm font-medium" 
                            data-lang="${detectedLanguage.code}">
                        Switch to ${detectedLanguage.native_name}
                    </button>
                    <button class="dismiss-suggestion text-blue-100 hover:text-white transition-colors text-sm">
                        No, thanks
                    </button>
                </div>
            </div>
        `;

        document.body.prepend(banner);

        // Add some top padding to body to account for the banner
        document.body.style.paddingTop = '60px';

        // Bind banner events
        banner.querySelector('.accept-language').addEventListener('click', (e) => {
            const langCode = e.target.dataset.lang;
            this.switchLanguage(langCode);
            this.dismissSuggestion();
        });

        banner.querySelector('.dismiss-suggestion').addEventListener('click', () => {
            this.dismissSuggestion();
            localStorage.setItem('language_suggestion_dismissed', 'true');
        });

        // Auto-dismiss after 10 seconds
        setTimeout(() => {
            if (document.querySelector('.language-suggestion')) {
                this.dismissSuggestion();
            }
        }, 10000);
    }

    dismissSuggestion() {
        const banner = document.querySelector('.language-suggestion');
        if (banner) {
            banner.remove();
            document.body.style.paddingTop = '';
        }
    }

    bindEvents() {
        // Language dropdown toggle
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('.language-toggle');
            const menu = document.querySelector('.language-menu');
            
            if (toggle && menu) {
                menu.classList.toggle('hidden');
            } else if (!e.target.closest('.language-dropdown')) {
                // Close menu when clicking outside
                if (menu) {
                    menu.classList.add('hidden');
                }
            }
        });

        // Language option clicks
        document.addEventListener('click', (e) => {
            const option = e.target.closest('.language-option');
            if (option) {
                e.preventDefault();
                const langCode = option.dataset.lang;
                this.switchLanguage(langCode);
            }
        });
    }

    async switchLanguage(langCode) {
        try {
            localStorage.setItem('user_selected_language', langCode);
            
            // Simply redirect to the language switch route (GET request)
            window.location.href = `/language/switch/${langCode}`;
            
        } catch (error) {
            console.error('Failed to switch language:', error);
        }
    }

    getCurrentLanguageFlag() {
        const current = this.availableLanguages.find(lang => lang.code === this.currentLanguage);
        return current ? current.flag_icon : '🌐';
    }

    getCurrentLanguageName() {
        const current = this.availableLanguages.find(lang => lang.code === this.currentLanguage);
        return current ? current.native_name : 'Language';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new LanguageDetector();
});

// Export for use in other modules
window.LanguageDetector = LanguageDetector;