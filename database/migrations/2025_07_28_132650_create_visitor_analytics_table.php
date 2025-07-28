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
        Schema::create('visitor_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // daily aggregated stats
            $table->integer('unique_visitors')->default(0);
            $table->integer('total_page_views')->default(0);
            $table->integer('new_visitors')->default(0);
            $table->integer('returning_visitors')->default(0);
            $table->integer('avg_session_duration')->default(0); // seconds
            $table->integer('bounce_rate')->default(0); // percentage
            $table->integer('avg_pages_per_session')->default(0);
            $table->json('top_pages')->nullable(); // most visited pages
            $table->json('top_referrers')->nullable();
            $table->json('top_countries')->nullable();
            $table->json('device_stats')->nullable(); // mobile vs desktop vs tablet
            $table->json('browser_stats')->nullable();
            $table->json('os_stats')->nullable();
            $table->json('traffic_sources')->nullable(); // organic, direct, social, etc
            $table->json('language_stats')->nullable();
            $table->timestamps();
            
            $table->unique('date');
            $table->index(['date', 'unique_visitors']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_analytics');
    }
};
