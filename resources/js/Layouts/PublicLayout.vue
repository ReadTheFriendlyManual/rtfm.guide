<template>
    <div class="min-h-screen bg-white dark:bg-slate-950 text-slate-900 dark:text-white transition-colors duration-200">
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
