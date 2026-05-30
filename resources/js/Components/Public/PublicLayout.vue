<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<div class="public-layout">
            <PublicNavbar />
            <main class="public-main">
                {children}
            </main>
            <Footer />
        </div>
</template>

<style scoped>
.public-layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: transparent;
    color: #0f172a;
}

.public-main {
    flex: 1;
    width: 100%;
}
</style>
