<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add about page configuration settings
        $aboutSettings = [
            [
                'key' => 'about_banner_image_desktop',
                'value' => '',
                'type' => 'image',
                'group' => 'about',
                'label' => 'Imagen de banner para desktop',
                'description' => 'Imagen que aparece en la parte superior de la página Acerca de en dispositivos de escritorio (1920x600px recomendado)'
            ],
            [
                'key' => 'about_banner_image_mobile',
                'value' => '',
                'type' => 'image',
                'group' => 'about',
                'label' => 'Imagen de banner para móvil',
                'description' => 'Imagen que aparece en la parte superior de la página Acerca de en dispositivos móviles (800x400px recomendado)'
            ],
            [
                'key' => 'about_mission_title',
                'value' => 'Nuestra Misión',
                'type' => 'text',
                'group' => 'about',
                'label' => 'Título de la sección misión',
                'description' => 'Título principal de la sección de misión'
            ],
            [
                'key' => 'about_mission_description',
                'value' => 'En ByMelodia, creemos que la música es el lenguaje universal que conecta corazones y mentes. Nuestra misión es descubrir, promover y compartir la música que define a una generación, creando un espacio donde los artistas emergentes y establecidos puedan encontrar su audiencia perfecta.',
                'type' => 'textarea',
                'group' => 'about',
                'label' => 'Descripción de la misión',
                'description' => 'Texto descriptivo de la misión de la empresa'
            ],
            [
                'key' => 'about_values_title',
                'value' => 'Nuestros Valores',
                'type' => 'text',
                'group' => 'about',
                'label' => 'Título de la sección valores',
                'description' => 'Título de la sección de valores'
            ],
            [
                'key' => 'about_value_1_title',
                'value' => 'Autenticidad',
                'type' => 'text',
                'group' => 'about',
                'label' => 'Título del primer valor',
                'description' => 'Título del primer valor de la empresa'
            ],
            [
                'key' => 'about_value_1_description',
                'value' => 'Promovemos música genuina que refleje la verdadera esencia de los artistas.',
                'type' => 'textarea',
                'group' => 'about',
                'label' => 'Descripción del primer valor',
                'description' => 'Descripción del primer valor de la empresa'
            ],
            [
                'key' => 'about_value_2_title',
                'value' => 'Innovación',
                'type' => 'text',
                'group' => 'about',
                'label' => 'Título del segundo valor',
                'description' => 'Título del segundo valor de la empresa'
            ],
            [
                'key' => 'about_value_2_description',
                'value' => 'Utilizamos tecnología de vanguardia para crear experiencias musicales únicas.',
                'type' => 'textarea',
                'group' => 'about',
                'label' => 'Descripción del segundo valor',
                'description' => 'Descripción del segundo valor de la empresa'
            ],
            [
                'key' => 'about_value_3_title',
                'value' => 'Comunidad',
                'type' => 'text',
                'group' => 'about',
                'label' => 'Título del tercer valor',
                'description' => 'Título del tercer valor de la empresa'
            ],
            [
                'key' => 'about_value_3_description',
                'value' => 'Construimos puentes entre artistas y audiencias, creando una comunidad vibrante.',
                'type' => 'textarea',
                'group' => 'about',
                'label' => 'Descripción del tercer valor',
                'description' => 'Descripción del tercer valor de la empresa'
            ],
            [
                'key' => 'about_cta_title',
                'value' => '¿Listo para ser parte de nuestra historia?',
                'type' => 'text',
                'group' => 'about',
                'label' => 'Título del llamado a la acción',
                'description' => 'Título del llamado a la acción al final de la página'
            ],
            [
                'key' => 'about_cta_description',
                'value' => 'Únete a nuestra comunidad y descubre la música que está definiendo el futuro.',
                'type' => 'textarea',
                'group' => 'about',
                'label' => 'Descripción del llamado a la acción',
                'description' => 'Descripción del llamado a la acción al final de la página'
            ],
            [
                'key' => 'about_cta_button_text',
                'value' => 'Explorar Música',
                'type' => 'text',
                'group' => 'about',
                'label' => 'Texto del botón de llamado a la acción',
                'description' => 'Texto del botón en el llamado a la acción'
            ]
        ];

        foreach ($aboutSettings as $setting) {
            \App\Models\SiteSetting::create($setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\SiteSetting::whereIn('key', [
            'about_banner_image_desktop',
            'about_banner_image_mobile',
            'about_mission_title',
            'about_mission_description',
            'about_values_title',
            'about_value_1_title',
            'about_value_1_description',
            'about_value_2_title',
            'about_value_2_description',
            'about_value_3_title',
            'about_value_3_description',
            'about_cta_title',
            'about_cta_description',
            'about_cta_button_text'
        ])->delete();
    }
};
