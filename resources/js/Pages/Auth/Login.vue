<template>
    <GuestLayout>
        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="font-display text-3xl/tight font-bold bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent mb-2">
                Welcome Back
            </h2>
            <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                Sign in to your account to continue
            </p>
        </div>

        <!-- Login Disabled Notice -->
        <div v-if="!loginEnabled" class="relative overflow-hidden rounded-2xl border-2 border-wine-400/40 dark:border-wine-500/30 bg-linear-to-br from-wine-50/80 via-gold-50/40 to-pearl-50 dark:from-pearl-800/80 dark:via-wine-900/40 dark:to-pearl-900 p-6 backdrop-blur-sm">
            <!-- Decorative accent -->
            <div class="absolute top-0 right-0 size-32 bg-linear-to-br from-wine-400/20 to-gold-400/20 dark:from-wine-500/10 dark:to-gold-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

            <!-- Icon -->
            <div class="relative flex items-start gap-4">
                <div class="shrink-0 size-12 rounded-xl bg-linear-to-br from-wine-500 to-wine-600 dark:from-wine-600 dark:to-wine-700 flex items-center justify-center shadow-lg shadow-wine-600/20">
                    <svg class="size-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="flex-1 pt-0.5">
                    <h3 class="font-display text-lg/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                        Login Unavailable
                    </h3>
                    <p class="text-sm/relaxed text-pearl-700 dark:text-pearl-300">
                        {{ loginDisabledMessage }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div v-else>
        <!-- OAuth Buttons -->
        <OAuthButtons />

        <Form
            action="/login"
            method="post"
            #default="{ errors, processing }"
        >
            <!-- Email -->
            <div class="mb-5">
                <label for="email" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
                    :class="{ 'border-wine-500 dark:border-wine-500 focus:border-wine-600 dark:focus:border-wine-600': errors.email }"
                />
                <p v-if="errors.email" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400 font-medium">
                    {{ errors.email }}
                </p>
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
                    :class="{ 'border-wine-500 dark:border-wine-500 focus:border-wine-600 dark:focus:border-wine-600': errors.password }"
                />
                <p v-if="errors.password" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400 font-medium">
                    {{ errors.password }}
                </p>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center group cursor-pointer">
                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded-lg border-2 border-pearl-300 dark:border-pearl-600 text-wine-600 focus:ring-wine-500 focus:ring-3 focus:ring-offset-0 transition-all"
                    />
                    <span class="ml-2 text-sm/relaxed font-medium text-pearl-600 dark:text-pearl-400 group-hover:text-pearl-900 dark:group-hover:text-pearl-200 transition-colors">
                        Remember me
                    </span>
                </label>

                <Link
                    v-if="canResetPassword"
                    href="/forgot-password"
                    class="text-sm/relaxed font-semibold text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 transition-colors"
                >
                    Forgot password?
                </Link>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="processing"
                class="w-full bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ processing ? 'Signing in...' : 'Sign In' }}
            </button>
        </Form>
        </div>

        <!-- Register Link -->
        <div class="mt-6 text-center text-sm/relaxed text-pearl-600 dark:text-pearl-400">
            Don't have an account?
            <Link href="/register" class="font-semibold text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 transition-colors">
                Sign up
            </Link>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Form, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import OAuthButtons from '@/Components/Auth/OAuthButtons.vue'

defineProps({
    canResetPassword: {
        type: Boolean,
        default: true,
    },
    loginEnabled: {
        type: Boolean,
        default: true,
    },
    loginDisabledMessage: {
        type: String,
        default: 'Login is currently disabled.',
    },
})
</script>
