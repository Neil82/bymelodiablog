// Advanced Analytics Tracking System
class AnalyticsTracker {
    constructor() {
        this.sessionId = this.getOrCreateSessionId();
        this.startTime = Date.now();
        this.lastActivityTime = Date.now();
        this.trackingInterval = null;
        this.pageStartTime = Date.now();
        this.maxScrollDepth = 0;
        this.isActive = true;
        this.eventQueue = [];
        this.config = {
            trackingInterval: 5000, // 5 seconds - much more frequent
            apiEndpoint: '/api/tracking',
            batchSize: 1, // Send immediately, don't batch
            sessionTimeout: 30 * 60 * 1000 // 30 minutes
        };
        
        this.init();
    }

    async init() {
        try {
            await this.initializeSession();
            this.startTracking();
            this.bindEvents();
            this.trackPageView();
        } catch (error) {
            console.error('Failed to initialize analytics tracker:', error);
        }
    }

    getOrCreateSessionId() {
        const STORAGE_KEY = 'analytics_session';
        const STORAGE_TIME_KEY = 'analytics_session_time';
        
        try {
            // Check if we have an existing session
            const existingSession = localStorage.getItem(STORAGE_KEY);
            const sessionTime = localStorage.getItem(STORAGE_TIME_KEY);
            
            if (existingSession && sessionTime) {
                const timeSinceSession = Date.now() - parseInt(sessionTime);
                
                // If session is less than 30 minutes old, reuse it
                if (timeSinceSession < this.config.sessionTimeout) {
                    console.log('Reusing existing session:', existingSession);
                    // Update the timestamp
                    localStorage.setItem(STORAGE_TIME_KEY, Date.now().toString());
                    return existingSession;
                }
            }
            
            // Create new session
            const newSessionId = this.generateSessionId();
            localStorage.setItem(STORAGE_KEY, newSessionId);
            localStorage.setItem(STORAGE_TIME_KEY, Date.now().toString());
            
            console.log('Created new session:', newSessionId);
            return newSessionId;
            
        } catch (error) {
            // Fallback if localStorage is not available
            console.warn('localStorage not available, using temporary session');
            return this.generateSessionId();
        }
    }

    generateSessionId() {
        return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    async initializeSession() {
        const sessionData = {
            session_id: this.sessionId,
            user_agent: navigator.userAgent,
            language_code: navigator.language.split('-')[0],
            referrer: document.referrer || null,
            started_at: new Date().toISOString()
        };

        // Geolocation will be handled server-side using IP address

        // Detect device type
        sessionData.device_type = this.detectDeviceType();
        
        // Detect browser info
        const browserInfo = this.detectBrowser();
        sessionData.browser = browserInfo.name;
        sessionData.browser_version = browserInfo.version;
        
        // Detect OS
        const osInfo = this.detectOS();
        sessionData.os = osInfo.name;
        sessionData.os_version = osInfo.version;

        // Parse UTM parameters
        const utmParams = this.parseUTMParameters();
        Object.assign(sessionData, utmParams);

        try {
            const response = await fetch('/api/tracking/session/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify(sessionData)
            });

            if (!response.ok) {
                throw new Error('Failed to initialize session');
            }

            const result = await response.json();
            console.log('Analytics session initialized:', result.session_id);
        } catch (error) {
            console.error('Failed to initialize session:', error);
        }
    }


    detectDeviceType() {
        const userAgent = navigator.userAgent.toLowerCase();
        
        if (/tablet|ipad|playbook|silk/i.test(userAgent)) {
            return 'tablet';
        }
        
        if (/mobile|iphone|ipod|android|blackberry|opera|mini|windows\sce|palm|smartphone|iemobile/i.test(userAgent)) {
            return 'mobile';
        }
        
        return 'desktop';
    }

    detectBrowser() {
        const userAgent = navigator.userAgent;
        let browserName = 'Unknown';
        let browserVersion = 'Unknown';

        if (userAgent.indexOf('Chrome') > -1) {
            browserName = 'Chrome';
            browserVersion = userAgent.match(/Chrome\/([0-9.]+)/)?.[1] || 'Unknown';
        } else if (userAgent.indexOf('Firefox') > -1) {
            browserName = 'Firefox';
            browserVersion = userAgent.match(/Firefox\/([0-9.]+)/)?.[1] || 'Unknown';
        } else if (userAgent.indexOf('Safari') > -1) {
            browserName = 'Safari';
            browserVersion = userAgent.match(/Version\/([0-9.]+)/)?.[1] || 'Unknown';
        } else if (userAgent.indexOf('Edge') > -1) {
            browserName = 'Edge';
            browserVersion = userAgent.match(/Edge\/([0-9.]+)/)?.[1] || 'Unknown';
        }

        return { name: browserName, version: browserVersion };
    }

