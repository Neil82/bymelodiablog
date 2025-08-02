<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use App\Models\TrackingEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    public function startSession(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string|max:255',
                'user_agent' => 'required|string',
                'language_code' => 'nullable|string|max:5',
                'referrer' => 'nullable|string|max:500',
                'device_type' => 'nullable|string|max:50',
                'browser' => 'nullable|string|max:100',
                'browser_version' => 'nullable|string|max:50',
                'os' => 'nullable|string|max:100',
                'os_version' => 'nullable|string|max:50',
                'utm_source' => 'nullable|string|max:100',
                'utm_medium' => 'nullable|string|max:100',
                'utm_campaign' => 'nullable|string|max:100',
                'started_at' => 'required|date'
            ]);

            // Get IP address
            $ipAddress = $request->ip();
            
            // Always get geolocation data from IP
            $geoData = $this->getGeolocationFromIP($ipAddress);

            // Check if session already exists
            $existingSession = UserSession::where('session_id', $validated['session_id'])->first();
            
            if ($existingSession) {
                return response()->json([
                    'success' => true,
                    'session_id' => $existingSession->session_id,
                    'message' => 'Session already exists'
                ]);
            }

            // Detect if it's a bot
            $isBot = $this->detectBot($validated['user_agent']);

            // Create new session
            $session = UserSession::create([
                'session_id' => $validated['session_id'],
                'ip_address' => $ipAddress,
                'user_agent' => $validated['user_agent'],
                'country_code' => $geoData['country_code'] ?? null,
                'country_name' => $geoData['country_name'] ?? null,
                'city' => $geoData['city'] ?? null,
                'region' => $geoData['region'] ?? null,
                'latitude' => $geoData['latitude'] ?? null,
                'longitude' => $geoData['longitude'] ?? null,
                'device_type' => $validated['device_type'],
                'browser' => $validated['browser'],
                'browser_version' => $validated['browser_version'],
                'os' => $validated['os'],
                'os_version' => $validated['os_version'],
                'referrer' => $validated['referrer'],
                'utm_source' => $validated['utm_source'],
                'utm_medium' => $validated['utm_medium'],
                'utm_campaign' => $validated['utm_campaign'],
                'language_code' => $validated['language_code'],
                'started_at' => $validated['started_at'],
                'last_activity_at' => now(),
                'is_bot' => $isBot
            ]);

            return response()->json([
                'success' => true,
                'session_id' => $session->session_id,
                'message' => 'Session started successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to start tracking session: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to start session'
            ], 500);
        }
    }

    public function trackEvents(Request $request)
    {
        try {
            $validated = $request->validate([
                'events' => 'required|array|max:50',
                'events.*.session_id' => 'required|string',
                'events.*.event_type' => 'required|string|max:100',
                'events.*.url' => 'required|string|max:500',
                'events.*.page_title' => 'nullable|string|max:200',
                'events.*.post_id' => 'nullable|integer|exists:posts,id',
                'events.*.time_on_page' => 'nullable|integer|min:0',
                'events.*.scroll_depth' => 'nullable|integer|min:0|max:100',
                'events.*.element_clicked' => 'nullable|string|max:200',
                'events.*.event_data' => 'nullable|array',
                'events.*.event_time' => 'required|date'
            ]);

            $processedEvents = 0;
            $errors = [];

            foreach ($validated['events'] as $eventData) {
                try {
                    // Find the session
                    $session = UserSession::where('session_id', $eventData['session_id'])->first();
                    
                    if (!$session) {
                        $errors[] = "Session not found: {$eventData['session_id']}";
                        continue;
                    }

                    // Update session activity
                    $session->updateActivity();
                    
                    // Increment page views for page_view events
                    if ($eventData['event_type'] === 'page_view') {
                        $session->increment('page_views');
                    }

                    // Create tracking event
                    TrackingEvent::create([
                        'user_session_id' => $session->id,
                        'event_type' => $eventData['event_type'],
                        'url' => $eventData['url'],
                        'page_title' => $eventData['page_title'] ?? null,
                        'post_id' => $eventData['post_id'] ?? null,
                        'time_on_page' => $eventData['time_on_page'] ?? null,
                        'scroll_depth' => $eventData['scroll_depth'] ?? null,
                        'element_clicked' => $eventData['element_clicked'] ?? null,
                        'event_data' => $eventData['event_data'] ?? null,
                        'event_time' => $eventData['event_time']
                    ]);

                    $processedEvents++;

                } catch (\Exception $e) {
                    $errors[] = "Failed to process event: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'processed_events' => $processedEvents,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to track events: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to track events'
            ], 500);
        }
    }

    public function endSession(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string'
            ]);

            $session = UserSession::where('session_id', $validated['session_id'])->first();
            
            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            $session->endSession();

            return response()->json([
                'success' => true,
                'message' => 'Session ended successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to end session: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to end session'
            ], 500);
        }
    }

    private function getGeolocationFromIP($ipAddress)
    {
        try {
            // Skip geolocation for local/private IPs
            if ($this->isPrivateIP($ipAddress)) {
                // Return test data for local development
                return [
                    'country_code' => 'PE',
                    'country_name' => 'Peru',
                    'city' => 'Lima',
                    'region' => 'Lima',
                    'latitude' => -12.0464,
                    'longitude' => -77.0428
                ];
            }

            // Using ip-api.com (free tier: 1000 requests/month)
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ipAddress}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success') {
                    return [
                        'country_code' => $data['countryCode'] ?? null,
                        'country_name' => $data['country'] ?? null,
                        'city' => $data['city'] ?? null,
                        'region' => $data['regionName'] ?? null,
                        'latitude' => $data['lat'] ?? null,
                        'longitude' => $data['lon'] ?? null
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to get geolocation: ' . $e->getMessage());
        }

        return null;
    }

    private function isPrivateIP($ip)
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    private function detectBot($userAgent)
    {
        $botPatterns = [
            'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider',
            'yandexbot', 'facebookexternalhit', 'twitterbot', 'rogerbot',
            'linkedinbot', 'embedly', 'quora', 'showyoubot', 'outbrain',
            'pinterest', 'developers.google.com', 'www.google.com',
            'spider', 'crawler', 'bot', 'crawl'
        ];

        $userAgentLower = strtolower($userAgent);
        
        foreach ($botPatterns as $pattern) {
            if (strpos($userAgentLower, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}
