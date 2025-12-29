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
                    'tldr_nsfw' => 'Just run `systemctl restart nginx` you fucking idiot. It\'s not rocket science.',
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

- Root/sudo access (if you don't have this, what the fuck are you even doing here?)
- Nginx actually fucking installed
- Two brain cells to rub together
- The ability to read fucking error messages instead of panic-posting on Stack Overflow

## Quick Answer

```bash
sudo systemctl restart nginx
```

That's it. You're done. Fuck off and do something productive now.

## Detailed Steps (For Those Who Need Their Hand Held Like a Goddamn Toddler)

### 1. Check if the Bastard is Even Running

```bash
sudo systemctl status nginx
```

If it says "active (running)" - congrats, it's alive. If not, it's dead. Pretty fucking straightforward.

### 2. Test Your Shitty Configuration First

Listen up, dipshit: **TEST BEFORE YOU RESTART**. This is not optional.

```bash
sudo nginx -t
```

If this fails, you fucked up your config. Fix your broken-ass configuration file before proceeding, or you'll take your entire site down like the amateur you are.

Expected output when you haven't shit the bed:
```
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

### 3. Actually Restart the Fucking Thing

```bash
sudo systemctl restart nginx
```

Was that so hard? Jesus Christ.

### 4. Verify You Didn't Fuck Everything Up

```bash
sudo systemctl status nginx
```

See "active (running)"? Good job, you managed to execute a basic command. Want a fucking medal?

## Reload vs Restart: Learn the Fucking Difference or Pay the Price

| Command | What the Fuck It Does | Downtime |
|---------|------------------------|----------|
| `reload` | Graceful restart, keeps connections alive | Zero (you're welcome) |
| `restart` | Hard restart, drops all active connections like a motherfucker | 1-2 seconds of "oh shit" |

For simple config changes, use reload instead of being a savage:

```bash
sudo systemctl reload nginx
```

Your users will thank you for not kicking them off mid-session, asshole.

## Common Ways Idiots Fuck This Up

### "Permission Denied"

You forgot `sudo`, genius. The error message literally fucking told you this.

```bash
sudo systemctl restart nginx  # ← See that? That's sudo. Use it.
```

### "Service Not Found"

Nginx isn't installed, you absolute walnut. How the hell did you get this far without Nginx being installed?

```bash
nginx -v  # Does it exist? Let's find out!
```

Not there? Then install the damn thing:

```bash
# Ubuntu/Debian (for the Debian fanboys)
sudo apt install nginx

# CentOS/RHEL (for masochists)
sudo yum install nginx
```

### Config Test Failed

This means you have syntax errors. The output literally tells you what's wrong and where. **READ THE FUCKING ERROR MESSAGE.**

```bash
sudo nginx -t  # Run this. Read it. Fix it.
```

Common fuckups that make you look incompetent:
- Missing semicolons (every directive needs one, you dunce)
- Typos in file paths (learn to spell)
- Copy-pasting random shit from Stack Overflow without understanding it
- Not closing brackets like a goddamn amateur
- Trailing commas where they don't belong

### Port Already in Use

Something else is camping on port 80 or 443. Find the squatter:

```bash
sudo lsof -i :80
sudo lsof -i :443
```

Then kill that process or reconfigure Nginx to use a different port. Your circus, your monkeys.

### "It's Not Working" (The Vague Bullshit)

"It's not working" is not a fucking error message. Check the actual logs:

```bash
sudo tail -f /var/log/nginx/error.log
```

Read what it says. Google the specific error. Don't just throw your hands up and cry.

## Platform-Specific Bullshit (It's Basically All the Same, Relax)

### Ubuntu/Debian

```bash
sudo systemctl restart nginx
```

### CentOS/RHEL

```bash
sudo systemctl restart nginx
```

### macOS (For the Hipsters)

```bash
brew services restart nginx
```

Or if you're doing it manually like a masochist:

```bash
nginx -s stop && nginx
```

## Best Practices (That You'll Probably Ignore)

1. **Test first, restart second** - Run `nginx -t` before restarting. Every. Fucking. Time.
2. **Use reload for config changes** - Zero downtime is better than "oops, site's down"
3. **Check the goddamn logs** - `/var/log/nginx/error.log` is your best friend when shit goes sideways
4. **Backup configs before making changes** - Future you will want to kiss present you
5. **Don't edit config files while drunk** - Seriously, don't

## Verify This Shit Actually Works

```bash
# Watch for errors in real-time
sudo tail -f /var/log/nginx/error.log

# Test HTTP response
curl -I http://localhost
```

If you see `HTTP/1.1 200 OK`, you're golden. If not, you fucked something up. Back to the logs.

## Other Obvious Commands You Might Need

```bash
# Start Nginx (if it's stopped, duh)
sudo systemctl start nginx

# Stop Nginx (kills it dead)
sudo systemctl stop nginx

# Reload without full restart
sudo systemctl reload nginx

# Check if it's set to start on boot
sudo systemctl is-enabled nginx

# View full system logs
sudo journalctl -u nginx -n 50
```

## The No-Bullshit TL;DR

1. Test your config: `sudo nginx -t`
2. If it passes, restart: `sudo systemctl restart nginx`
3. Verify it worked: `sudo systemctl status nginx`
4. If it's fucked, read the error logs: `sudo tail -f /var/log/nginx/error.log`

If you can't figure it out after that, maybe server administration isn't for you. Try something easier, like underwater basket weaving.

## Final Warning

If you're about to restart Nginx on a production server at 3 PM on a Friday without testing the config first, **stop right the fuck now**. Test it. Make sure it works. Then restart.

Don't be the asshole who takes down the company website because you were too lazy to run `nginx -t` first.

You've been warned, dipshit.
MARKDOWN;
    }
}
