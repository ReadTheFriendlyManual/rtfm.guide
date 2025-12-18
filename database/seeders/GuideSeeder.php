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
            Guide::updateOrCreate(
                ['slug' => 'how-to-restart-nginx'],
                [
                'user_id' => $user->id,
                'category_id' => $nginxCategory->id,

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

## Platform-specific Instructions

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
```

## Configuration Files

After restarting, check your configuration files:

```bash
# Test configuration
sudo nginx -t

# View configuration
sudo nginx -T
```

## Troubleshooting

If Nginx fails to restart:

1. Check the error logs:
   ```bash
   sudo tail -f /var/log/nginx/error.log
   ```

2. Verify port availability:
   ```bash
   sudo netstat -tlnp | grep :80
   ```

3. Check for syntax errors in config files

## Additional Resources

- [Nginx Documentation](https://nginx.org/en/docs/)
- [DigitalOcean Nginx Guides](https://www.digitalocean.com/community/tags/nginx)',
                'difficulty' => 'beginner',
                'estimated_minutes' => 2,
                'os_tags' => ['linux', 'ubuntu', 'centos'],
                'status' => 'published',
                'published_at' => now(),
                'view_count' => 150,
                ]);
        }

        if ($deploymentCategory) {
            Guide::updateOrCreate(
                ['slug' => 'zero-downtime-deployment'],
                [
                'user_id' => $user->id,
                'category_id' => $deploymentCategory->id,
                'title' => 'Zero Downtime Laravel Deployment',
                'tldr' => 'Use deployment strategies that keep your app running during updates',
                'content' => '# Zero Downtime Laravel Deployment

## Overview
Deploy Laravel applications without any downtime using proper deployment strategies and zero-downtime techniques.

## Prerequisites

- Laravel application ready for production
- Server with SSH access
- Git repository
- Web server (Nginx/Apache) configured

## Method 1: Using Laravel Deployer

### Installation
```bash
composer require lorisleiva/laravel-deployer --dev
```

### Setup
```bash
php artisan deploy:init
```

### Deploy
```bash
dep deploy
```

## Method 2: Manual Zero-Downtime Deployment

### Step 1: Prepare Deployment Directory
```bash
# Create releases directory
mkdir -p /var/www/html/releases
cd /var/www/html

# Clone to new release directory
RELEASE_DIR="releases/$(date +%Y%m%d_%H%M%S)"
git clone --depth 1 https://github.com/your/repo.git $RELEASE_DIR
cd $RELEASE_DIR
```

### Step 2: Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm run production  # If using frontend assets
```

### Step 3: Environment Setup
```bash
cp .env.example .env
# Configure production environment variables
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 4: Database Migrations
```bash
php artisan migrate --force
```

### Step 5: Storage & Permissions
```bash
php artisan storage:link
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Step 6: Zero-Downtime Switch
```bash
# Create symbolic link atomically
ln -sfn $RELEASE_DIR current_new
mv -Tf current_new current

# Restart queue workers if needed
php artisan queue:restart
```

### Step 7: Cleanup
```bash
# Keep only last 5 releases
cd releases
ls -t | tail -n +6 | xargs rm -rf
```

## Testing Zero-Downtime Deployment

1. **Monitor logs during deployment**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check application health**:
   ```bash
   curl -I https://yourdomain.com/api/health
   ```

3. **Verify database connections**:
   ```bash
   php artisan tinker --execute="DB::connection()->getPdo()"
   ```

## Common Issues & Solutions

### Issue: File permissions after deployment
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
```

### Issue: Old cached config
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Issue: Queue workers not restarting
```bash
# Force restart queues
php artisan queue:restart
# Or use process manager (Supervisor)
sudo supervisorctl restart laravel-worker:*
```

## Advanced Techniques

### Blue-Green Deployment
- Maintain two identical environments
- Route traffic between them
- Roll back instantly if issues occur

### Canary Deployments
- Route small percentage of traffic to new version
- Gradually increase traffic as confidence grows
- Roll back quickly if metrics degrade

## Tools & Resources

- [Laravel Deployer](https://github.com/lorisleiva/laravel-deployer)
- [Envoy](https://laravel.com/docs/envoy) - Laravel\'s deployment tool
- [Deployer](https://deployer.org/) - PHP deployment tool
- [Capistrano](https://capistranorb.com/) - Ruby deployment tool',
                'difficulty' => 'intermediate',
                'estimated_minutes' => 15,
                'os_tags' => ['linux'],
                'status' => 'published',
                'published_at' => now(),
                'view_count' => 89,
            ]);
        }

        if ($gitCategory) {
            Guide::updateOrCreate(
                ['slug' => 'git-revert-commit'],
                [
                'user_id' => $user->id,
                'category_id' => $gitCategory->id,
                'title' => 'How to Revert a Git Commit',
                'tldr' => 'Use git revert to undo commits while preserving history',
                'content' => '# How to Revert a Git Commit

## Overview
Learn how to safely undo Git commits using `git revert` instead of dangerous `git reset` operations, especially when working with shared branches.

## The Safe Way: git revert

**Never use `git reset` on commits that have been pushed to shared branches!**

### Basic Usage
```bash
# Revert the last commit
git revert HEAD

# Revert a specific commit by hash
git revert a1b2c3d4

# Revert multiple commits
git revert HEAD~3..HEAD

# Revert a range of commits
git revert <commit-hash1>..<commit-hash2>
```

## Why git revert is Safe

- **Preserves history**: All original commits remain in the log
- **Safe for collaboration**: Won\'t cause conflicts for other developers
- **Creates new commits**: The revert is a new commit that undoes changes
- **Maintains integrity**: No history rewriting

## Common Scenarios

### Scenario 1: Undo Last Commit (Not Pushed)
```bash
# Option A: Keep changes staged (safest)
git reset --soft HEAD~1

# Option B: Keep changes unstaged
git reset --mixed HEAD~1

# Option C: Discard changes completely (dangerous!)
git reset --hard HEAD~1
```

### Scenario 2: Undo Last Commit (Already Pushed)
```bash
# Safe revert for shared branches
git revert HEAD
git push origin main
```

### Scenario 3: Undo Multiple Recent Commits
```bash
# Revert last 3 commits
git revert HEAD~3..HEAD

# Or revert individually (allows editing each revert message)
git revert HEAD~2
git revert HEAD~1
git revert HEAD
```

### Scenario 4: Undo a Specific Commit in History
```bash
# Find the commit hash
git log --oneline

# Revert that specific commit
git revert a1b2c3d4
```

## Interactive Revert

For more control over the revert process:
```bash
# Interactive mode (edit revert message)
git revert -e HEAD

# No commit message editing
git revert --no-edit HEAD
```

## Handling Merge Conflicts

If `git revert` encounters conflicts:
```bash
# Fix the conflicts in your editor
# Then continue the revert
git add <resolved-files>
git revert --continue

# Or abort if it\'s too messy
git revert --abort
```

## Checking Revert Status

```bash
# See what\'s being reverted
git status

# View the revert in progress
git diff --cached
```

## Best Practices

### When to Use git reset (Private Branches Only)
- **Local commits**: Not yet pushed
- **Feature branches**: Before merging to main
- **Personal repos**: Where history rewriting is acceptable

### Always Use git revert For
- **Shared branches**: main, develop, release branches
- **Team projects**: Where others depend on history
- **Public repos**: Where history integrity matters

## Examples

### Example 1: Simple Feature Revert
```bash
# Feature was committed but broke something
git revert abc123def
git push origin main

# Result: Feature is undone, history preserved
```

### Example 2: Hotfix Rollback
```bash
# Hotfix introduced a bug
git revert HEAD
git push origin production

# Production is immediately fixed
```

## Troubleshooting

### Issue: Cannot revert error
**Cause**: Trying to revert a merge commit
**Solution**: Use --mainline flag
```bash
git revert -m 1 <merge-commit-hash>
```

### Issue: Revert creates conflicts
**Cause**: Changes conflict with current state
**Solution**: Resolve conflicts manually, then `git revert --continue`

### Issue: Want to undo the revert itself
**Solution**: Revert the revert commit
```bash
git revert <revert-commit-hash>
```

## Related Commands

- `git reset`: Rewind history (use carefully)
- `git commit --amend`: Modify last commit
- `git reflog`: View all history changes
- `git cherry-pick`: Apply specific commits

## Resources

- [Git Revert Documentation](https://git-scm.com/docs/git-revert)
- [Understanding Git Reset](https://git-scm.com/docs/git-reset)
- [Rewriting History vs Safe Operations](https://www.atlassian.com/git/tutorials/rewriting-history)',
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
