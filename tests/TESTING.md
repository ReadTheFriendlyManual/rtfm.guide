# Testing Guide

## Overview

This project has comprehensive test coverage for email verification, toast notifications, and related features.

## Test Categories

### Unit & Feature Tests
Standard PHPUnit/Pest tests that run quickly and reliably.

```bash
# Run all tests
php artisan test

# Run all tests in parallel
./vendor/bin/pest --parallel

# Run specific test file
php artisan test tests/Feature/Auth/EmailVerificationFlowTest.php

# Run tests by filter
php artisan test --filter=EmailVerification
```


## Email Verification Test Coverage

### Token Management (13 tests)
- `tests/Feature/Notifications/VerifyEmailNotificationTest.php`
  - Token generation and uniqueness
  - Token expiration (24 hours)
  - Token replacement when resending
  - Token cleanup after verification
  - Email content and URL validation
  - Queue verification

### Verification Flow (11 tests)
- `tests/Feature/Auth/EmailVerificationFlowTest.php`
  - Valid token verification
  - Expired token rejection
  - Invalid token rejection
  - Already verified user handling
  - Token deletion after use

- `tests/Feature/Auth/EmailVerificationNotificationTest.php`
  - Rate limiting (1 per minute)
  - Resend notification
  - Success toast display

**Total: 24 tests covering email verification and toasts**

## Toast Notification Testing

Toast notifications are tested at the feature level:

1. **Unit Level**: Flash messages are set correctly in session
2. **Feature Level**: HTTP responses contain correct flash data and redirect properly

### Toast Types Tested
- ✅ Success (green) - Email verified
- ✅ Error (red) - Invalid/expired tokens, rate limits
- ✅ Info (blue) - Verification email sent, already verified

### Toast Integration Verified
- ✅ Toast messages flash to session
- ✅ Shared via Inertia props to frontend
- ✅ Rate limiting with countdown timer
- ✅ Translation support for SFW/NSFW mode

## Quick Test Commands

```bash
# All email verification tests
php artisan test --filter=EmailVerification

# All toast tests
php artisan test --filter=Toast

# All tests in parallel
./vendor/bin/pest --parallel

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter="shows success toast after email verification"
```

## Continuous Integration

For GitHub Actions or other CI:

```yaml
# .github/workflows/tests.yml
- name: Run Tests
  run: ./vendor/bin/pest --parallel
```

## Troubleshooting

### Tests fail with "Route [login] not defined"
```bash
php artisan config:clear
php artisan route:clear
```

### Email tests fail with queue errors
Queues are enabled by default. Tests use `Notification::fake()` to avoid actual queue jobs.
