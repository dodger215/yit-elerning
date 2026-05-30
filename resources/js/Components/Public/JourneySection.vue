<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<section class="journey-section" id="journey">
    <div class="journey-container">
        <div class="journey-content" data-aos="fade-right">
            <span class="section-label">YOUR GROWTH STORY</span>
            <h2 class="journey-title">
                <span class="journey-title-line">Ready to Start</span>
                Your Journey?
            </h2>
            <p class="journey-subtitle">We assess every aspect of your readiness:</p>

            <div class="journey-checklist">
                <div class="checklist-item glassy-panel" data-aos="fade-up" data-aos-delay="100">
                    <span class="check-icon">✓</span>
                    <span class="check-text">Your current skills and experience level</span>
                </div>
                <div class="checklist-item glassy-panel" data-aos="fade-up" data-aos-delay="150">
                    <span class="check-icon">✓</span>
                    <span class="check-text">Your career goals and target industry</span>
                </div>
                <div class="checklist-item glassy-panel" data-aos="fade-up" data-aos-delay="200">
                    <span class="check-icon">✓</span>
                    <span class="check-text">Your preferred learning style and schedule</span>
                </div>
                <div class="checklist-item glassy-panel" data-aos="fade-up" data-aos-delay="250">
                    <span class="check-icon">✓</span>
                    <span class="check-text">The fastest path to your first tech role</span>
                </div>
            </div>

            <div class="journey-result glassy-panel" data-aos="zoom-in" data-aos-delay="300">
                <div class="card-reflection"></div>
                <p><strong>Result:</strong> Teams that ship faster, stay longer, and scale smarter.</p>
            </div>

            <Link href="/auth/register" class="journey-cta" data-aos="fade-up" data-aos-delay="350">
                Begin Your Assessment →
            </Link>
        </div>

        <div class="journey-image-wrapper" data-aos="fade-left">
            <div class="glassy-card-wrap">
                <div class="card-reflection"></div>
                <img src="/images/student-image.png" alt="Student learning" class="journey-img" />
            </div>
        </div>
    </div>
</section>
</template>

<style scoped>
.journey-section {
    padding: 100px 20px;
    background: #060d1a;
    overflow: hidden;
    position: relative;
}

.journey-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 80px;
    position: relative;
    z-index: 2;
}

.glassy-panel {
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(12px) saturate(150%);
    -webkit-backdrop-filter: blur(12px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 4px 12px rgba(0,0,0,0.1);
}

.card-reflection {
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, transparent 40%);
    pointer-events: none;
    z-index: 1;
}

.journey-content { flex: 1; }

.section-label {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    color: #60a5fa;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 20px;
    padding: 6px 14px;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 20px;
}

.journey-title {
    font-size: 52px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.1;
    margin: 0 0 20px;
    font-family: 'Montserrat', sans-serif;
}

.journey-title-line {
    display: block;
    color: #60a5fa;
}

.journey-subtitle {
    font-size: 17px;
    color: rgba(255,255,255,0.6);
    margin: 0 0 28px;
    font-weight: 500;
}

.journey-checklist {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 36px;
}

.checklist-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-radius: 14px;
    transition: transform 0.3s ease, border-color 0.3s ease;
}
.checklist-item:hover {
    transform: translateX(8px);
    border-color: rgba(96, 165, 250, 0.4);
}

.check-icon {
    color: #60a5fa;
    font-weight: 700;
    font-size: 18px;
    line-height: 1;
    flex-shrink: 0;
}

.check-text {
    font-size: 16px;
    color: rgba(255,255,255,0.8);
    line-height: 1.5;
}

.journey-result {
    font-size: 15px;
    color: rgba(255,255,255,0.7);
    padding: 22px 26px;
    border-left: 4px solid #3b82f6;
    border-radius: 0 16px 16px 0;
    margin-bottom: 36px;
    position: relative;
    overflow: hidden;
}

.journey-result strong { color: #ffffff; }

.journey-cta {
    display: inline-flex;
    align-items: center;
    font-size: 15px;
    font-weight: 700;
    color: #ffffff;
    text-decoration: none;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
    padding: 12px 24px;
    background: rgba(59, 130, 246, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(96, 165, 250, 0.5);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2), 0 8px 16px rgba(0,0,0,0.3);
}
.journey-cta:hover { 
    background: rgba(37, 99, 235, 0.9);
    transform: translateY(-2px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 12px 24px rgba(37, 99, 235, 0.4);
}

.journey-image-wrapper {
    flex: 1;
    display: flex;
    justify-content: flex-end;
}

.glassy-card-wrap {
    position: relative;
    width: 100%;
    max-width: 520px;
    border-radius: 24px;
    padding: 12px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1), 0 20px 40px rgba(0,0,0,0.4);
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}
.glassy-card-wrap:hover { transform: perspective(1000px) rotateY(-5deg) scale(1.02); }

.journey-img {
    width: 100%;
    border-radius: 16px;
    object-fit: cover;
    display: block;
}

@media (max-width: 900px) {
    .journey-container { flex-direction: column; gap: 48px; }
    .journey-title { font-size: 38px; }
    .journey-image-wrapper { justify-content: center; width: 100%; }
    .glassy-card-wrap { max-width: 100%; }
}
@media (max-width: 600px) {
    .journey-title { font-size: 30px; }
}
</style>
