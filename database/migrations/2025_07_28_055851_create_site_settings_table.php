<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, boolean, json
            $table->string('group')->default('general'); // general, banner, ads, seo
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            [
                'key' => 'banner_title',
                'value' => 'ByMelodia',
                'type' => 'text',
                'group' => 'banner',
                'label' => 'Título del Banner',
                'description' => 'Título principal que aparece en el banner de inicio',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'banner_subtitle',
                'value' => 'El espacio donde la cultura juvenil cobra vida. Tendencias, música, lifestyle y todo lo que te mueve.',
                'type' => 'textarea',
                'group' => 'banner',
                'label' => 'Subtítulo del Banner',
                'description' => 'Descripción que aparece debajo del título',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'banner_button_text',
                'value' => 'Explorar contenido',
                'type' => 'text',
                'group' => 'banner',
                'label' => 'Texto del Botón Principal',
                'description' => 'Texto del botón principal del banner',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'banner_button_url',
                'value' => '/blog',
                'type' => 'text',
                'group' => 'banner',
                'label' => 'URL del Botón Principal',
                'description' => 'Enlace del botón principal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'banner_image_desktop',
                'value' => null,
                'type' => 'image',
                'group' => 'banner',
                'label' => 'Imagen de Fondo (Desktop)',
                'description' => 'Imagen de fondo del banner para desktop (1920x800px recomendado)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'banner_image_mobile',
                'value' => null,
                'type' => 'image',
                'group' => 'banner',
                'label' => 'Imagen de Fondo (Mobile)',
                'description' => 'Imagen de fondo del banner para móvil (800x600px recomendado)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'adsense_client_id',
                'value' => null,
                'type' => 'text',
                'group' => 'ads',
                'label' => 'AdSense Client ID',
                'description' => 'Tu ID de cliente de Google AdSense (ca-pub-xxxxxxxxxx)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'adsense_auto_ads',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'ads',
                'label' => 'Auto Ads Habilitado',
                'description' => 'Activar anuncios automáticos de AdSense',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'privacy_policy',
                'value' => '',
                'type' => 'textarea',
                'group' => 'legal',
                'label' => 'Política de Privacidad',
                'description' => 'Contenido de la política de privacidad',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'terms_of_service',
                'value' => '',
                'type' => 'textarea',
                'group' => 'legal',
                'label' => 'Términos de Servicio',
                'description' => 'Contenido de los términos de servicio',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};