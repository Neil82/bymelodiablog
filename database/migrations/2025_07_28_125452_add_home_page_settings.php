<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SiteSetting;

return new class extends Migration
{
    public function up(): void
    {
        // Add home page configuration settings
        $homeSettings = [
            [
                'key' => 'home_status_text',
                'value' => 'Nuevo contenido cada día',
                'type' => 'text',
                'group' => 'home',
                'label' => 'Texto de estado del banner',
                'description' => 'Pequeño texto que aparece arriba del título principal'
            ],
            [
                'key' => 'home_main_title',
                'value' => 'Contenido Fresco',
                'type' => 'text',
                'group' => 'home',
                'label' => 'Título principal de la sección',
                'description' => 'Título grande que aparece en la sección de posts recientes'
            ],
            [
                'key' => 'home_main_subtitle',
                'value' => 'Lo más nuevo en cultura juvenil, directo desde nuestros creadores',
                'type' => 'textarea',
                'group' => 'home',
                'label' => 'Subtítulo de la sección',
                'description' => 'Descripción que aparece debajo del título principal'
            ]
        ];

        foreach ($homeSettings as $setting) {
            SiteSetting::create($setting);
        }
    }

    public function down(): void
    {
        SiteSetting::whereIn('key', [
            'home_status_text',
            'home_main_title', 
            'home_main_subtitle'
        ])->delete();
    }
};
