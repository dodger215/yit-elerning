<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
<nav :class="['public-navbar', { 'is-scrolled': isScrolled }]">
    <div class="navbar-container">
        <Link href="/" class="navbar-logo">
            <img src="/images/logo.png" alt="YOUTH IN TECH" class="logo-img" />
        </Link>

        <div class="navbar-center desktop-only">
            <div class="nav-pill">
                <a href="#about" class="nav-link">About</a>
                <a href="#how-it-works" class="nav-link">Process</a>
                <a href="#courses" class="nav-link">Courses</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="#faq" class="nav-link">FAQ</a>
            </div>
        </div>

        <div class="navbar-right">
            <Link href="/auth/login" class="auth-pill-button">Sign In</Link>
            <Link href="/auth/register" class="auth-pill-button primary">Get Started</Link>
        </div>
    </div>
</nav>
</template>

<style scoped>
.public-navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    padding: 24px 0;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    background: transparent;
}

.public-navbar.is-scrolled {
    padding: 12px 0;
    background: rgba(10, 15, 30, 0.65);
    backdrop-filter: blur(24px) saturate(150%);
    -webkit-backdrop-filter: blur(24px) saturate(150%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.05), 0 10px 40px rgba(0,0,0,0.2);
}

.navbar-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar-logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    z-index: 2;
    transition: transform 0.3s ease;
}

.navbar-logo:hover {
    transform: scale(1.05);
}

.logo-img {
    height: 48px;
    width: auto;
    filter: brightness(0) invert(1);
}

.navbar-center {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;
}

.nav-pill {
    display: flex;
    align-items: center;
    gap: 4px;
    background: rgba(255, 255, 255, 0.03);
    padding: 6px;
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.nav-link {
    color: rgba(255,255,255,0.7);
    font-size: 14px;
    font-weight: 500;
    padding: 8px 18px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);
    transform: translateY(-1px);
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 2;
}

.auth-pill-button {
    display: inline-flex;
    align-items: center;
    height: 40px;
    padding: 0 22px;
    font-size: 14px;
    font-weight: 600;
    color: #f1f5f9;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
}

.auth-pill-button:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-2px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.15), 0 4px 12px rgba(0,0,0,0.2);
}

.auth-pill-button.primary {
    background: #ffffff;
    color: #0f172a;
    border-color: #ffffff;
    box-shadow: 0 4px 14px rgba(255,255,255,0.2);
}

.auth-pill-button.primary:hover {
    background: #f1f5f9;
    box-shadow: 0 6px 20px rgba(255,255,255,0.3);
    transform: translateY(-2px);
}

.mobile-only { display: none !important; }

@media (max-width: 1024px) {
    .navbar-container { padding: 0 24px; }
    .nav-pill { display: none; }
}

@media (max-width: 768px) {
    .public-navbar { padding: 16px 0; }
    .navbar-container { padding: 0 16px; }
    .desktop-only { display: none !important; }
    .mobile-only { display: flex !important; }
    .logo-img { height: 40px; }
}
</style>