    detectOS() {
        const userAgent = navigator.userAgent;
        let osName = 'Unknown';
        let osVersion = 'Unknown';

        if (userAgent.indexOf('Windows NT') > -1) {
            osName = 'Windows';
            osVersion = userAgent.match(/Windows NT ([0-9.]+)/)?.[1] || 'Unknown';
        } else if (userAgent.indexOf('Mac OS X') > -1) {
            osName = 'macOS';
            osVersion = userAgent.match(/Mac OS X ([0-9_]+)/)?.[1]?.replace(/_/g, '.') || 'Unknown';
        } else if (userAgent.indexOf('Linux') > -1) {
            osName = 'Linux';
        } else if (userAgent.indexOf('Android') > -1) {
            osName = 'Android';
            osVersion = userAgent.match(/Android ([0-9.]+)/)?.[1] || 'Unknown';
        } else if (userAgent.indexOf('iOS') > -1) {
            osName = 'iOS';
            osVersion = userAgent.match(/OS ([0-9_]+)/)?.[1]?.replace(/_/g, '.') || 'Unknown';
        }

        return { name: osName, version: osVersion };
    }

    parseUTMParameters() {
        const urlParams = new URLSearchParams(window.location.search);
        return {
            utm_source: urlParams.get('utm_source'),
            utm_medium: urlParams.get('utm_medium'),
            utm_campaign: urlParams.get('utm_campaign')
        };
    }

