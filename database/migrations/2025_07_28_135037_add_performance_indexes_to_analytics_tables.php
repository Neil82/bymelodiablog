<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add composite indexes for better query performance
        
        // User Sessions optimizations
        Schema::table('user_sessions', function (Blueprint $table) {
            // For analytics queries filtering by date and grouping by country/device
            $table->index(['started_at', 'country_code'], 'idx_sessions_date_country');
            $table->index(['started_at', 'device_type'], 'idx_sessions_date_device');
            $table->index(['started_at', 'is_bot'], 'idx_sessions_date_bot');
            
            // For detecting returning visitors
            $table->index(['ip_address', 'started_at'], 'idx_sessions_ip_date');
            
            // For session duration analysis
            $table->index(['total_duration', 'started_at'], 'idx_sessions_duration_date');
        });

        // Tracking Events optimizations
        Schema::table('tracking_events', function (Blueprint $table) {
            // For post analytics queries
            $table->index(['post_id', 'event_type', 'event_time'], 'idx_events_post_type_time');
            
            // For page view analytics
            $table->index(['event_type', 'event_time', 'url'], 'idx_events_type_time_url');
            
            // For scroll tracking
            $table->index(['event_type', 'scroll_depth', 'event_time'], 'idx_events_scroll_time');
            
            // For user session events lookup
            $table->index(['user_session_id', 'event_type'], 'idx_events_session_type');
        });

        // Post Analytics optimizations
        Schema::table('post_analytics', function (Blueprint $table) {
            // For trending posts queries
            $table->index(['date', 'unique_views'], 'idx_post_analytics_date_views');
            $table->index(['date', 'avg_time_on_page'], 'idx_post_analytics_date_time');
            
            // For post performance over time
            $table->index(['post_id', 'date', 'unique_views'], 'idx_post_analytics_post_date_views');
        });

        // Visitor Analytics optimizations
        Schema::table('visitor_analytics', function (Blueprint $table) {
            // For dashboard queries
            $table->index(['date', 'unique_visitors'], 'idx_visitor_analytics_date_visitors');
        });

        // Post Translations optimizations
        Schema::table('post_translations', function (Blueprint $table) {
            // For multilingual content lookup
            $table->index(['language_id', 'post_id'], 'idx_post_translations_lang_post');
            $table->index(['slug', 'language_id'], 'idx_post_translations_slug_lang');
        });

        // Category Translations optimizations  
        Schema::table('category_translations', function (Blueprint $table) {
            // For multilingual category lookup
            $table->index(['language_id', 'category_id'], 'idx_category_translations_lang_cat');
            $table->index(['slug', 'language_id'], 'idx_category_translations_slug_lang');
        });

        // Languages optimization
        Schema::table('languages', function (Blueprint $table) {
            // For active language queries
            $table->index(['is_active', 'sort_order'], 'idx_languages_active_order');
        });

        // Posts table additional indexes for analytics
        Schema::table('posts', function (Blueprint $table) {
            // For published posts with analytics
            $table->index(['status', 'published_at', 'views'], 'idx_posts_status_date_views');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop all the indexes we created
        Schema::table('user_sessions', function (Blueprint $table) {
            $table->dropIndex('idx_sessions_date_country');
            $table->dropIndex('idx_sessions_date_device');  
            $table->dropIndex('idx_sessions_date_bot');
            $table->dropIndex('idx_sessions_ip_date');
            $table->dropIndex('idx_sessions_duration_date');
        });

        Schema::table('tracking_events', function (Blueprint $table) {
            $table->dropIndex('idx_events_post_type_time');
            $table->dropIndex('idx_events_type_time_url');
            $table->dropIndex('idx_events_scroll_time');
            $table->dropIndex('idx_events_session_type');
        });

        Schema::table('post_analytics', function (Blueprint $table) {
            $table->dropIndex('idx_post_analytics_date_views');
            $table->dropIndex('idx_post_analytics_date_time');
            $table->dropIndex('idx_post_analytics_post_date_views');
        });

        Schema::table('visitor_analytics', function (Blueprint $table) {
            $table->dropIndex('idx_visitor_analytics_date_visitors');
        });

        Schema::table('post_translations', function (Blueprint $table) {
            $table->dropIndex('idx_post_translations_lang_post');
            $table->dropIndex('idx_post_translations_slug_lang');
        });

        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropIndex('idx_category_translations_lang_cat');
            $table->dropIndex('idx_category_translations_slug_lang');
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->dropIndex('idx_languages_active_order');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('idx_posts_status_date_views');
        });
    }
};
