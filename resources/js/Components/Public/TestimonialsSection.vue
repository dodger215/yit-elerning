<script setup>
import { Link } from '@inertiajs/vue3';

const testimonials = [
    { name: 'Ama Owusu', role: 'Frontend Developer', text: 'YIT transformed my career. The mentorship programme alone is worth every penny. I landed my first dev job 3 months after completing the course.' },
    { name: 'Kwame Asante', role: 'Data Analyst', text: 'The structured curriculum and real-world projects made all the difference. I went from zero Python knowledge to building ML models in 6 months.' },
    { name: 'Efua Mensah', role: 'UX Designer', text: 'I loved how the platform blended theory with practice. The instructors are genuinely passionate and always available.' },
    { name: 'Kofi Darko', role: 'Backend Engineer', text: 'The community here is incredible. I made connections that led directly to my current job. YIT is the real deal.' },
    { name: 'Abena Boateng', role: 'Cloud Architect', text: 'As someone switching careers, YIT provided the perfect structure. The roadmap removed all the overwhelm and kept me on track.' },
    { name: 'Nana Yaw Ofori', role: 'Mobile Developer', text: 'The live sessions are incredibly interactive. You get direct feedback from experts who have shipped real products in the industry.' },
    { name: 'Adwoa Sarpong', role: 'Product Manager', text: 'YIT doesn\'t just teach you tech — it teaches you how to think like a tech professional. That mindset shift was priceless.' },
    { name: 'Yaw Barimah', role: 'Cybersecurity Analyst', text: 'The security programme is thorough and practical. I passed my CEH exam on the first attempt thanks to YIT\'s preparation.' },
];

const row1 = testimonials.slice(0, 4);
const row2 = testimonials.slice(4, 8);
</script>

<template>
<section class="testimonials-section">
    <div class="testimonials-container">
        <div class="testimonials-header-row" data-aos="fade-up">
            <div class="testimonials-header-left">
                <span class="section-label">WHAT PEOPLE SAY</span>
                <h2 class="testimonials-title">Testimonials</h2>
            </div>
            <div class="testimonials-header-right">
                <p class="testimonials-subtitle">Students and tutors trust YIT to unlock their full potential.</p>
            </div>
        </div>

        <div class="marquee-wrapper" data-aos="fade-in" data-aos-delay="200">
            <div class="marquee-row">
                <div class="marquee-track scroll-left">
                    <div v-for="(t, i) in [...row1, ...row1]" :key="'r1-' + i" class="testimonial-card glassy-panel">
                        <div class="card-reflection"></div>
                        <div class="testimonial-header">
                            <div class="avatar">{{ t.name.charAt(0) }}</div>
                            <div class="testimonial-info">
                                <h4 class="t-name">{{ t.name }}</h4>
                                <p class="t-role">{{ t.role }}</p>
                            </div>
                        </div>
                        <p class="t-text">"{{ t.text }}"</p>
                    </div>
                </div>
            </div>
            <div class="marquee-row">
                <div class="marquee-track scroll-right">
                    <div v-for="(t, i) in [...row2, ...row2]" :key="'r2-' + i" class="testimonial-card glassy-panel">
                        <div class="card-reflection"></div>
                        <div class="testimonial-header">
                            <div class="avatar">{{ t.name.charAt(0) }}</div>
                            <div class="testimonial-info">
                                <h4 class="t-name">{{ t.name }}</h4>
                                <p class="t-role">{{ t.role }}</p>
                            </div>
                        </div>
                        <p class="t-text">"{{ t.text }}"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</template>

<style scoped>
.testimonials-section {
    padding: 100px 0;
    background: #060d1a;
    overflow: hidden;
    position: relative;
}

.testimonials-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
    display: flex;
    flex-direction: column;
    gap: 60px;
    position: relative;
    z-index: 2;
}

.testimonials-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.testimonials-header-left { max-width: 500px; }

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

.testimonials-title {
    font-size: 56px;
    font-weight: 800;
    color: #ffffff;
    margin: 0;
    line-height: 1.1;
}

.testimonials-header-right { max-width: 360px; text-align: right; }

.testimonials-subtitle {
    font-size: 16px;
    color: rgba(255,255,255,0.6);
    line-height: 1.65;
    margin: 0;
}

.marquee-wrapper {
    display: flex;
    flex-direction: column;
    gap: 20px;
    mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
}

.marquee-row { overflow: hidden; }

.marquee-track {
    display: flex;
    gap: 20px;
    width: max-content;
}

.scroll-left { animation: scroll-left 50s linear infinite; }
.scroll-right { animation: scroll-right 50s linear infinite; }

@keyframes scroll-left {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}
@keyframes scroll-right {
    from { transform: translateX(-50%); }
    to { transform: translateX(0); }
}

.marquee-wrapper:hover .marquee-track { animation-play-state: paused; }

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

.testimonial-card {
    width: 400px;
    flex-shrink: 0;
    padding: 32px;
    transition: transform 0.3s ease, border-color 0.3s ease;
}

.testimonial-card > * { position: relative; z-index: 2; }

.testimonial-card:hover {
    transform: translateY(-4px);
    border-color: rgba(255,255,255,0.2);
}

.testimonial-header {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-bottom: 20px;
}

.avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);
}

.testimonial-info { flex: 1; }

.t-name {
    font-size: 16px;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 4px;
}

.t-role {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin: 0;
}

.t-text {
    font-size: 15px;
    line-height: 1.65;
    color: rgba(255,255,255,0.7);
    margin: 0;
    font-style: italic;
}

@media (max-width: 768px) {
    .testimonials-header-row { flex-direction: column; align-items: flex-start; gap: 16px; }
    .testimonials-header-right { text-align: left; }
    .testimonials-title { font-size: 40px; }
    .testimonial-card { width: 320px; padding: 24px; }
}
</style>
