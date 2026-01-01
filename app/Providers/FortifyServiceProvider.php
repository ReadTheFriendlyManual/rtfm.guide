<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            $settings = Setting::current();

            if (! $settings->login_enabled) {
                throw ValidationException::withMessages([
                    Fortify::username() => [$settings->login_disabled_message ?? 'Login is currently disabled.'],
                ]);
            }

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(function () {
            $settings = Setting::current();

            return Inertia::render('Auth/Login', [
                'canResetPassword' => true,
                'loginEnabled' => $settings->login_enabled,
                'loginDisabledMessage' => $settings->login_disabled_message ?? 'Login is currently disabled.',
            ]);
        });

        Fortify::registerView(function () {
            $settings = Setting::current();

            return Inertia::render('Auth/Register', [
                'registrationEnabled' => $settings->registration_enabled,
                'registrationDisabledMessage' => $settings->registration_disabled_message ?? 'Registration is currently disabled.',
            ]);
        });

        Fortify::verifyEmailView(fn () => Inertia::render('Auth/VerifyEmail'));
        Fortify::twoFactorChallengeView(fn () => Inertia::render('Auth/TwoFactorChallenge'));
        Fortify::confirmPasswordView(fn () => Inertia::render('Auth/ConfirmPassword'));
        Fortify::resetPasswordView(fn (Request $request) => Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]));
        Fortify::requestPasswordResetLinkView(fn () => Inertia::render('Auth/ForgotPassword'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
