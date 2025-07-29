<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('confirmation_token')->unique()->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->string('language_code', 5)->default('es');
            $table->boolean('is_active')->default(true);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('unsubscribe_token')->unique()->nullable();
            $table->json('preferences')->nullable(); // For future: categories, frequency, etc.
            $table->timestamps();
            
            $table->index('email');
            $table->index('confirmed_at');
            $table->index('is_active');
            $table->index('language_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};