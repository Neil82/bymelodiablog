<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, $code)
    {
        $language = Language::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$language) {
            return redirect()->back()->with('error', 'Language not found');
        }

        App::setLocale($code);
        Session::put('locale', $code);

        return redirect()->back()->with('success', 'Language changed successfully');
    }

    public function getAvailableLanguages()
    {
        return response()->json([
            'languages' => Language::active()
                ->orderBy('sort_order')
                ->get(['code', 'name', 'native_name', 'flag_icon']),
            'current' => app()->getLocale()
        ]);
    }

    public function detectBrowserLanguage(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if (!$acceptLanguage) {
            return response()->json(['detected' => null]);
        }

        // Parse Accept-Language header
        preg_match_all('/([a-z]{2})(?:-[A-Z]{2})?(?:;q=([0-9.]+))?/', $acceptLanguage, $matches);
        
        if (empty($matches[1])) {
            return response()->json(['detected' => null]);
        }

        $languages = [];
        for ($i = 0; $i < count($matches[1]); $i++) {
            $lang = $matches[1][$i];
            $quality = isset($matches[2][$i]) && $matches[2][$i] !== '' 
                ? (float) $matches[2][$i] 
                : 1.0;
            $languages[$lang] = $quality;
        }

        arsort($languages);

        // Find first supported language
        foreach (array_keys($languages) as $lang) {
            $supportedLang = Language::where('code', $lang)
                ->where('is_active', true)
                ->first();
                
            if ($supportedLang) {
                return response()->json([
                    'detected' => $supportedLang->code,
                    'language' => $supportedLang
                ]);
            }
        }

        return response()->json(['detected' => null]);
    }
}
