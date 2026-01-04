<?php

namespace Database\Seeders;

use App\Models\Flag;
use Illuminate\Database\Seeder;

class FlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Flag::create([
            'slug' => 'medical-advice',
            'name' => 'Medical Advice',
            'description' => 'This content contains medical information and should not be considered professional medical advice. Always consult with a qualified healthcare provider.',
            'color' => 'red',
            'icon' => '⚕️',
            'order' => 1,
        ]);

        Flag::create([
            'slug' => 'legal-advice',
            'name' => 'Legal Advice',
            'description' => 'This content contains legal information and should not be considered professional legal advice. Always consult with a qualified attorney.',
            'color' => 'red',
            'icon' => '⚖️',
            'order' => 2,
        ]);

        Flag::create([
            'slug' => 'outdated',
            'name' => 'Outdated Content',
            'description' => 'This content may be outdated or apply to older versions. Please verify with current documentation.',
            'color' => 'yellow',
            'icon' => '⚠️',
            'order' => 3,
        ]);

        Flag::create([
            'slug' => 'deprecated',
            'name' => 'Deprecated',
            'description' => 'This guide covers deprecated features or methods. Consider using newer alternatives.',
            'color' => 'orange',
            'icon' => '🔴',
            'order' => 4,
        ]);

        Flag::create([
            'slug' => 'experimental',
            'name' => 'Experimental',
            'description' => 'This content covers experimental features that may change or be removed in future versions.',
            'color' => 'purple',
            'icon' => '🧪',
            'order' => 5,
        ]);

        Flag::create([
            'slug' => 'security-sensitive',
            'name' => 'Security Sensitive',
            'description' => 'This content involves security-sensitive operations. Ensure you understand the implications before proceeding.',
            'color' => 'red',
            'icon' => '🔒',
            'order' => 6,
        ]);

        Flag::create([
            'slug' => 'requires-admin',
            'name' => 'Requires Administrator Access',
            'description' => 'This guide requires administrator or root access to complete the described operations.',
            'color' => 'blue',
            'icon' => '🔑',
            'order' => 7,
        ]);
    }
}
