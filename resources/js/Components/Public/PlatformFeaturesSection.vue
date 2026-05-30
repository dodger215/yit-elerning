<script setup>
import { Link } from '@inertiajs/vue3';

const features = [
    { icon: '🎥', tag: 'Live', title: 'Live & Recorded Sessions', desc: 'Attend real-time classes or revisit recorded sessions at your pace. Never miss a lesson again.', bottom: 'Streaming' },
    { icon: '🤝', tag: 'Popular', title: '1-on-1 Mentorship', desc: 'Get direct access to industry professionals who will guide your progress, review your work, and answer your questions.', bottom: 'Mentorship', featured: true },
    { icon: '📊', tag: 'Analytics', title: 'Progress Tracking', desc: 'Visualise your growth with detailed analytics. Stay motivated with milestones, streaks, and feedback loops.', bottom: 'Insights' },
    { icon: '🌐', tag: 'Community', title: 'Global Peer Network', desc: 'Collaborate with learners worldwide. Build relationships, share knowledge, and grow together.', bottom: 'Networking' },
    { icon: '🏆', tag: 'Certified', title: 'Industry Certificates', desc: 'Earn certifications recognised by top companies. Add real credentials to your professional portfolio.', bottom: 'Credentials' },
    { icon: '⚡', tag: 'Projects', title: 'Real-World Projects', desc: 'Work on actual industry projects from day one. Build a portfolio that stands out to employers.', bottom: 'Portfolio' },
];
</script>

<template>
<section class="features-section" id="features">
    <div class="features-container">
        <div class="features-header" data-aos="fade-up">
            <span class="section-label">PLATFORM FEATURES</span>
            <h2 class="features-title">Everything you need<br/>to succeed.</h2>
        </div>

        <div class="features-grid">
            <div v-for="(feature, i) in features" :key="feature.title" 
                 :class="['feature-card glassy-panel', { featured: feature.featured }]"
                 data-aos="fade-up" :data-aos-delay="i * 100">
                <div class="card-reflection"></div>
                <div class="feature-top">
                    <div class="feature-tags">
                        <span :class="['feature-tag', { popular: feature.tag === 'Popular' }]">{{ feature.tag }}</span>
                    </div>
                    <span class="feature-icon">{{ feature.icon }}</span>
                </div>
                <h3 class="feature-title">{{ feature.title }}</h3>
                <p class="feature-desc">{{ feature.desc }}</p>
                <div class="feature-bottom">
                    <span class="feature-bottom-label">{{ feature.bottom }}</span>
                </div>
            </div>
        </div>
    </div>
</section>
</template>

<style scoped>
.features-section {
    padding: 100px 40px;
    background: #060d1a;
    position: relative;
    overflow: hidden;
}

.features-container {
    max-width: 1300px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 56px;
    position: relative;
    z-index: 2;
}

.features-header { text-align: center; }

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

.features-title {
    font-size: 48px;
    font-weight: 800;
    color: #ffffff;
    margin: 0;
    line-height: 1.15;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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

.feature-card {
    padding: 32px 28px;
    display: flex;
    flex-direction: column;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.4s ease;
}

.feature-card > * { position: relative; z-index: 2; }

.feature-card:hover { 
    border-color: rgba(96, 165, 250, 0.4); 
    transform: translateY(-8px);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 15px 35px rgba(0,0,0,0.4);
}

.feature-card.featured {
    background: linear-gradient(145deg, rgba(26, 42, 74, 0.6), rgba(13, 26, 48, 0.6));
    border-color: rgba(96, 165, 250, 0.3);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 10px 30px rgba(59, 130, 246, 0.15);
}
.feature-card.featured:hover {
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3), 0 20px 40px rgba(59, 130, 246, 0.2);
}

.feature-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.feature-tags { display: flex; gap: 8px; }

.feature-tag {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.7);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
}

.feature-tag.popular {
    background: rgba(59,130,246,0.15);
    border-color: rgba(59,130,246,0.3);
    color: #93c5fd;
}

.feature-icon { 
    font-size: 28px; 
    opacity: 0.8; 
    transition: transform 0.3s ease;
}
.feature-card:hover .feature-icon { transform: scale(1.1) rotate(5deg); }

.feature-title {
    font-size: 20px;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 14px;
}

.feature-desc {
    font-size: 14px;
    color: rgba(255,255,255,0.6);
    line-height: 1.7;
    margin: 0;
    flex-grow: 1;
}

.feature-bottom {
    margin-top: 28px;
    padding-top: 18px;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.feature-bottom-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.3);
}

@media (max-width: 1024px) {
    .features-section { padding: 80px 24px; }
    .features-grid { grid-template-columns: 1fr; max-width: 600px; margin: 0 auto; }
    .features-title { font-size: 36px; }
}
@media (max-width: 768px) {
    .features-section { padding: 60px 16px; }
    .features-title { font-size: 28px; }
}
</style>
