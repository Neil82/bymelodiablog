<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Language;
use App\Models\SiteSettingTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $groups = [
            'banner' => __('admin.settings.banner_section'),
            'home' => __('admin.settings.home_section'),
            'about' => __('admin.settings.about_section'),
            'seo' => __('admin.settings.seo_section'),
            'ads' => __('admin.settings.adsense_section'),
            'legal' => __('admin.settings.legal_section') ?? 'Legal (Privacy & Terms)'
        ];

        // Get current language from request or default to spanish
        $currentLanguage = $request->get('lang', 'es');
        $currentLang = Language::where('code', $currentLanguage)->first();
        $activeLanguages = Language::where('is_active', true)->get();

        $settings = [];
        foreach ($groups as $group => $title) {
            $baseSettings = SiteSetting::where('group', $group)->get()->keyBy('key');
            
            // For each setting, get the translation if not in main language
            foreach ($baseSettings as $key => $setting) {
                if ($currentLanguage !== 'es' && in_array($setting->type, ['text', 'textarea'])) {
                    // Get translation for this setting
                    $translation = SiteSettingTranslation::where('site_setting_id', $setting->id)
                        ->where('language_id', $currentLang->id ?? null)
                        ->first();
                    
                    if ($translation) {
                        $setting->translated_value = $translation->value;
                    }
                }
            }
            
            $settings[$group] = $baseSettings;
        }

        return view('admin.settings.index', compact('groups', 'settings', 'activeLanguages', 'currentLang', 'currentLanguage'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'language' => 'required|exists:languages,code'
        ]);

        $currentLanguage = $request->language;
        $language = Language::where('code', $currentLanguage)->first();

        foreach ($request->settings as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            
            if (!$setting) {
                continue;
            }

            // Handle file uploads for image settings (always update main setting)
            if ($setting->type === 'image' && $request->hasFile("files.{$key}")) {
                $file = $request->file("files.{$key}");
                
                // Delete old image if exists
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }

                // Process and save new image
                $filename = $this->processImage($file, $key);
                SiteSetting::set($key, $filename);
            } else {
                // Handle text/textarea settings with translations
                if ($currentLanguage === 'es') {
                    // Update main setting for Spanish
                    $processedValue = $setting->type === 'boolean' ? ($value ? 'true' : 'false') : $value;
                    SiteSetting::set($key, $processedValue);
                } else {
                    // Handle translations for other languages (only text/textarea)
                    if (in_array($setting->type, ['text', 'textarea'])) {
                        // Only create translation if value is not empty
                        if (!empty($value)) {
                            SiteSettingTranslation::updateOrCreate(
                                [
                                    'site_setting_id' => $setting->id,
                                    'language_id' => $language->id
                                ],
                                [
                                    'value' => $value
                                ]
                            );
                        } else {
                            // If value is empty, delete existing translation
                            SiteSettingTranslation::where('site_setting_id', $setting->id)
                                ->where('language_id', $language->id)
                                ->delete();
                        }
                    } else {
                        // For non-translatable settings (boolean, image), update main setting
                        $processedValue = $setting->type === 'boolean' ? ($value ? 'true' : 'false') : $value;
                        SiteSetting::set($key, $processedValue);
                    }
                }
            }
        }

        // Clear all settings cache
        SiteSetting::clearCache();

        $redirectUrl = route('admin.settings.index', ['lang' => $currentLanguage]);
        return redirect($redirectUrl)->with('success', 'Configuración actualizada exitosamente.');
    }

    private function processImage($file, $key)
    {
        $filename = time() . '_' . $key . '.webp';
        $path = 'settings/' . $filename;

        // Determine dimensions based on setting type
        $dimensions = $this->getImageDimensions($key);
        
        // Process image with Intervention Image
        $image = Image::make($file)
            ->resize($dimensions['width'], $dimensions['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('webp', 95);

        // Save to storage
        Storage::disk('public')->put($path, $image);

        return $path;
    }

    private function getImageDimensions($key)
    {
        $dimensions = [
            'banner_image_desktop' => ['width' => 1920, 'height' => 800],
            'banner_image_mobile' => ['width' => 800, 'height' => 600],
            'about_banner_image_desktop' => ['width' => 1920, 'height' => 600],
            'about_banner_image_mobile' => ['width' => 800, 'height' => 400],
            'og_image' => ['width' => 1200, 'height' => 630],
        ];

        return $dimensions[$key] ?? ['width' => 800, 'height' => 600];
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'setting_key' => 'required|string'
        ]);

        $file = $request->file('file');
        $key = $request->setting_key;
        
        try {
            $filename = $this->processImage($file, $key);
            
            return response()->json([
                'success' => true,
                'filename' => $filename,
                'url' => Storage::disk('public')->url($filename)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la imagen: ' . $e->getMessage()
            ], 500);
        }
    }
}