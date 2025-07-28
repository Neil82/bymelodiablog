class AnalyticsTracker {
    constructor() {
        this.sessionId = this.generateSessionId();
        this.events = [];
        this.currentUrl = window.location.href;
        this.currentTitle = document.title;
        this.startTime = Date.now();
        this.lastActivityTime = Date.now();
        this.isSessionStarted = false;
        this.sendInterval = null;
        this.position = null;
    }

    async init() {
        try {
            await this.detectBrowserInfo();
            await this.detectLocation();
            await this.initializeSession();
            this.bindEvents();
            this.startPeriodicSend();
            this.trackPageView();
        } catch (error) {
            console.error('Failed to initialize analytics tracker:', error);
        }
    }

    generateSessionId() {
        return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    async detectBrowserInfo() {
        const ua = navigator.userAgent;
        this.browserInfo = {
            user_agent: ua,
            language_code: navigator.language || navigator.userLanguage,
            device_type: this.detectDeviceType(ua),
            browser: this.detectBrowser(ua),
            browser_version: this.detectBrowserVersion(ua),
            os: this.detectOS(ua),
            os_version: this.detectOSVersion(ua)
        };
    }

    async detectLocation() {
        try {
            if ('geolocation' in navigator) {
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        timeout: 5000,
                        enableHighAccuracy: false
                    });
                });
                this.position = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };
            }
        } catch (error) {
            console.log('Geolocation not available or denied:', error);
            this.position = null;
        }
    }

    async initializeSession() {
        try {
            const sessionData = {
                session_id: this.sessionId,
                user_agent: this.browserInfo.user_agent,
                language_code: this.browserInfo.language_code,
                referrer: document.referrer || null,
                latitude: this.position?.latitude || null,
                longitude: this.position?.longitude || null,
                device_type: this.browserInfo.device_type,
                browser: this.browserInfo.browser,
                browser_version: this.browserInfo.browser_version,
                os: this.browserInfo.os,
                os_version: this.browserInfo.os_version,
                utm_source: this.getUrlParameter('utm_source'),
                utm_medium: this.getUrlParameter('utm_medium'),
                utm_campaign: this.getUrlParameter('utm_campaign'),
                started_at: new Date().toISOString()
            };

            const response = await fetch('/api/tracking/session/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify(sessionData)
            });

            if (!response.ok) {
                if (response.status === 419) {
                    console.error('CSRF token mismatch - analytics disabled');
                    return;
                }
                throw new Error('Failed to initialize session');
            }

            const result = await response.json();
            this.isSessionStarted = result.success;
            
        } catch (error) {
            console.error('Failed to initialize session:', error);
            throw error;
        }
    }

    trackPageView() {
        this.addEvent('page_view', {
            url: this.currentUrl,
            page_title: this.currentTitle,
            post_id: this.extractPostId()
        });
    }

    trackClick(element) {
        this.addEvent('click', {
            url: this.currentUrl,
            element_clicked: this.getElementSelector(element)
        });
    }

    trackScroll() {
        const scrollDepth = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
        this.addEvent('scroll', {
            url: this.currentUrl,
            scroll_depth: Math.min(scrollDepth, 100)
        });
    }

    addEvent(eventType, data = {}) {
        if (!this.isSessionStarted) return;

        const event = {
            session_id: this.sessionId,
            event_type: eventType,
            url: data.url || this.currentUrl,
            page_title: data.page_title || this.currentTitle,
            post_id: data.post_id || null,
            time_on_page: data.time_on_page || Math.round((Date.now() - this.startTime) / 1000),
            scroll_depth: data.scroll_depth || null,
            element_clicked: data.element_clicked || null,
            event_data: data.event_data || null,
            event_time: new Date().toISOString()
        };

        this.events.push(event);
        this.lastActivityTime = Date.now();

        // Send immediately for important events
        if (['page_view', 'click'].includes(eventType)) {
            this.sendEvents();
        }
    }

    async sendEvents() {
        if (this.events.length === 0 || !this.isSessionStarted) return;

        try {
            const eventsToSend = [...this.events];
            this.events = [];

            const response = await fetch('/api/tracking/events', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({ events: eventsToSend })
            });

            if (!response.ok) {
                // Put events back if failed
                this.events.unshift(...eventsToSend);
                throw new Error('Failed to send events');
            }

        } catch (error) {
            console.error('Failed to send analytics events:', error);
        }
    }

    bindEvents() {
        // Track clicks
        document.addEventListener('click', (e) => {
            this.trackClick(e.target);
        });

        // Track scroll
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.trackScroll();
            }, 250);
        });

        // Track page unload
        window.addEventListener('beforeunload', () => {
            this.endSession();
        });

        // Track page visibility changes
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.sendEvents();
            }
        });
    }

    startPeriodicSend() {
        this.sendInterval = setInterval(() => {
            this.sendEvents();
        }, 10000); // Send every 10 seconds
    }

    async endSession() {
        if (!this.isSessionStarted) return;

        this.sendEvents();

        try {
            await fetch('/api/tracking/session/end', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({ session_id: this.sessionId })
            });
        } catch (error) {
            console.error('Failed to end session:', error);
        }

        if (this.sendInterval) {
            clearInterval(this.sendInterval);
        }
    }

    // Helper methods
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token) {
            console.warn('CSRF token not found - analytics may not work');
            return '';
        }
        return token.getAttribute('content');
    }

    getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    extractPostId() {
        // Try to extract post ID from URL or meta tags
        const postMeta = document.querySelector('meta[name="post-id"]');
        if (postMeta) {
            return parseInt(postMeta.getAttribute('content'));
        }
        return null;
    }

    getElementSelector(element) {
        if (element.id) return `#${element.id}`;
        if (element.className) return `.${element.className.split(' ')[0]}`;
        return element.tagName.toLowerCase();
    }

    detectDeviceType(ua) {
        if (/tablet|ipad|playbook|silk/i.test(ua)) return 'tablet';
        if (/mobile|iphone|ipod|android|blackberry|opera|mini|windows\sce|palm|smartphone|iemobile/i.test(ua)) return 'mobile';
        return 'desktop';
    }

    detectBrowser(ua) {
        if (ua.includes('Firefox')) return 'Firefox';
        if (ua.includes('Chrome')) return 'Chrome';
        if (ua.includes('Safari')) return 'Safari';
        if (ua.includes('Edge')) return 'Edge';
        if (ua.includes('Opera')) return 'Opera';
        return 'Unknown';
    }

    detectBrowserVersion(ua) {
        const match = ua.match(/(chrome|firefox|safari|edge|opera)\/?\s*(\d+)/i);
        return match ? match[2] : 'Unknown';
    }

    detectOS(ua) {
        if (ua.includes('Windows')) return 'Windows';
        if (ua.includes('Mac')) return 'macOS';
        if (ua.includes('Linux')) return 'Linux';
        if (ua.includes('Android')) return 'Android';
        if (ua.includes('iOS')) return 'iOS';
        return 'Unknown';
    }

    detectOSVersion(ua) {
        if (ua.includes('Windows NT 10.0')) return '10';
        if (ua.includes('Windows NT 6.3')) return '8.1';
        if (ua.includes('Windows NT 6.1')) return '7';
        if (ua.includes('Mac OS X')) {
            const match = ua.match(/Mac OS X (\d+[._]\d+)/);
            return match ? match[1].replace('_', '.') : 'Unknown';
        }
        return 'Unknown';
    }
}

// Initialize analytics when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.analyticsTracker = new AnalyticsTracker();
    window.analyticsTracker.init();
});