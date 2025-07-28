<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Language;
use App\Models\CategoryTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TranslateCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:translate 
                            {--lang=* : Languages to translate to (e.g., en,fr,it)}
                            {--auto : Use automatic translation service}
                            {--force : Overwrite existing translations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create translations for existing categories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏷️  Starting category translation process...');

        // Get languages to translate to
        $langCodes = $this->option('lang');
        $useAutoTranslation = $this->option('auto');
        $force = $this->option('force');

        if (empty($langCodes)) {
            // Get all active languages except default
            $languages = Language::active()
                ->where('is_default', false)
                ->pluck('code')
                ->toArray();
        } else {
            $languages = is_array($langCodes) ? $langCodes : explode(',', $langCodes[0]);
        }

        if (empty($languages)) {
            $this->error('No languages found to translate to.');
            return 1;
        }

        $this->info('Languages to translate to: ' . implode(', ', $languages));

        // Get all categories
        $categories = Category::where('active', true)->get();
        $this->info("Found {$categories->count()} categories to translate.");

        $progressBar = $this->output->createProgressBar($categories->count() * count($languages));
        $progressBar->start();

        $translated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($categories as $category) {
            foreach ($languages as $langCode) {
                $language = Language::where('code', $langCode)->active()->first();
                
                if (!$language) {
                    $this->newLine();
                    $this->warn("Language '{$langCode}' not found or inactive. Skipping.");
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Check if translation already exists
                $existingTranslation = CategoryTranslation::where('category_id', $category->id)
                    ->where('language_id', $language->id)
                    ->first();

                if ($existingTranslation && !$force) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                try {
                    // Create or update translation
                    $translationData = [
                        'category_id' => $category->id,
                        'language_id' => $language->id,
                        'name' => $useAutoTranslation ? 
                            $this->translateText($category->name, $langCode) : 
                            $category->name . " ({$language->native_name})",
                        'slug' => Str::slug($category->name . '-' . $langCode),
                        'description' => $useAutoTranslation && $category->description ? 
                            $this->translateText($category->description, $langCode) : 
                            $category->description
                    ];

                    CategoryTranslation::updateOrCreate(
                        [
                            'category_id' => $category->id,
                            'language_id' => $language->id
                        ],
                        $translationData
                    );

                    $translated++;

                } catch (\Exception $e) {
                    $this->newLine();
                    $this->error("Error translating category '{$category->name}' to {$langCode}: " . $e->getMessage());
                    $errors++;
                }

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('✅ Category translation completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Translated', $translated],
                ['Skipped', $skipped],
                ['Errors', $errors],
                ['Total processed', $translated + $skipped + $errors]
            ]
        );

        return 0;
    }

    /**
     * Translate text using a simple translation service
     */
    private function translateText(string $text, string $targetLang): string
    {
        $translations = [
            'en' => [
                'Cultura Juvenil' => 'Youth Culture',
                'Música' => 'Music',
                'Lifestyle' => 'Lifestyle',
                'Tendencias' => 'Trends',
                'Tecnología' => 'Technology',
                'Arte' => 'Art',
                'Deportes' => 'Sports',
                'Moda' => 'Fashion',
                'Viajes' => 'Travel',
                'Gastronomía' => 'Gastronomy'
            ],
            'fr' => [
                'Cultura Juvenil' => 'Culture Jeunesse',
                'Música' => 'Musique',
                'Lifestyle' => 'Style de vie',
                'Tendencias' => 'Tendances',
                'Tecnología' => 'Technologie',
                'Arte' => 'Art',
                'Deportes' => 'Sports',
                'Moda' => 'Mode',
                'Viajes' => 'Voyages',
                'Gastronomía' => 'Gastronomie'
            ],
            'it' => [
                'Cultura Juvenil' => 'Cultura Giovanile',
                'Música' => 'Musica',
                'Lifestyle' => 'Stile di vita',
                'Tendencias' => 'Tendenze',
                'Tecnología' => 'Tecnologia',
                'Arte' => 'Arte',
                'Deportes' => 'Sport',
                'Moda' => 'Moda',
                'Viajes' => 'Viaggi',
                'Gastronomía' => 'Gastronomia'
            ]
        ];

        if (isset($translations[$targetLang])) {
            foreach ($translations[$targetLang] as $spanish => $translation) {
                $text = str_ireplace($spanish, $translation, $text);
            }
        }

        return $text;
    }
}
