<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('type');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $now = now();
        DB::table('settings')->insert([
            [
                'key' => 'registration_enabled',
                'type' => 'boolean',
                'value' => '0',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'login_enabled',
                'type' => 'boolean',
                'value' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'registration_disabled_message',
                'type' => 'text',
                'value' => 'Registration is temporarily disabled while we resolve an email deliverability issue. We expect it to be resolved within a few hours.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'login_disabled_message',
                'type' => 'text',
                'value' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
