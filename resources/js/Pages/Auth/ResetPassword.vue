<template>
    <GuestLayout>
        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="font-display text-3xl/tight font-bold bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent mb-2">
                Reset Password
            </h2>
            <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                Enter your new password below
            </p>
        </div>

        <!-- Reset Password Form -->
        <Form
            action="/reset-password"
            method="post"
            #default="{ errors, processing }"
        >
            <!-- Hidden Fields -->
            <input type="hidden" name="token" :value="token" />
            <input type="hidden" name="email" :value="email" />

            <!-- Email (read-only display) -->
            <div class="mb-5">
                <label for="email" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    :value="email"
                    disabled
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-pearl-50 dark:bg-pearl-900 text-pearl-600 dark:text-pearl-400"
                />
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                    New Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autofocus
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
                    :class="{ 'border-wine-500 dark:border-wine-500 focus:border-wine-600 dark:focus:border-wine-600': errors.password_confirmation }"
                />
                <p v-if="errors.password_confirmation" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400 font-medium">
                    {{ errors.password_confirmation }}
                </p>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="processing"
                class="w-full bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ processing ? 'Resetting...' : 'Reset Password' }}
            </button>
        </Form>
    </GuestLayout>
</template>

<script setup>
import { Form } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
})
</script>
