<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Música',
                'slug' => 'musica',
                'description' => 'Todo sobre música juvenil, artistas emergentes y tendencias musicales',
                'color' => '#3B82F6',
                'active' => true,
            ],
            [
                'name' => 'Tendencias',
                'slug' => 'tendencias',
                'description' => 'Las últimas tendencias en moda, tecnología y cultura juvenil',
                'color' => '#8B5CF6',
                'active' => true,
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description' => 'Estilo de vida, tips y experiencias para jóvenes',
                'color' => '#10B981',
                'active' => true,
            ],
            [
                'name' => 'Entretenimiento',
                'slug' => 'entretenimiento',
                'description' => 'Películas, series, gaming y entretenimiento juvenil',
                'color' => '#F59E0B',
                'active' => true,
            ],
            [
                'name' => 'Cultura',
                'slug' => 'cultura',
                'description' => 'Arte, literatura y expresiones culturales contemporáneas',
                'color' => '#EF4444',
                'active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
