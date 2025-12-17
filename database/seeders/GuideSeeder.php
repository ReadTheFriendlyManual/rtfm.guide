<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample user
        $user = User::firstOrCreate(
            ['email' => 'demo@rtfm.guide'],
            [
                'name' => 'RTFM Demo User',
                'password' => bcrypt('password'),
                'github_username' => 'rtfm-demo',
                'trust_level' => 'trusted',
                'reputation_points' => 100,
            ]
        );

        // Get some categories
        $nginxCategory = Category::where('slug', 'nginx')->first();
        $deploymentCategory = Category::where('slug', 'deployment')->first();
        $gitCategory = Category::where('slug', 'git')->first();

        if ($nginxCategory) {
            Guide::create([
                'user_id' => $user->id,
                'category_id' => $nginxCategory->id,
                'slug' => 'how-to-restart-nginx',
                'title' => 'How to Restart Nginx',
                'tldr' => 'Use systemctl restart nginx to restart Nginx service',
                'content' => '# How to Restart Nginx

## Quick Answer
```bash
sudo systemctl restart nginx
```

## Step-by-Step Instructions

1. **Check Nginx status first**:
   ```bash
   sudo systemctl status nginx
   ```

2. **Restart the service**:
   ```bash
   sudo systemctl restart nginx
   ```

3. **Verify it\'s running**:
   ```bash
   sudo systemctl status nginx
   ```

## Common Issues

- **Permission denied**: Make sure you\'re running as root or with sudo
- **Service not found**: Nginx might not be installed as a service

## Platform-specific instructions

### Ubuntu/Debian
```bash
sudo systemctl restart nginx
```

### CentOS/RHEL
```bash
sudo systemctl restart nginx
```

### Using service command (older systems)
```bash
sudo service nginx restart
```',
                'difficulty' => 'beginner',
                'estimated_minutes' => 2,
                'os_tags' => ['linux', 'ubuntu', 'centos'],
                'status' => 'published',
                'published_at' => now(),
                'view_count' => 150,
            ]);
        }

        if ($deploymentCategory) {
            Guide::create([
                'user_id' => $user->id,
                'category_id' => $deploymentCategory->id,
                'slug' => 'zero-downtime-deployment',
                'title' => 'Zero Downtime Laravel Deployment',
                'tldr' => 'Use deployment strategies that keep your app running during updates',
                'content' => '# Zero Downtime Laravel Deployment

## Overview
Deploy Laravel applications without any downtime using proper deployment strategies.

## Using Laravel Deployer

1. **Install Deployer**:
   ```bash
   composer require lorisleiva/laravel-deployer
   ```

2. **Initialize deployment**:
   ```bash
   php artisan deploy:init
   ```

3. **Deploy**:
   ```bash
   dep deploy
   ```

## Manual Deployment Strategy

1. **Deploy to new directory**
2. **Run migrations**
3. **Update symbolic link**
4. **Clean up old deployment**

This ensures users never see errors during deployment.',
                'difficulty' => 'intermediate',
                'estimated_minutes' => 15,
                'os_tags' => ['linux'],
                'status' => 'published',
                'published_at' => now(),
                'view_count' => 89,
            ]);
        }

        if ($gitCategory) {
            Guide::create([
                'user_id' => $user->id,
                'category_id' => $gitCategory->id,
                'slug' => 'git-revert-commit',
                'title' => 'How to Revert a Git Commit',
                'tldr' => 'Use git revert to undo commits while preserving history',
                'content' => '# How to Revert a Git Commit

## The Safe Way: git revert

**Don\'t use `git reset` if the commit is already pushed!**

```bash
# Revert the last commit
git revert HEAD

# Revert a specific commit
git revert <commit-hash>

# Revert multiple commits
git revert <commit-hash1> <commit-hash2>
```

## Why git revert?

- **Preserves history**: All commits remain in the log
- **Safe for shared branches**: Won\'t cause conflicts for others
- **Creates new commit**: The revert is a new commit that undoes changes

## Common Scenarios

### Undo last commit (not pushed)
```bash
git reset --soft HEAD~1  # Keep changes staged
git reset --hard HEAD~1  # Discard changes completely
```

### Undo last commit (already pushed)
```bash
git revert HEAD
git push
```',
                'difficulty' => 'beginner',
                'estimated_minutes' => 5,
                'os_tags' => ['linux', 'macos', 'windows'],
                'status' => 'published',
                'published_at' => now(),
                'view_count' => 203,
            ]);
        }
    }
}
