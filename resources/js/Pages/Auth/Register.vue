<template>
    <GuestLayout>
        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="font-display text-3xl/tight font-bold bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent mb-2">
                Join RTFM.guide
            </h2>
            <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                Create your account to get started
            </p>
        </div>

        <!-- Registration Disabled Notice -->
        <div v-if="!registrationEnabled" class="relative overflow-hidden rounded-2xl border-2 border-gold-400/40 dark:border-gold-500/30 bg-linear-to-br from-gold-50/80 via-wine-50/40 to-pearl-50 dark:from-pearl-800/80 dark:via-wine-900/40 dark:to-pearl-900 p-6 backdrop-blur-sm">
            <!-- Decorative accent -->
            <div class="absolute top-0 right-0 size-32 bg-linear-to-br from-gold-400/20 to-wine-400/20 dark:from-gold-500/10 dark:to-wine-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

            <!-- Icon -->
            <div class="relative flex items-start gap-4">
                <div class="shrink-0 size-12 rounded-xl bg-linear-to-br from-gold-500 to-gold-600 dark:from-gold-600 dark:to-gold-700 flex items-center justify-center shadow-lg shadow-gold-600/20">
                    <svg class="size-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="flex-1 pt-0.5">
                    <h3 class="font-display text-lg/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                        Registration Unavailable
                    </h3>
                    <p class="text-sm/relaxed text-pearl-700 dark:text-pearl-300">
                        {{ registrationDisabledMessage }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Register Form -->
        <div v-else>
        <!-- OAuth Buttons -->
        <OAuthButtons />

        <Form
            action="/register"
            method="post"
            #default="{ errors, processing }"
        >
            <!-- Name -->
            <div class="mb-5">
                <label for="name" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                    Name
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
                    :class="{ 'border-wine-500 dark:border-wine-500 focus:border-wine-600 dark:focus:border-wine-600': errors.name }"
                />
                <p v-if="errors.name" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400 font-medium">
                    {{ errors.name }}
                </p>
            </div>

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
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
                    :class="{ 'border-wine-500 dark:border-wine-500 focus:border-wine-600 dark:focus:border-wine-600': errors.password }"
                />
                <p v-if="errors.password" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400 font-medium">
                    {{ errors.password }}
                </p>
            </div>

            <!-- Password Confirmation -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                    Confirm Password
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
                />
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="processing"
                class="w-full bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ processing ? 'Creating account...' : 'Create Account' }}
            </button>
        </Form>
        </div>

        <!-- Login Link -->
        <div class="mt-6 text-center text-sm/relaxed text-pearl-600 dark:text-pearl-400">
            Already have an account?
            <Link href="/login" class="font-semibold text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 transition-colors">
                Sign in
            </Link>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Form, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import OAuthButtons from '@/Components/Auth/OAuthButtons.vue'

const props = defineProps({
    registrationEnabled: {
        type: Boolean,
        default: true,
    },
    registrationDisabledMessage: {
        type: String,
        default: 'Registration is currently disabled.',
    },
})
</script>
