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
            $table->string('oauth_provider')->nullable()->after('email_verified_at');
            $table->string('oauth_id')->nullable()->after('oauth_provider');
            $table->string('avatar')->nullable()->after('password');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('github_username')->nullable()->after('bio');
            $table->string('gitlab_username')->nullable()->after('github_username');
            $table->integer('reputation_points')->default(0)->after('gitlab_username');
            $table->enum('trust_level', ['new', 'member', 'trusted', 'moderator', 'admin'])->default('new')->after('reputation_points');
            $table->string('preferred_locale', 5)->default('en')->after('trust_level');
            $table->boolean('newsletter_subscribed')->default(false)->after('preferred_locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'oauth_provider',
                'oauth_id',
                'avatar',
                'bio',
                'github_username',
                'gitlab_username',
                'reputation_points',
                'trust_level',
                'preferred_locale',
                'newsletter_subscribed',
            ]);
        });
    }
};
