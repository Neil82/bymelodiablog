<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Language;
use App\Models\PostTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TranslatePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:translate 
                            {--lang=* : Languages to translate to (e.g., en,fr,it)}
                            {--auto : Use automatic translation service}
                            {--force : Overwrite existing translations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create translations for existing posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌍 Starting post translation process...');

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

        // Get default language
        $defaultLanguage = Language::getDefault();
        if (!$defaultLanguage) {
            $this->error('No default language found.');
            return 1;
        }

        // Get all posts
        $posts = Post::published()->get();
        $this->info("Found {$posts->count()} posts to translate.");

        $progressBar = $this->output->createProgressBar($posts->count() * count($languages));
        $progressBar->start();

        $translated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($posts as $post) {
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
                $existingTranslation = PostTranslation::where('post_id', $post->id)
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
                        'post_id' => $post->id,
                        'language_id' => $language->id,
                        'title' => $useAutoTranslation ? 
                            $this->translateText($post->title, $langCode) : 
                            $post->title . " ({$language->native_name})",
                        'slug' => Str::slug($post->title . '-' . $langCode),
                        'excerpt' => $useAutoTranslation && $post->excerpt ? 
                            $this->translateText($post->excerpt, $langCode) : 
                            $post->excerpt,
                        'content' => $useAutoTranslation ? 
                            $this->translateText(strip_tags($post->content), $langCode) : 
                            $post->content . "\n\n<p><em>Translation needed for {$language->native_name}</em></p>",
                        'seo_meta' => $post->seo_meta
                    ];

                    PostTranslation::updateOrCreate(
                        [
                            'post_id' => $post->id,
                            'language_id' => $language->id
                        ],
                        $translationData
                    );

                    $translated++;

                } catch (\Exception $e) {
                    $this->newLine();
                    $this->error("Error translating post '{$post->title}' to {$langCode}: " . $e->getMessage());
                    $errors++;
                }

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('✅ Translation completed!');
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
     * In production, you might want to use Google Translate API, DeepL, etc.
     */
    private function translateText(string $text, string $targetLang): string
    {
        // For now, we'll just add a language indicator
        // In production, integrate with Google Translate API or similar service
        
        $translations = [
            'en' => [
                'Cultura Juvenil' => 'Youth Culture',
                'Música' => 'Music',
                'Lifestyle' => 'Lifestyle',
                'Tendencias' => 'Trends',
                'Descubre' => 'Discover',
                'las últimas' => 'the latest',
                'en' => 'in',
                'y' => 'and'
            ],
            'fr' => [
                'Cultura Juvenil' => 'Culture Jeunesse',
                'Música' => 'Musique',
                'Lifestyle' => 'Style de vie',
                'Tendencias' => 'Tendances',
                'Descubre' => 'Découvrez',
                'las últimas' => 'les dernières',
                'en' => 'en',
                'y' => 'et'
            ],
            'it' => [
                'Cultura Juvenil' => 'Cultura Giovanile',
                'Música' => 'Musica',
                'Lifestyle' => 'Stile di vita',
                'Tendencias' => 'Tendenze',
                'Descubre' => 'Scopri',
                'las últimas' => 'le ultime',
                'en' => 'in',
                'y' => 'e'
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