    startTracking() {
        // Send first time tracking after 3 seconds
        setTimeout(() => {
            this.trackActivity();
        }, 3000);

        // Track every 5 seconds
        this.trackingInterval = setInterval(() => {
            this.trackActivity();
        }, this.config.trackingInterval);

        // Track when page becomes hidden/visible
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.trackPageHidden();
            } else {
                this.trackPageVisible();
            }
        });

        // Track when user is about to leave
        window.addEventListener('beforeunload', () => {
            this.trackPageUnload();
        });
    }

    bindEvents() {
        // Track scroll depth
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.trackScrollDepth();
            }, 250);
        });

        // Track clicks
        document.addEventListener('click', (e) => {
            this.trackClick(e);
        });

        // Track mouse movement for activity detection
        document.addEventListener('mousemove', () => {
            this.updateLastActivity();
        });

        // Track keyboard activity
        document.addEventListener('keypress', () => {
            this.updateLastActivity();
        });

        // Track form submissions
        document.addEventListener('submit', (e) => {
            this.trackFormSubmission(e);
        });
    }

    updateLastActivity() {
        this.lastActivityTime = Date.now();
        this.isActive = true;
    }

    trackPageView() {
        const eventData = {
            event_type: 'page_view',
            url: window.location.href,
            page_title: document.title,
            event_time: new Date().toISOString(),
            post_id: this.getPostId()
        };

        this.queueEvent(eventData);
    }

    trackActivity() {
        const now = Date.now();
        const timeOnPage = Math.floor((now - this.pageStartTime) / 1000);
        
        const eventData = {
            event_type: 'activity_heartbeat',
            url: window.location.href,
            page_title: document.title,
            time_on_page: timeOnPage,
            scroll_depth: this.maxScrollDepth,
            event_time: new Date().toISOString(),
            post_id: this.getPostId(),
            event_data: {
                is_active: this.isActive,
                time_since_last_activity: now - this.lastActivityTime
            }
        };

        this.queueEvent(eventData);
        
        // Reset activity flag
        this.isActive = false;
    }

    trackScrollDepth() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const documentHeight = Math.max(
            document.body.scrollHeight,
            document.body.offsetHeight,
            document.documentElement.clientHeight,
            document.documentElement.scrollHeight,
            document.documentElement.offsetHeight
        );
        const windowHeight = window.innerHeight;
        const scrollPercent = Math.round((scrollTop / (documentHeight - windowHeight)) * 100);
        
        if (scrollPercent > this.maxScrollDepth) {
            this.maxScrollDepth = Math.min(scrollPercent, 100);
            
            // Track milestone scroll depths
            if (this.maxScrollDepth >= 25 && this.maxScrollDepth % 25 === 0) {
                const eventData = {
                    event_type: 'scroll_milestone',
                    url: window.location.href,
                    scroll_depth: this.maxScrollDepth,
                    event_time: new Date().toISOString(),
                    post_id: this.getPostId()
                };
                
                this.queueEvent(eventData);
            }
        }
    }

    trackClick(event) {
        const element = event.target;
        const elementInfo = this.getElementInfo(element);
        
        const eventData = {
            event_type: 'click',
            url: window.location.href,
            element_clicked: elementInfo.selector,
            event_time: new Date().toISOString(),
            post_id: this.getPostId(),
            event_data: {
                element_type: element.tagName.toLowerCase(),
                element_text: elementInfo.text,
                element_href: element.href || null,
                coordinates: {
                    x: event.clientX,
                    y: event.clientY
                }
            }
        };

        this.queueEvent(eventData);
    }

    trackFormSubmission(event) {
        const form = event.target;
        const formData = new FormData(form);
        const formFields = {};
        
        for (let [key, value] of formData.entries()) {
            // Don't track sensitive data
            if (!this.isSensitiveField(key)) {
                formFields[key] = typeof value === 'string' ? value.substring(0, 100) : 'file';
            }
        }

        const eventData = {
            event_type: 'form_submission',
            url: window.location.href,
            event_time: new Date().toISOString(),
            event_data: {
                form_id: form.id || null,
                form_action: form.action || null,
                form_method: form.method || 'GET',
                field_count: Object.keys(formFields).length,
                fields: formFields
            }
        };

        this.queueEvent(eventData);
    }

    trackPageHidden() {
        const timeOnPage = Math.floor((Date.now() - this.pageStartTime) / 1000);
        
        const eventData = {
            event_type: 'page_hidden',
            url: window.location.href,
            time_on_page: timeOnPage,
            scroll_depth: this.maxScrollDepth,
            event_time: new Date().toISOString(),
            post_id: this.getPostId()
        };

        this.queueEvent(eventData);
        this.flushEventQueue();
    }

    trackPageVisible() {
        const eventData = {
            event_type: 'page_visible',
            url: window.location.href,
            event_time: new Date().toISOString(),
            post_id: this.getPostId()
        };

        this.queueEvent(eventData);
    }

    trackPageUnload() {
        const timeOnPage = Math.floor((Date.now() - this.pageStartTime) / 1000);
        
        const eventData = {
            event_type: 'page_unload',
            url: window.location.href,
            time_on_page: timeOnPage,
            scroll_depth: this.maxScrollDepth,
            event_time: new Date().toISOString(),
            post_id: this.getPostId()
        };

        this.queueEvent(eventData);
        this.flushEventQueue();
    }

    queueEvent(eventData) {
        this.eventQueue.push({
            ...eventData,
            session_id: this.sessionId
        });

        if (this.eventQueue.length >= this.config.batchSize) {
            this.flushEventQueue();
        }
    }

    async flushEventQueue() {
        if (this.eventQueue.length === 0) return;

        const events = [...this.eventQueue];
        this.eventQueue = [];

        try {
            const response = await fetch('/api/tracking/events', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({ events })
            });

            if (!response.ok) {
                throw new Error('Failed to send tracking events');
            }
        } catch (error) {
            console.error('Failed to send tracking events:', error);
            // Re-queue events for retry
            this.eventQueue.unshift(...events);
        }
    }

    getElementInfo(element) {
        let selector = element.tagName.toLowerCase();
        
        if (element.id) {
            selector += '#' + element.id;
        }
        
        if (element.className) {
            selector += '.' + element.className.split(' ').join('.');
        }

        const text = element.textContent || element.value || element.alt || '';
        
        return {
            selector: selector,
            text: text.substring(0, 100) // Limit text length
        };
    }

    getPostId() {
        // Try to get post ID from meta tag first
        const metaPostId = document.querySelector('meta[name="post-id"]');
        if (metaPostId) {
            const content = metaPostId.getAttribute('content');
            if (content && content.trim() !== '') {
                const postId = parseInt(content);
                if (!isNaN(postId)) {
                    console.log('Found post ID from meta tag:', postId);
                    return postId;
                }
            }
        }

        // Try to extract from URL patterns
        const pathname = window.location.pathname;
        
        // Pattern for /blog/post-slug or /posts/123
        const blogMatch = pathname.match(/\/blog\/[\w-]+/) || pathname.match(/\/posts?\/(\d+)/);
        if (blogMatch) {
            console.log('Detected blog post URL but no meta tag found:', pathname);
        }

        console.log('No post ID found for:', pathname);
        return null;
    }

    isSensitiveField(fieldName) {
        const sensitiveFields = [
            'password', 'pass', 'pwd', 'secret', 'token', 'key',
            'credit_card', 'card_number', 'cvv', 'ssn', 'social_security'
        ];
        
        return sensitiveFields.some(field => 
            fieldName.toLowerCase().includes(field)
        );
    }

    getCSRFToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : '';
    }

    destroy() {
        if (this.trackingInterval) {
            clearInterval(this.trackingInterval);
        }
        this.flushEventQueue();
    }

    clearSession() {
        try {
            localStorage.removeItem('analytics_session');
            localStorage.removeItem('analytics_session_time');
            console.log('Session cleared');
        } catch (error) {
            console.warn('Could not clear session storage');
        }
    }
}

// Initialize analytics tracker when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if not in admin (allow localhost for testing)
    if (!window.location.pathname.startsWith('/admin')) {
        window.analyticsTracker = new AnalyticsTracker();
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.analyticsTracker) {
        window.analyticsTracker.destroy();
    }
});

// Export for use in other modules
window.AnalyticsTracker = AnalyticsTracker;