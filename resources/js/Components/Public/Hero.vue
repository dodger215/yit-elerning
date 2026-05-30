<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const mouseX = ref(0);
const mouseY = ref(0);
const heroRef = ref(null);

const handleMouseMove = (e) => {
    if (!heroRef.value) return;
    const rect = heroRef.value.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    mouseX.value = x;
    mouseY.value = y;
};

onMounted(() => {
    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
});
</script>

<template>
<section class="hero-section" ref="heroRef">
    <!-- Hero specific bg GIF -->
    <div class="hero-bg-gif" 
         :style="{ transform: `translate(${mouseX * 20}px, ${mouseY * 20}px) scale(1.05)` }"></div>
    
    <!-- Dark gradient overlay -->
    <div class="hero-overlay"></div>

    <!-- Floating noise/grid texture -->
    <div class="hero-texture"
         :style="{ transform: `translate(${mouseX * -30}px, ${mouseY * -30}px)` }"></div>

    <div class="hero-inner">
        <!-- Left: Content -->
        <div class="hero-content" data-aos="fade-right" data-aos-duration="1000">
            <div class="hero-badge">
                <span class="badge-dot"></span>
                Elite Tech Education Platform
            </div>

            <h1 class="hero-title">
                Unlock your full<br />
                <span class="hero-title-accent">learning potential.</span>
            </h1>

            <p class="hero-subtitle">
                Access an elite network of educators and industry
                professionals committed to your success.
            </p>

            <div class="hero-ctas">
                <Link class="hero-btn-primary glassy-btn" href="/auth/register?role=instructor">
                    Become a Tutor
                </Link>
                <Link class="hero-btn-secondary glassy-btn" href="/auth/register">
                    Start Learning →
                </Link>
            </div>

            <div class="hero-social-proof" data-aos="fade-up" data-aos-delay="300">
                <div class="proof-avatars">
                    <div class="proof-avatar" style="background: #3b82f6;">A</div>
                    <div class="proof-avatar" style="background: #8b5cf6;">K</div>
                    <div class="proof-avatar" style="background: #10b981;">E</div>
                    <div class="proof-avatar" style="background: #f59e0b;">Y</div>
                </div>
                <p class="proof-text"><strong>500+</strong> students already enrolled</p>
            </div>
        </div>

        <!-- Right: Image + floating cards -->
        <div class="hero-visual" data-aos="fade-left" data-aos-duration="1200">
            <div class="hero-image-wrap glassy-card"
                 :style="{ transform: `perspective(1000px) rotateY(${mouseX * 10}deg) rotateX(${mouseY * -10}deg)` }">
                <img src="/images/image.png" alt="Student learning" class="hero-img" />
                <div class="card-reflection"></div>
            </div>

            <!-- Floating stat cards -->
            <div class="hero-float-card card-top-right glassy-card float-anim-1">
                <span class="float-icon">🏆</span>
                <div>
                    <p class="float-val">95%</p>
                    <p class="float-lbl">Completion Rate</p>
                </div>
            </div>

            <div class="hero-float-card card-bottom-left glassy-card float-anim-2">
                <span class="float-icon">⚡</span>
                <div>
                    <p class="float-val">40+</p>
                    <p class="float-lbl">Expert Mentors</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="hero-scroll">
        <div class="scroll-line"></div>
        <span>Scroll</span>
    </div>
</section>
</template>

<style scoped>
.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow: hidden;
    background: transparent;
    padding-top: 80px;
}

.hero-bg-gif {
    position: absolute;
    inset: -20px;
    background-image: url('/images/Untitled-video.gif');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.35; 
    z-index: 0;
    transition: transform 0.1s ease-out;
    mix-blend-mode: luminosity;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(6,13,26,0.8) 20%, rgba(15,23,42,0.5) 100%);
    z-index: 1;
}

.hero-texture {
    position: absolute;
    inset: -50px;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(59,130,246,0.2) 0%, transparent 40%),
        radial-gradient(circle at 80% 20%, rgba(244,114,182,0.15) 0%, transparent 40%);
    z-index: 2;
    pointer-events: none;
    transition: transform 0.1s ease-out;
}

.hero-inner {
    position: relative;
    z-index: 3;
    max-width: 1300px;
    margin: 0 auto;
    padding: 80px 40px;
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

/* LEFT CONTENT */
.hero-content {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    font-weight: 700;
    color: rgba(255,255,255,0.9);
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px;
    padding: 8px 18px;
    width: fit-content;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    backdrop-filter: blur(12px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.3);
}

.badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #3b82f6;
    animation: pulse-dot 2s ease-in-out infinite;
    box-shadow: 0 0 12px #3b82f6;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.8); }
}

