<template>
    <GuestLayout>
        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="font-display text-3xl/tight font-bold bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent mb-2">
                Forgot Password
            </h2>
            <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </p>
        </div>

        <!-- Success Message -->
        <div v-if="status" class="mb-6 p-4 rounded-xl bg-gold-50 dark:bg-gold-900/20 border-2 border-gold-200 dark:border-gold-800">
            <p class="text-sm/relaxed text-gold-700 dark:text-gold-400 font-medium">
                {{ status }}
            </p>
        </div>

        <!-- Password Reset Form -->
        <Form
            action="/forgot-password"
            method="post"
            #default="{ errors, processing }"
        >
            <!-- Email -->
            <div class="mb-6">
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

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="processing"
                class="w-full bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ processing ? 'Sending...' : 'Email Password Reset Link' }}
            </button>
        </Form>

        <!-- Back to Login Link -->
        <div class="mt-6 text-center text-sm/relaxed text-pearl-600 dark:text-pearl-400">
            Remember your password?
            <Link href="/login" class="font-semibold text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 transition-colors">
                Sign in
            </Link>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Form, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

defineProps({
    status: String,
})
</script>
