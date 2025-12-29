<template>
    <GuestLayout>
        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="font-display text-3xl/tight font-bold bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent mb-2">
                Two-Factor Authentication
            </h2>
            <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                {{ recovery ? 'Please confirm access to your account by entering one of your emergency recovery codes.' : 'Please confirm access to your account by entering the authentication code provided by your authenticator application.' }}
            </p>
        </div>

        <!-- Two-Factor Challenge Form -->
        <Form
            action="/two-factor-challenge"
            method="post"
            #default="{ errors, processing }"
        >
            <!-- Code Input (when not using recovery) -->
            <div v-if="!recovery" class="mb-6">
                <label for="code" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                    Authentication Code
                </label>
                <input
                    id="code"
                    type="text"
                    name="code"
                    inputmode="numeric"
                    autofocus
                    autocomplete="one-time-code"
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500 font-mono text-lg/tight tracking-widest text-center"
                    :class="{ 'border-wine-500 dark:border-wine-500 focus:border-wine-600 dark:focus:border-wine-600': errors.code }"
                />
                <p v-if="errors.code" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400 font-medium">
                    {{ errors.code }}
                </p>
            </div>

            <!-- Recovery Code Input (when using recovery) -->
            <div v-else class="mb-6">
                <label for="recovery_code" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                    Recovery Code
                </label>
                <input
                    id="recovery_code"
                    type="text"
                    name="recovery_code"
                    autofocus
                    autocomplete="one-time-code"
                    class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500 font-mono text-lg/tight"
                    :class="{ 'border-wine-500 dark:border-wine-500 focus:border-wine-600 dark:focus:border-wine-600': errors.recovery_code }"
                />
                <p v-if="errors.recovery_code" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400 font-medium">
                    {{ errors.recovery_code }}
                </p>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="processing"
                class="w-full bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ processing ? 'Verifying...' : 'Verify' }}
            </button>
        </Form>

        <!-- Toggle Recovery Mode -->
        <div class="mt-6 text-center">
            <button
                @click="toggleRecovery"
                class="text-sm/relaxed font-semibold text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 transition-colors"
            >
                {{ recovery ? 'Use an authentication code' : 'Use a recovery code' }}
            </button>
        </div>
    </GuestLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Form } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const recovery = ref(false)

function toggleRecovery() {
    recovery.value = !recovery.value
}
</script>
