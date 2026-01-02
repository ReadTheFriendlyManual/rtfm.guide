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

        <!-- Resend Verification Email Form -->
        <Form
            action="/email/verification-notification"
            method="post"
            #default="{ processing }"
            @submit="handleSubmit"
        >
            <button
                type="submit"
                :disabled="processing || isRateLimited"
                class="w-full bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="processing">Sending...</span>
                <span v-else-if="isRateLimited">Wait {{ remainingSeconds }}s</span>
                <span v-else>Resend Verification Email</span>
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
import { Form, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, watch } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const page = usePage()
const remainingSeconds = ref(0)
const rateLimitEndTime = ref(null)
let intervalId = null

const isRateLimited = computed(() => remainingSeconds.value > 0)

const updateRemainingSeconds = () => {
    if (!rateLimitEndTime.value) {
        remainingSeconds.value = 0
        return
    }

    const now = Date.now()
    const diff = Math.ceil((rateLimitEndTime.value - now) / 1000)

    if (diff <= 0) {
        remainingSeconds.value = 0
        rateLimitEndTime.value = null
        if (intervalId) {
            clearInterval(intervalId)
            intervalId = null
        }
    } else {
        remainingSeconds.value = diff
    }
}

const startRateLimitTimer = (seconds = 60) => {
    rateLimitEndTime.value = Date.now() + (seconds * 1000)
    updateRemainingSeconds()

    if (intervalId) {
        clearInterval(intervalId)
    }

    intervalId = setInterval(updateRemainingSeconds, 1000)
}

const handleSubmit = () => {
    startRateLimitTimer()
}

// Watch for errors in flash messages to extract rate limit time
watch(() => page.props.flash, (flash) => {
    if (flash?.error && typeof flash.error === 'string') {
        const match = flash.error.match(/wait (\d+) seconds/)
        if (match) {
            const seconds = parseInt(match[1])
            startRateLimitTimer(seconds)
        }
    }
}, { deep: true, immediate: true })

onMounted(() => {
    updateRemainingSeconds()
})
</script>
