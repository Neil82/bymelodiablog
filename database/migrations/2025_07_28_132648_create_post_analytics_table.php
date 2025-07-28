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
        Schema::create('post_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->date('date'); // analytics by day
            $table->integer('unique_views')->default(0);
            $table->integer('total_views')->default(0);
            $table->integer('avg_time_on_page')->default(0); // seconds
            $table->integer('total_time_on_page')->default(0); // seconds
            $table->integer('bounce_rate')->default(0); // percentage
            $table->integer('scroll_depth_avg')->default(0); // percentage
            $table->integer('social_shares')->default(0);
            $table->integer('comments_count')->default(0);
            $table->json('traffic_sources')->nullable(); // direct, search, social, etc
            $table->json('device_breakdown')->nullable(); // mobile, desktop, tablet
            $table->json('country_breakdown')->nullable();
            $table->json('referrer_breakdown')->nullable();
            $table->timestamps();
            
            $table->unique(['post_id', 'date']);
            $table->index(['date', 'unique_views']);
            $table->index(['post_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_analytics');
    }
};
