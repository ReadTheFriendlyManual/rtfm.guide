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
        Schema::table('users', function (Blueprint $table) {
            $table->string('twitter_username')->nullable()->after('gitlab_username');
            $table->string('linkedin_username')->nullable()->after('twitter_username');
            $table->string('website_url')->nullable()->after('linkedin_username');
            $table->text('featured_bio')->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['twitter_username', 'linkedin_username', 'website_url', 'featured_bio']);
        });
    }
};