.hero-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 76px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.05;
    margin: 0;
    letter-spacing: -3px;
    text-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.hero-title-accent {
    background: linear-gradient(270deg, #60a5fa, #c084fc, #f472b6, #60a5fa);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: animated-gradient-text 5s ease infinite;
    font-style: italic;
    padding-right: 10px;
}

@keyframes animated-gradient-text {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.hero-subtitle {
    font-size: 19px;
    color: rgba(255,255,255,0.75);
    line-height: 1.7;
    margin: 0;
    max-width: 480px;
    font-family: 'Outfit', sans-serif;
}

.hero-ctas {
    display: flex;
    gap: 14px;
    align-items: center;
    flex-wrap: wrap;
}

.glassy-btn {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2), 0 8px 20px rgba(0,0,0,0.2);
}

.hero-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 54px;
    padding: 0 40px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.9), rgba(37, 99, 235, 0.9));
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
    border-radius: 16px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border: 1px solid rgba(96, 165, 250, 0.6);
}

.hero-btn-primary:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.4), 0 15px 30px rgba(37, 99, 235, 0.5);
    background: linear-gradient(135deg, rgba(96, 165, 250, 1), rgba(37, 99, 235, 1));
}

.hero-btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 54px;
    padding: 0 36px;
    background: rgba(255, 255, 255, 0.08);
    color: #ffffff;
    font-size: 16px;
    font-weight: 600;
    border-radius: 16px;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.hero-btn-secondary:hover {
    background: rgba(255,255,255,0.15);
    transform: translateY(-3px) scale(1.02);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 15px 30px rgba(0,0,0,0.3);
}

.hero-social-proof {
    display: flex;
    align-items: center;
    gap: 14px;
}

.proof-avatars { display: flex; }

.proof-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid #060d1a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 800;
    color: #ffffff;
    margin-left: -10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.4);
    transition: transform 0.2s ease;
}
.proof-avatar:hover { transform: translateY(-4px) scale(1.1); z-index: 10; }
.proof-avatar:first-child { margin-left: 0; }

.proof-text {
    font-size: 14px;
    color: rgba(255,255,255,0.7);
    margin: 0;
}
.proof-text strong { color: #ffffff; }

/* RIGHT VISUAL */
.hero-visual {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.glassy-card {
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(24px) saturate(150%);
    -webkit-backdrop-filter: blur(24px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 20px 50px rgba(0,0,0,0.5);
}

.hero-image-wrap {
    position: relative;
    width: 100%;
    max-width: 520px;
    border-radius: 24px;
    overflow: hidden;
    transition: transform 0.1s ease-out;
}

.card-reflection {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 50%);
    pointer-events: none;
    z-index: 2;
}

.hero-img {
    width: 100%;
    height: 560px;
    object-fit: cover;
    object-position: center top;
    display: block;
    opacity: 0.95;
    mix-blend-mode: normal;
}

/* Floating cards */
.hero-float-card {
    position: absolute;
    border-radius: 16px;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.card-top-right { top: 40px; right: -30px; }
.card-bottom-left { bottom: 60px; left: -30px; }

.float-anim-1 { animation: float 6s ease-in-out infinite; }
.float-anim-2 { animation: float 8s ease-in-out infinite reverse; }

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

.float-icon { font-size: 28px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.4)); }

.float-val {
    font-size: 24px;
    font-weight: 800;
    color: #ffffff;
    margin: 0;
    line-height: 1;
    font-family: 'Space Grotesk', sans-serif;
}

.float-lbl {
    font-size: 11px;
    color: rgba(255,255,255,0.7);
    margin: 4px 0 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Scroll indicator */
.hero-scroll {
    position: absolute;
    bottom: 32px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: rgba(255,255,255,0.5);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.scroll-line {
    width: 2px;
    height: 36px;
    background: rgba(255,255,255,0.4);
    animation: scroll-anim 2s ease-in-out infinite;
}

@keyframes scroll-anim {
    0%, 100% { transform: scaleY(1); opacity: 0.8; transform-origin: top; }
    50% { transform: scaleY(0); opacity: 0; transform-origin: bottom; }
}

/* Responsive */
@media (max-width: 1024px) {
    .hero-inner { grid-template-columns: 1fr; gap: 48px; padding: 60px 24px; text-align: center; }
    .hero-title { font-size: 60px; }
    .hero-ctas { justify-content: center; }
    .hero-social-proof { justify-content: center; }
    .hero-badge { margin: 0 auto; }
    .hero-visual { max-width: 480px; margin: 0 auto; }
    .card-top-right { right: -10px; }
    .card-bottom-left { left: -10px; }
}
@media (max-width: 640px) {
    .hero-title { font-size: 42px; }
    .hero-btn-primary, .hero-btn-secondary { width: 100%; }
    .hero-img { height: 400px; }
    .hero-float-card { display: none; }
}
</style>
