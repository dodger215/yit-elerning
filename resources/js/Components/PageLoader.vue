<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

const isLoading = ref(true);
let timeoutId = null;

onMounted(() => {
    // Initial page load delay
    timeoutId = setTimeout(() => {
        isLoading.value = false;
    }, 2200);

    // Listen to Inertia routing events for SPA navigation
    const removeStart = router.on('start', () => {
        clearTimeout(timeoutId);
        isLoading.value = true;
    });

    const removeFinish = router.on('finish', () => {
        timeoutId = setTimeout(() => {
            isLoading.value = false;
        }, 1500); // Shorter delay for SPA transitions
    });

    onUnmounted(() => {
        clearTimeout(timeoutId);
        removeStart();
        removeFinish();
    });
});
</script>

<template>
    <transition name="fade-loader">
        <div v-if="isLoading" class="page-loader">
            <div class="loader-content">
                <div class="loader-logo-wrapper">
                    <!-- Dim background logo -->
                    <img src="/images/logo.png" alt="YIT Logo" class="loader-logo dim-logo" />
                    <!-- Bright filling logo -->
                    <img src="/images/logo.png" alt="YIT Logo" class="loader-logo fill-logo" />
                </div>
                <div class="loader-glow"></div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
/* Loader Styles */
.page-loader {
    position: fixed;
    inset: 0;
    z-index: 999999;
    background-color: #060d1a;
    display: flex;
    justify-content: center;
    align-items: center;
}

.fade-loader-leave-active {
    transition: opacity 0.8s ease-in-out, transform 0.8s cubic-bezier(0.86, 0, 0.07, 1);
}
.fade-loader-leave-to {
    opacity: 0;
    transform: scale(1.1);
}

.loader-content {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.loader-logo-wrapper {
    position: relative;
    width: 140px;
    height: auto;
    z-index: 2;
}

.loader-logo {
    width: 100%;
    height: auto;
    filter: brightness(0) invert(1);
    display: block;
}

.dim-logo {
    opacity: 0.15;
}

.fill-logo {
    position: absolute;
    top: 0;
    left: 0;
    clip-path: inset(100% 0 0 0);
    animation: fill-up-logo 1.8s cubic-bezier(0.86, 0, 0.07, 1) forwards;
}

.loader-glow {
    position: absolute;
    width: 80px;
    height: 80px;
    background: #3b82f6;
    border-radius: 50%;
    filter: blur(50px);
    opacity: 0;
    z-index: 1;
    animation: glow-pulse 1.8s cubic-bezier(0.86, 0, 0.07, 1) forwards;
}

@keyframes fill-up-logo {
    0% { clip-path: inset(100% 0 0 0); }
    100% { clip-path: inset(0 0 0 0); }
}

@keyframes glow-pulse {
    0% { opacity: 0; transform: scale(0.5); }
    100% { opacity: 0.7; transform: scale(2.5); }
}
</style>
