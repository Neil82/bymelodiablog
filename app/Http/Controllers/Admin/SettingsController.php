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
    public function index()
    {
        $groups = [
            'banner' => __('admin.settings.banner_section'),
            'home' => __('admin.settings.home_section'),
            'seo' => __('admin.settings.seo_section'),
            'ads' => __('admin.settings.adsense_section'),
            'legal' => __('admin.settings.legal_section') ?? 'Legal (Privacy & Terms)'
        ];

        $settings = [];
        foreach ($groups as $group => $title) {
            $settings[$group] = SiteSetting::where('group', $group)->get()->keyBy('key');
        }

        $activeLanguages = Language::where('is_active', true)->get();
        $currentLang = app()->getLocale();

        return view('admin.settings.index', compact('groups', 'settings', 'activeLanguages', 'currentLang'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            
            if (!$setting) {
                continue;
            }

            // Handle file uploads for image settings
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
                // Handle regular text/textarea/boolean settings
                $processedValue = $setting->type === 'boolean' ? ($value ? 'true' : 'false') : $value;
                SiteSetting::set($key, $processedValue);
            }
        }

        // Clear all settings cache
        SiteSetting::clearCache();

        return redirect()->route('admin.settings.index')
                        ->with('success', 'Configuración actualizada exitosamente.');
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