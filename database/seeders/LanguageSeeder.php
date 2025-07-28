<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'code' => 'es',
                'name' => 'Español',
                'native_name' => 'Español',
                'flag_icon' => '🇪🇸',
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'flag_icon' => '🇺🇸',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2
            ],
            [
                'code' => 'fr',
                'name' => 'Français',
                'native_name' => 'Français',
                'flag_icon' => '🇫🇷',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 3
            ],
            [
                'code' => 'it',
                'name' => 'Italiano',
                'native_name' => 'Italiano',
                'flag_icon' => '🇮🇹',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 4
            ],
            [
                'code' => 'pt',
                'name' => 'Português',
                'native_name' => 'Português',
                'flag_icon' => '🇵🇹',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 5
            ],
            [
                'code' => 'de',
                'name' => 'Deutsch',
                'native_name' => 'Deutsch',
                'flag_icon' => '🇩🇪',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 6
            ]
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
