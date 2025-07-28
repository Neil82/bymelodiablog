<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\Language;
use App\Models\SiteSettingTranslation;

class SiteSettingTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $englishLanguage = Language::where('code', 'en')->first();
        
        if (!$englishLanguage) {
            return;
        }

        // Traducciones de configuraciones principales
        $translations = [
            'banner_title' => 'ByMelodia',
            'banner_subtitle' => '🎵 **Discover youth culture** that\'s defining the future.

✨ Trends, music, lifestyle and everything that moves **Generation Z**.

🚀 Fresh, authentic and always updated content to keep you **up to date**.',
            'banner_button_text' => 'Explore content',
            'banner_button_url' => '/blog',
            'home_main_title' => 'Fresh Content',
            'home_main_subtitle' => 'The latest in youth culture, straight from our creators',
            'home_status_text' => 'New content every day',
            'site_name' => 'ByMelodia - Youth Culture Blog',
            'site_description' => 'Discover the latest trends in youth culture, music, fashion and lifestyle. Fresh and authentic content for Generation Z.',
            'site_keywords' => 'youth culture, music, trends, generation z, lifestyle, fashion, urban culture',
            'og_title' => 'ByMelodia - Youth Culture Blog',
            'og_description' => 'Discover the latest trends in youth culture, music, fashion and lifestyle. Fresh and authentic content for Generation Z.',
            'twitter_title' => 'ByMelodia - Youth Culture Blog',
            'twitter_description' => 'Discover the latest trends in youth culture, music, fashion and lifestyle. Fresh and authentic content for Generation Z.',
            'twitter_handle' => '@bymelodia',
        ];

        foreach ($translations as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            
            if ($setting) {
                SiteSettingTranslation::updateOrCreate(
                    [
                        'site_setting_id' => $setting->id,
                        'language_id' => $englishLanguage->id,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }

        $this->command->info('Site setting translations created successfully!');
    }
}