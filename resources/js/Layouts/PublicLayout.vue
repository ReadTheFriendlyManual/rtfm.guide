<template>
    <div class="min-h-dvh bg-pearl-50 dark:bg-pearl-950 text-pearl-900 dark:text-pearl-50 transition-colors duration-300">
        <Navbar />

        <main class="pt-16">
            <slot />
        </main>

        <Footer />
    </div>
</template>

<script setup>
import Navbar from '@/Components/Shared/Navbar.vue'
import Footer from '@/Components/Shared/Footer.vue'
</script>
