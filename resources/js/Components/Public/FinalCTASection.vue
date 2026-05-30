<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<section class="cta-section">
    <div class="cta-container" data-aos="zoom-in">
        <div class="glassy-panel cta-inner">
            <div class="card-reflection"></div>
            <div class="cta-badge">🚀 Join 500+ learners today</div>
            <h2 class="cta-title">Ready to Earn<br/>While Mentoring?</h2>
            <p class="cta-sub">Turn your expertise into income. Help the next generation of tech professionals grow while building your own brand.</p>
            <div class="cta-actions">
                <Link href="/auth/register?role=instructor" class="cta-btn primary">Become a Tutor</Link>
                <Link href="/auth/register" class="cta-btn secondary">Start Learning</Link>
            </div>
        </div>
    </div>
</section>
</template>

<style scoped>
.cta-section {
    padding: 100px 20px;
    background: #060d1a;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cta-container {
    max-width: 800px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.glassy-panel {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.6) 0%, rgba(6, 13, 26, 0.8) 100%);
    backdrop-filter: blur(24px) saturate(150%);
    -webkit-backdrop-filter: blur(24px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 20px 40px rgba(0,0,0,0.4);
    border-radius: 32px;
    position: relative;
    padding: 80px 40px;
    overflow: hidden;
}

.card-reflection {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 50% 0%, rgba(96,165,250,0.15) 0%, transparent 60%);
    pointer-events: none;
    z-index: 1;
}

.cta-inner > * { position: relative; z-index: 2; }

.cta-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #93c5fd;
    background: rgba(59,130,246,0.15);
    border: 1px solid rgba(59,130,246,0.3);
    border-radius: 12px;
    padding: 8px 18px;
    margin-bottom: 24px;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
}

.cta-title {
    font-size: 56px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 20px;
    line-height: 1.15;
    letter-spacing: -1px;
}

.cta-sub {
    font-size: 17px;
    color: rgba(255,255,255,0.7);
    line-height: 1.65;
    margin: 0 auto 40px;
    max-width: 520px;
}

.cta-actions {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    justify-content: center;
}

.cta-btn {
    display: inline-flex;
    align-items: center;
    height: 56px;
    padding: 0 40px;
    font-size: 16px;
    font-weight: 700;
    border-radius: 16px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.cta-btn.primary {
    background: rgba(59, 130, 246, 0.9);
    color: #ffffff;
    border: 1px solid rgba(96, 165, 250, 0.5);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2), 0 8px 20px rgba(37,99,235,0.3);
}

.cta-btn.primary:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.3), 0 12px 24px rgba(37,99,235,0.4);
}

.cta-btn.secondary {
    background: rgba(255,255,255,0.05);
    color: #ffffff;
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
}

.cta-btn.secondary:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-2px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2), 0 8px 16px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .cta-section { padding: 70px 20px; }
    .glassy-panel { padding: 60px 24px; border-radius: 24px; }
    .cta-title { font-size: 38px; }
    .cta-actions { flex-direction: column; width: 100%; max-width: 300px; margin: 0 auto; }
    .cta-btn { justify-content: center; width: 100%; }
}
</style>
