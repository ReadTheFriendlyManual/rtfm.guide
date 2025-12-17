<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Server Administration
        $server = Category::create([
            'slug' => 'server',
            'name' => 'Server Administration',
            'description' => 'Web server setup, configuration, and management',
            'icon' => 'server',
            'order' => 1,
        ]);

        Category::create([
            'parent_id' => $server->id,
            'slug' => 'nginx',
            'name' => 'Nginx',
            'description' => 'Nginx web server configuration and optimization',
            'icon' => 'settings',
            'order' => 1,
        ]);

        Category::create([
            'parent_id' => $server->id,
            'slug' => 'apache',
            'name' => 'Apache',
            'description' => 'Apache HTTP server setup and management',
            'icon' => 'settings',
            'order' => 2,
        ]);

        Category::create([
            'parent_id' => $server->id,
            'slug' => 'mysql',
            'name' => 'MySQL',
            'description' => 'MySQL database administration and optimization',
            'icon' => 'database',
            'order' => 3,
        ]);

        // Laravel Development
        $laravel = Category::create([
            'slug' => 'laravel',
            'name' => 'Laravel',
            'description' => 'Laravel PHP framework guides and tutorials',
            'icon' => 'code',
            'order' => 2,
        ]);

        Category::create([
            'parent_id' => $laravel->id,
            'slug' => 'deployment',
            'name' => 'Deployment',
            'description' => 'Deploying Laravel applications to production',
            'icon' => 'rocket',
            'order' => 1,
        ]);

        Category::create([
            'parent_id' => $laravel->id,
            'slug' => 'authentication',
            'name' => 'Authentication',
            'description' => 'User authentication and authorization in Laravel',
            'icon' => 'shield',
            'order' => 2,
        ]);

        Category::create([
            'parent_id' => $laravel->id,
            'slug' => 'eloquent',
            'name' => 'Eloquent ORM',
            'description' => 'Database operations with Laravel Eloquent',
            'icon' => 'database',
            'order' => 3,
        ]);

        // Git & Version Control
        Category::create([
            'slug' => 'git',
            'name' => 'Git',
            'description' => 'Version control with Git and GitHub workflows',
            'icon' => 'git-branch',
            'order' => 3,
        ]);

        // Docker & Containerization
        Category::create([
            'slug' => 'docker',
            'name' => 'Docker',
            'description' => 'Containerization with Docker and orchestration',
            'icon' => 'container',
            'order' => 4,
        ]);

        // Command Line Tools
        Category::create([
            'slug' => 'cli',
            'name' => 'Command Line',
            'description' => 'Essential command line tools and techniques',
            'icon' => 'terminal',
            'order' => 5,
        ]);
    }
}
