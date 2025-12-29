<template>
    <GuestLayout>
        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="font-display text-3xl/tight font-bold bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent mb-2">
                Verify Your Email
            </h2>
            <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
            </p>
        </div>

        <!-- Success Message -->
        <div v-if="status === 'verification-link-sent'" class="mb-6 p-4 rounded-xl bg-gold-50 dark:bg-gold-900/20 border-2 border-gold-200 dark:border-gold-800">
            <p class="text-sm/relaxed text-gold-700 dark:text-gold-400 font-medium">
                A new verification link has been sent to your email address.
            </p>
        </div>

        <!-- Resend Verification Email Form -->
        <Form
            action="/email/verification-notification"
            method="post"
            #default="{ processing }"
        >
            <button
                type="submit"
                :disabled="processing"
                class="w-full bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ processing ? 'Sending...' : 'Resend Verification Email' }}
            </button>
        </Form>

        <!-- Logout Link -->
        <div class="mt-6 text-center">
            <Form
                action="/logout"
                method="post"
                #default="{ processing }"
                class="inline"
            >
                <button
                    type="submit"
                    :disabled="processing"
                    class="text-sm/relaxed font-semibold text-pearl-600 dark:text-pearl-400 hover:text-pearl-900 dark:hover:text-pearl-200 transition-colors"
                >
                    Log Out
                </button>
            </Form>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Form } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

defineProps({
    status: String,
})
</script>
