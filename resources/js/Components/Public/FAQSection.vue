<script setup>
import { ref } from 'vue';

const faqs = [
    { q: 'What is Youth in Tech?', a: 'Youth in Tech (YIT) is a premier e-learning platform dedicated to empowering the next generation of tech professionals through expert mentorship, structured curricula, and real-world project experience.' },
    { q: 'Who are the instructors?', a: 'Our instructors are seasoned industry professionals with years of real-world experience at top tech companies. Each mentor is rigorously vetted to ensure the highest quality of instruction.' },
    { q: 'How do I enrol in a course?', a: 'Simply register for a free account, browse our course catalogue, and click "Enrol". Most courses are available immediately after sign-up.' },
    { q: 'Are there certificates?', a: 'Yes! Upon completing a course, you\'ll receive an industry-recognised certificate that you can add to your LinkedIn profile or CV.' },
    { q: 'Can I learn at my own pace?', a: 'Absolutely. All course content is recorded and available on-demand. Live sessions are also scheduled regularly so you can participate in real time.' },
    { q: 'Is there a free trial?', a: 'We offer a 7-day free trial for all new students. No credit card required — explore the platform before committing.' },
];

const openIndex = ref(null);
const toggle = (i) => { openIndex.value = openIndex.value === i ? null : i; };
</script>

<template>
<section class="faq-section" id="faq">
    <div class="faq-container">
        <div class="faq-header" data-aos="fade-up">
            <span class="section-label">FAQ</span>
            <h2 class="faq-title">Frequently Asked Questions</h2>
            <p class="faq-sub">Got a question? We've probably answered it below.</p>
        </div>

        <div class="faq-list">
            <div v-for="(faq, i) in faqs" :key="i" 
                 :class="['faq-item glassy-panel', { open: openIndex === i }]"
                 data-aos="fade-up" :data-aos-delay="i * 50">
                <div class="card-reflection"></div>
                <button class="faq-question" @click="toggle(i)">
                    <span>{{ faq.q }}</span>
                    <span class="faq-chevron">{{ openIndex === i ? '−' : '+' }}</span>
                </button>
                <div class="faq-answer" v-show="openIndex === i">
                    <p>{{ faq.a }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
</template>

<style scoped>
.faq-section {
    padding: 100px 20px;
    background: #060d1a;
    position: relative;
    overflow: hidden;
}

.faq-container {
    max-width: 800px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 56px;
    position: relative;
    z-index: 2;
}

.faq-header { text-align: center; }

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

.faq-title {
    font-size: 42px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 14px;
}

.faq-sub {
    font-size: 16px;
    color: rgba(255,255,255,0.6);
    margin: 0;
}

.faq-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.glassy-panel {
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(16px) saturate(150%);
    -webkit-backdrop-filter: blur(16px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 16px;
    position: relative;
    transition: border-color 0.3s ease, transform 0.3s ease;
}

.card-reflection {
    position: absolute;
    inset: 0;
    border-radius: 16px;
    background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, transparent 30%);
    pointer-events: none;
    z-index: 1;
}

.faq-item:hover {
    border-color: rgba(255,255,255,0.2);
    transform: translateX(4px);
}

.faq-item.open {
    border-color: rgba(96, 165, 250, 0.5);
    background: rgba(15, 23, 42, 0.6);
}

.faq-question {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 28px;
    background: transparent;
    border: none;
    cursor: pointer;
    text-align: left;
    font-size: 17px;
    font-weight: 600;
    color: #ffffff;
    gap: 16px;
    position: relative;
    z-index: 2;
    transition: color 0.2s ease;
}

.faq-chevron {
    font-size: 24px;
    font-weight: 300;
    color: rgba(255,255,255,0.4);
    flex-shrink: 0;
    line-height: 1;
    transition: color 0.2s ease, transform 0.3s ease;
}

.faq-item.open .faq-chevron { 
    color: #60a5fa; 
    transform: rotate(180deg);
}

.faq-answer {
    border-top: 1px solid rgba(255,255,255,0.06);
    position: relative;
    z-index: 2;
}

.faq-answer p {
    padding: 24px 28px;
    font-size: 15px;
    color: rgba(255,255,255,0.6);
    line-height: 1.7;
    margin: 0;
}

@media (max-width: 768px) {
    .faq-section { padding: 60px 16px; }
    .faq-title { font-size: 30px; }
    .faq-question { padding: 20px 24px; font-size: 15px; }
    .faq-answer p { padding: 20px 24px; }
}
</style>
