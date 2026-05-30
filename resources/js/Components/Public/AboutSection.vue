<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<section class="about-section" id="about">
    <div class="about-container">
        <div class="about-header" data-aos="fade-up">
            <span class="section-label">GET TO KNOW US</span>
            <h2 class="about-heading">Think of us as your career guides.</h2>
            <p class="about-subtext">We combine expert mentorship with hands-on curriculum to prepare you for the real world of tech.</p>
        </div>

        <div class="about-card glassy-panel" data-aos="zoom-in" data-aos-delay="150">
            <div class="card-bg-effect"></div>
            <div class="card-reflection"></div>
            <div class="card-content">
                <span class="step-tag">Step 1 — Discover</span>
                <h3 class="step-title">Find the right path<br/>for your career.</h3>
                <p class="step-description">
                    We help you identify your strengths, interests, and the most impactful career path available in tech today. No guesswork — just clarity.
                </p>
                <Link href="/auth/register" class="card-cta">Start Your Journey →</Link>
            </div>
        </div>

        <div class="about-stats glassy-panel" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-box">
                <span class="stat-num">500+</span>
                <span class="stat-label">Students Enrolled</span>
            </div>
            <div class="stat-box">
                <span class="stat-num">40+</span>
                <span class="stat-label">Expert Mentors</span>
            </div>
            <div class="stat-box">
                <span class="stat-num">95%</span>
                <span class="stat-label">Completion Rate</span>
            </div>
            <div class="stat-box">
                <span class="stat-num">12+</span>
                <span class="stat-label">Tech Disciplines</span>
            </div>
        </div>
    </div>
</section>
</template>

<style scoped>
.about-section {
    padding: 100px 20px;
    background: #060d1a;
    position: relative;
    overflow: hidden;
}

/* Glassy generic class */
.glassy-panel {
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(24px) saturate(150%);
    -webkit-backdrop-filter: blur(24px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 10px 30px rgba(0,0,0,0.2);
    border-radius: 24px;
    position: relative;
}

.card-reflection {
    position: absolute;
    inset: 0;
    border-radius: 24px;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 40%);
    pointer-events: none;
    z-index: 1;
}

.about-container {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 64px;
    position: relative;
    z-index: 2;
}

.about-header {
    text-align: center;
    max-width: 700px;
    margin: 0 auto;
}

.section-label {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    color: #60a5fa;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 16px;
    padding: 6px 14px;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 20px;
}

.about-heading {
    font-size: 42px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.15;
    margin: 0 0 16px;
}

.about-subtext {
    font-size: 17px;
    color: rgba(255,255,255,0.6);
    line-height: 1.7;
    margin: 0;
}

.about-card {
    padding: 72px 64px;
    overflow: hidden;
    min-height: 380px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
}

.about-card:hover {
    transform: translateY(-5px);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 20px 40px rgba(0,0,0,0.4);
}

.card-bg-effect {
    position: absolute;
    top: 0; right: 0;
    width: 60%;
    height: 100%;
    background-image: url('/images/about.png');
    background-size: cover;
    background-position: center right;
    opacity: 0.15;
    pointer-events: none;
    z-index: 0;
    mix-blend-mode: luminosity;
    transition: opacity 0.5s ease;
}
.about-card:hover .card-bg-effect { opacity: 0.25; }

.card-content {
    position: relative;
    z-index: 2;
    max-width: 560px;
}

.step-tag {
    display: inline-block;
    background: rgba(255, 255, 255, 0.05);
    color: #e2e8f0;
    font-size: 11px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 28px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
}

.step-title {
    font-size: 44px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 20px;
    line-height: 1.15;
}

.step-description {
    font-size: 16px;
    color: rgba(255,255,255,0.6);
    line-height: 1.7;
    margin: 0 0 36px;
}

.card-cta {
    display: inline-flex;
    align-items: center;
    font-size: 14px;
    font-weight: 700;
    color: #60a5fa;
    text-decoration: none;
    transition: all 0.3s ease;
    padding: 10px 20px;
    background: rgba(59, 130, 246, 0.1);
    border-radius: 12px;
    border: 1px solid rgba(59, 130, 246, 0.2);
}
.card-cta:hover { 
    background: rgba(59, 130, 246, 0.2);
    color: #ffffff; 
    transform: translateX(4px);
}

.about-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    overflow: hidden;
}

.stat-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 36px 24px;
    text-align: center;
    gap: 8px;
    position: relative;
    transition: background 0.3s ease;
}
.stat-box:hover { background: rgba(255,255,255,0.02); }

.stat-box + .stat-box::before {
    content: '';
    position: absolute;
    left: 0;
    top: 20%;
    height: 60%;
    width: 1px;
    background: rgba(255,255,255,0.1);
}

.stat-num {
    font-size: 44px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1;
    background: linear-gradient(180deg, #ffffff, #94a3b8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-label {
    font-size: 12px;
    font-weight: 600;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    letter-spacing: 1px;
}

@media (max-width: 900px) {
    .about-card { padding: 40px 32px; min-height: auto; }
    .step-title { font-size: 32px; }
    .about-heading { font-size: 32px; }
    .about-stats { grid-template-columns: repeat(2, 1fr); }
    .stat-box + .stat-box::before { display: none; }
    .stat-box { border: 1px solid rgba(255,255,255,0.05); }
}
</style>
