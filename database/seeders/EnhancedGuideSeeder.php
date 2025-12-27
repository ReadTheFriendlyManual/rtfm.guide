<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnhancedGuideSeeder extends Seeder
{
    public function run(): void
    {
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

        $nginxCategory = Category::where('slug', 'nginx')->first();

        if ($nginxCategory) {
            Guide::updateOrCreate(
                ['slug' => 'how-to-restart-nginx'],
                [
                    'user_id' => $user->id,
                    'category_id' => $nginxCategory->id,
                    'title' => 'How to Restart Nginx',
                    'difficulty' => 'beginner',
                    'estimated_minutes' => 5,
                    'os_tags' => ['linux', 'macos'],
                    'status' => 'published',
                    'visibility' => 'public',
                    'published_at' => now(),

                    // SFW Version
                    'tldr' => 'Use `systemctl restart nginx` to restart the Nginx web server on Linux systems.',
                    'content' => $this->getNginxContentSFW(),

                    // NSFW Version
                    'tldr_nsfw' => 'Just run `systemctl restart nginx` and stop overthinking it.',
                    'content_nsfw' => $this->getNginxContentNSFW(),
                ]
            );
        }
    }

    private function getNginxContentSFW(): string
    {
        return <<<'MARKDOWN'
## Prerequisites

Before restarting Nginx, ensure you have:

- Root or sudo access to the server
- Nginx installed and configured
- Basic knowledge of the command line

## Quick Answer

```bash
sudo systemctl restart nginx
```

## Detailed Steps

### 1. Check Current Status

Before restarting, check if Nginx is currently running:

```bash
sudo systemctl status nginx
```

You should see output indicating whether Nginx is active (running) or inactive (stopped).

### 2. Test Your Configuration

**Always test your configuration before restarting** to avoid downtime:

```bash
sudo nginx -t
```

If the configuration test passes, you'll see:

```
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

> **Warning:** If the test fails, fix the configuration errors before proceeding.

### 3. Restart Nginx

Once your configuration is valid, restart Nginx:

```bash
sudo systemctl restart nginx
```

### 4. Verify the Restart

Confirm that Nginx restarted successfully:

```bash
sudo systemctl status nginx
```

Look for the "active (running)" status in the output.

## Alternative Methods

### Using the reload Command

If you only changed configuration files and want to avoid dropping connections:

```bash
sudo systemctl reload nginx
```

**Reload vs Restart:**

| Command | Use Case | Downtime |
|---------|----------|----------|
| `reload` | Configuration changes only | None |
| `restart` | Full service restart | Brief (1-2s) |

### Using Service Command (Legacy)

On older systems without systemd:

```bash
sudo service nginx restart
```

### Using Nginx Binary Directly

```bash
sudo nginx -s reload  # Graceful reload
sudo nginx -s stop    # Stop
sudo nginx            # Start
```

## Common Issues

### Permission Denied

**Problem:** You see "Permission denied" when running the command.

**Solution:** Ensure you're using `sudo`:

```bash
sudo systemctl restart nginx
```

### Service Not Found

**Problem:** Error message "Unit nginx.service could not be found"

**Solution:** Check if Nginx is installed:

```bash
nginx -v
```

If not installed, install it first:

```bash
# Ubuntu/Debian
sudo apt update && sudo apt install nginx

# CentOS/RHEL
sudo yum install nginx
```

### Configuration Test Failed

**Problem:** `nginx -t` shows configuration errors.

**Solution:** Check the error message and fix the configuration file:

```bash
sudo nginx -t  # See the error
sudo nano /etc/nginx/nginx.conf  # Edit the config
```

Common configuration errors:
- Missing semicolons
- Incorrect file paths
- Syntax errors in server blocks

### Port Already in Use

**Problem:** Nginx fails to start because port 80/443 is in use.

**Solution:** Find what's using the port:

```bash
sudo lsof -i :80
sudo lsof -i :443
```

Then either stop the conflicting service or change Nginx's port.

## Platform-Specific Notes

### Ubuntu/Debian

```bash
# Restart
sudo systemctl restart nginx

# Enable on boot
sudo systemctl enable nginx
```

### CentOS/RHEL 8+

```bash
# Restart
sudo systemctl restart nginx

# Enable on boot
sudo systemctl enable nginx
```

### macOS (Homebrew)

```bash
# Restart
brew services restart nginx

# Or manually
nginx -s stop && nginx
```

## Best Practices

1. **Always test configuration first:** Run `nginx -t` before restarting
2. **Use reload when possible:** It's graceful and doesn't drop connections
3. **Check logs after restart:** Monitor `/var/log/nginx/error.log` for issues
4. **Backup configs before changes:** Keep copies of working configurations

## Monitoring After Restart

### Check Error Logs

```bash
sudo tail -f /var/log/nginx/error.log
```

### Check Access Logs

```bash
sudo tail -f /var/log/nginx/access.log
```

### Test HTTP Response

```bash
curl -I http://localhost
```

Expected response should start with `HTTP/1.1 200 OK` or your configured status.

## Related Commands

```bash
# Start Nginx
sudo systemctl start nginx

# Stop Nginx
sudo systemctl stop nginx

# Check if enabled on boot
sudo systemctl is-enabled nginx

# View full logs
sudo journalctl -u nginx
```

## Additional Resources

- [Official Nginx Documentation](https://nginx.org/en/docs/)
- [Nginx Configuration Guide](https://nginx.org/en/docs/beginners_guide.html)
MARKDOWN;
    }

    private function getNginxContentNSFW(): string
    {
        return <<<'MARKDOWN'
## Prerequisites

- Root/sudo access
- Nginx actually installed (obviously)
- The ability to read error messages

## Quick Answer

```bash
sudo systemctl restart nginx
```

That's it. You're done. Go grab a coffee.

## Detailed Steps (If You Really Need Hand-Holding)

### 1. Check if It's Even Running

```bash
sudo systemctl status nginx
```

### 2. Test Your Damn Configuration First

Seriously, **TEST BEFORE YOU RESTART**:

```bash
sudo nginx -t
```

If this fails, you f*cked up your config. Fix it first, or you'll have downtime.

### 3. Actually Restart the Thing

```bash
sudo systemctl restart nginx
```

### 4. Make Sure You Didn't Break Anything

```bash
sudo systemctl status nginx
```

See "active (running)"? Good. You did it right.

## Reload vs Restart: Know the F*cking Difference

| Command | What It Does | Downtime |
|---------|--------------|----------|
| `reload` | Graceful restart, keeps connections | Zero |
| `restart` | Hard restart, drops everything | 1-2 seconds |

For config changes, use reload:

```bash
sudo systemctl reload nginx
```

## Common Ways to Screw This Up

### "Permission Denied"

You forgot `sudo`. Read the error message next time.

```bash
sudo systemctl restart nginx  # ← Notice the sudo
```

### "Service Not Found"

Nginx isn't installed. How did you even get this far?

```bash
nginx -v  # Check if it exists
```

Not there? Install it:

```bash
# Ubuntu/Debian
sudo apt install nginx

# CentOS/RHEL
sudo yum install nginx
```

### Config Test Failed

This means you have syntax errors. Read the output - it literally tells you what's wrong:

```bash
sudo nginx -t
```

Common f*ckups:
- Missing semicolons (every directive needs one)
- Typos in file paths
- Copy-pasting config without reading it

### Port Already in Use

Something else is squatting on port 80/443. Find the bastard:

```bash
sudo lsof -i :80
sudo lsof -i :443
```

Kill it or change Nginx's port. Your choice.

## Platform-Specific (It's All Pretty Much the Same)

### Ubuntu/Debian

```bash
sudo systemctl restart nginx
```

### CentOS/RHEL

```bash
sudo systemctl restart nginx
```

### macOS

```bash
brew services restart nginx
```

## Actually Useful Best Practices

1. **Test first, restart second** - `nginx -t` is your friend
2. **Use reload for config changes** - No downtime
3. **Check the logs** - `/var/log/nginx/error.log` will tell you what went wrong
4. **Backup working configs** - Future you will thank present you

## Verify It Actually Works

```bash
# Check logs for errors
sudo tail -f /var/log/nginx/error.log

# Test HTTP response
curl -I http://localhost
```

If you see `HTTP/1.1 200 OK` or your expected status, you're golden.

## Stop, Start, and Other Obvious Commands

```bash
# Start
sudo systemctl start nginx

# Stop
sudo systemctl stop nginx

# Reload (graceful restart)
sudo systemctl reload nginx

# View logs
sudo journalctl -u nginx
```

## TL;DR

1. Test config: `sudo nginx -t`
2. Restart: `sudo systemctl restart nginx`
3. Verify: `sudo systemctl status nginx`

If that doesn't work, read the error messages. They're there for a reason.
MARKDOWN;
    }
}
