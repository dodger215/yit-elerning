<script setup>
import { Link } from '@inertiajs/vue3';

const steps = [
    { num: '01', stage: 'Stage 1', title: 'Discovery', text: 'We assess your background, goals, and learning style to craft the most effective starting point.', icon: '/images/step-1.png' },
    { num: '02', stage: 'Stage 2', title: 'Planning', text: 'A personalised roadmap is built — covering tools, timelines, and milestones tailored just for you.', icon: '/images/step-1.png' },
    { num: '03', stage: 'Stage 3', title: 'Learning', text: 'Dive into structured, project-based modules guided by industry experts and mentors.', icon: '/images/step-1.png' },
    { num: '04', stage: 'Stage 4', title: 'Practice', text: 'Apply your skills to real-world projects and challenges in a collaborative environment.', icon: '/images/step-1.png' },
    { num: '05', stage: 'Stage 5', title: 'Launch', text: 'Graduate ready to enter the tech industry with a portfolio and professional connections.', icon: '/images/step-1.png' },
];
</script>

<template>
<section class="how-section" id="how-it-works">
    <div class="how-container">
        <div class="how-header" data-aos="fade-up">
            <span class="section-label">OUR PROCESS</span>
            <h2 class="how-title">How We Work</h2>
            <p class="how-sub">5 stages. Zero guesswork.</p>
        </div>

        <div class="steps-grid">
            <div v-for="(step, i) in steps" :key="step.num" 
                 class="step-card glassy-panel" 
                 data-aos="fade-up" 
                 :data-aos-delay="i * 100">
                <div class="card-reflection"></div>
                <div class="step-top">
                    <span class="step-num">{{ step.num }}</span>
                    <img :src="step.icon" :alt="step.title" class="step-icon" />
                </div>
                <div class="step-stage">{{ step.stage }}</div>
                <h3 class="step-title">{{ step.title }}</h3>
                <p class="step-text">{{ step.text }}</p>
                <div class="step-footer">Phase {{ step.num }}</div>
            </div>
        </div>
    </div>
</section>
</template>

<style scoped>
.how-section {
    padding: 100px 20px;
    background: #060d1a;
    position: relative;
    overflow: hidden;
}

.how-container {
    max-width: 1300px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 60px;
    position: relative;
    z-index: 2;
}

.how-header { text-align: center; }

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

.how-title {
    font-size: 48px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 12px;
}

.how-sub {
    font-size: 15px;
    font-weight: 600;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin: 0;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
}

.glassy-panel {
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(24px) saturate(150%);
    -webkit-backdrop-filter: blur(24px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 8px 24px rgba(0,0,0,0.2);
    border-radius: 20px;
    position: relative;
}

.card-reflection {
    position: absolute;
    inset: 0;
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, transparent 40%);
    pointer-events: none;
    z-index: 1;
}

.step-card {
    padding: 28px 22px;
    display: flex;
    flex-direction: column;
    min-height: 340px;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.4s ease;
}

.step-card:hover {
    transform: translateY(-8px);
    border-color: rgba(96, 165, 250, 0.4);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 15px 35px rgba(0,0,0,0.4);
}

.step-card > * { position: relative; z-index: 2; }

.step-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
}

.step-num {
    font-size: 32px;
    font-weight: 800;
    color: rgba(255,255,255,0.1);
    font-family: 'Montserrat', sans-serif;
    transition: color 0.3s ease;
}
.step-card:hover .step-num { color: rgba(96, 165, 250, 0.3); }

.step-icon {
    width: 44px;
    height: 44px;
    object-fit: contain;
    opacity: 0.6;
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.step-card:hover .step-icon { opacity: 1; transform: scale(1.1); }

.step-stage {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 5px 12px;
    background: rgba(255,255,255,0.05);
    border-radius: 6px;
    color: rgba(255,255,255,0.7);
    margin-bottom: 16px;
    align-self: flex-start;
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
}

.step-title {
    font-size: 20px;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 14px;
}

.step-text {
    font-size: 14px;
    line-height: 1.65;
    color: rgba(255,255,255,0.6);
    margin: 0;
    flex-grow: 1;
}

.step-footer {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255,255,255,0.3);
    margin-top: 24px;
    padding-top: 18px;
    border-top: 1px solid rgba(255,255,255,0.08);
}

@media (max-width: 1200px) { .steps-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 900px) { .steps-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .steps-grid { grid-template-columns: 1fr; } .how-title { font-size: 32px; } .step-card { min-height: auto; } }
</style>
