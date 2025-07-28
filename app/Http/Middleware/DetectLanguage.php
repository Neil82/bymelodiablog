<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class DetectLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->detectLanguage($request);
        
        if ($locale) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }

        return $next($request);
    }

    private function detectLanguage(Request $request): ?string
    {
        // 1. Check if language is explicitly set via URL parameter
        if ($request->has('lang')) {
            $lang = $request->get('lang');
            if ($this->isValidLanguage($lang)) {
                return $lang;
            }
        }

        // 2. Check session for previously selected language
        if (Session::has('locale')) {
            $sessionLang = Session::get('locale');
            if ($this->isValidLanguage($sessionLang)) {
                return $sessionLang;
            }
        }

        // 3. Detect from browser Accept-Language header
        $browserLang = $this->getBrowserLanguage($request);
        if ($browserLang && $this->isValidLanguage($browserLang)) {
            return $browserLang;
        }

        // 4. Fall back to default language
        $defaultLang = Language::getDefault();
        return $defaultLang ? $defaultLang->code : 'es';
    }

    private function getBrowserLanguage(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');
        if (!$acceptLanguage) {
            return null;
        }

        // Parse Accept-Language header (e.g., "en-US,en;q=0.9,es;q=0.8")
        preg_match_all('/([a-z]{2})(?:-[A-Z]{2})?(?:;q=([0-9.]+))?/', $acceptLanguage, $matches);
        
        if (empty($matches[1])) {
            return null;
        }

        $languages = [];
        for ($i = 0; $i < count($matches[1]); $i++) {
            $lang = $matches[1][$i];
            $quality = isset($matches[2][$i]) && $matches[2][$i] !== '' 
                ? (float) $matches[2][$i] 
                : 1.0;
            $languages[$lang] = $quality;
        }

        // Sort by quality (preference)
        arsort($languages);

        // Return the first supported language
        foreach (array_keys($languages) as $lang) {
            if ($this->isValidLanguage($lang)) {
                return $lang;
            }
        }

        return null;
    }

    private function isValidLanguage(string $code): bool
    {
        return Language::where('code', $code)
            ->where('is_active', true)
            ->exists();
    }
}
