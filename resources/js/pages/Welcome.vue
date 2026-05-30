<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import PublicNavbar from '../Components/Public/PublicNavbar.vue';
import Hero from '../Components/Public/Hero.vue';
import AboutSection from '../Components/Public/AboutSection.vue';
import HowWeWorkSection from '../Components/Public/HowWeWorkSection.vue';
import JourneySection from '../Components/Public/JourneySection.vue';
import CoursesSection from '../Components/Public/CoursesSection.vue';
import PlatformFeaturesSection from '../Components/Public/PlatformFeaturesSection.vue';
import TestimonialsSection from '../Components/Public/TestimonialsSection.vue';
import FAQSection from '../Components/Public/FAQSection.vue';
import FinalCTASection from '../Components/Public/FinalCTASection.vue';
import Footer from '../Components/Public/Footer.vue';

import AOS from 'aos';
import 'aos/dist/aos.css';

defineProps({
  appName: {
    type: String,
    default: 'YouthInTech',
  },
});

const cursorX = ref(0);
const cursorY = ref(0);
const cursorHover = ref(false);
const isLoading = ref(true);

const updateCursor = (e) => {
    cursorX.value = e.clientX;
    cursorY.value = e.clientY;
    
    // Check if hovering over clickable elements
    const target = e.target;
    if (target.tagName.toLowerCase() === 'a' || 
        target.tagName.toLowerCase() === 'button' ||
        target.closest('a') || target.closest('button')) {
        cursorHover.value = true;
    } else {
        cursorHover.value = false;
    }
};

onMounted(() => {
  AOS.init({
    duration: 800,
    once: true,
    offset: 100,
    easing: 'ease-out-cubic'
  });
  
  window.addEventListener('mousemove', updateCursor);
  
  // Hide loader after animation
  setTimeout(() => {
      isLoading.value = false;
  }, 2200);
});

onUnmounted(() => {
    window.removeEventListener('mousemove', updateCursor);
});
</script>

<template>
  <Head :title="'Welcome to ' + appName">
      <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
  </Head>

  <!-- Custom Cursor -->
  <div class="custom-cursor" 
       :class="{ 'cursor-hover': cursorHover }"
       :style="{ left: cursorX + 'px', top: cursorY + 'px' }">
      <div class="cursor-dot"></div>
  </div>

  <div class="public-layout">
    <!-- Loading Screen -->
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

    <!-- Global Animated Overlay Background -->
    <div class="global-animated-bg"></div>

    <PublicNavbar />

    <main class="slideshow-animation-container">
      <Hero data-aos="fade-in" />
      
      <div data-aos="fade-up">
        <AboutSection />
      </div>

      <div data-aos="fade-up" data-aos-delay="100">
        <HowWeWorkSection />
      </div>

      <div class="combined-bg-wrapper">
        <div data-aos="fade-up" data-aos-delay="200">
            <JourneySection />
        </div>
        <div data-aos="fade-up" data-aos-delay="100">
            <CoursesSection />
        </div>
      </div>

      <div data-aos="zoom-in" data-aos-delay="150">
        <PlatformFeaturesSection />
      </div>

      <div data-aos="fade-up" data-aos-delay="200">
        <TestimonialsSection />
      </div>

      <div data-aos="fade-up" data-aos-delay="100">
        <FAQSection />
      </div>

      <div data-aos="flip-up" data-aos-delay="300">
        <FinalCTASection />
      </div>
    </main>

    <Footer />
  </div>
</template>

<style scoped>
.public-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #060d1a;
  color: #f1f5f9;
  overflow-x: hidden;
  font-family: 'Outfit', 'Inter', sans-serif;
  cursor: none; /* Hide default cursor */
  position: relative;
}

/* Global Animated Overlay */
.global-animated-bg {
    position: fixed;
    inset: 0;
    width: 100%;
    height: 100%;
    background-image: url('/images/overlay.gif');
    background-size: cover;
    background-position: center;
    background-repeat: repeat;
    opacity: 0.05;
    pointer-events: none;
    z-index: 0;
    mix-blend-mode: screen;
}

/* Ensure child elements don't show default cursor */
.public-layout :deep(a),
.public-layout :deep(button),
.public-layout :deep(input),
.public-layout :deep(*) {
  cursor: none !important;
}

/* Base style resets */
.public-layout :deep(h1), .public-layout :deep(h2), .public-layout :deep(h3) {
    font-family: 'Outfit', sans-serif;
}

/* Custom Cursor Styles */
.custom-cursor {
    position: fixed;
    top: 0;
    left: 0;
    width: 32px;
    height: 32px;
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 9999;
    transition: width 0.3s, height 0.3s, background-color 0.3s, border-color 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    mix-blend-mode: difference;
}

.cursor-dot {
    width: 4px;
    height: 4px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s;
}

.custom-cursor.cursor-hover {
    width: 64px;
    height: 64px;
    background: rgba(255, 255, 255, 0.1);
    border-color: transparent;
    backdrop-filter: blur(4px);
    mix-blend-mode: normal;
}

.custom-cursor.cursor-hover .cursor-dot {
    transform: scale(0);
}

main {
  flex-grow: 1;
  position: relative;
  z-index: 1;
}

.combined-bg-wrapper {
    position: relative;
    width: 100%;
    display: block;
}

.slideshow-animation-container > div {
  animation: slideshowFadeIn 1.2s ease-out both;
}

@keyframes slideshowFadeIn {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Mobile fallback for cursor */
@media (max-width: 768px) {
    .custom-cursor { display: none; }
    .public-layout,
    .public-layout :deep(a),
    .public-layout :deep(button),
    .public-layout :deep(*) {
        cursor: auto !important;
    }
    .public-layout :deep(a),
    .public-layout :deep(button) {
        cursor: pointer !important;
    }
}

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
