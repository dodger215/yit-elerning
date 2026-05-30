<script setup>
import { Link } from '@inertiajs/vue3';

const courses = [
    { title: 'Web Development', sub: 'Frontend & Backend', desc: 'Master HTML, CSS, JavaScript, Vue, React and Node.js through hands-on projects.', color: '#3b82f6' },
    { title: 'Data Science', sub: 'ML & Analytics', desc: 'Learn Python, Pandas, Machine Learning, and how to extract insights from data.', color: '#8b5cf6' },
    { title: 'Cybersecurity', sub: 'Security & Networking', desc: 'Understand threat landscapes, penetration testing, and secure system design.', color: '#10b981' },
    { title: 'UI/UX Design', sub: 'Design Systems', desc: 'Create intuitive, beautiful interfaces with Figma and human-centred design principles.', color: '#f59e0b' },
    { title: 'Cloud Computing', sub: 'AWS & DevOps', desc: 'Deploy and scale applications on the cloud with modern DevOps practices.', color: '#ef4444' },
    { title: 'Mobile Dev', sub: 'iOS & Android', desc: 'Build cross-platform mobile apps with Flutter and React Native.', color: '#06b6d4' },
];
</script>

<template>
<section class="courses-section" id="courses">
    <div class="courses-container">
        <div class="courses-header" data-aos="fade-up">
            <span class="section-label">WHAT WE OFFER</span>
            <h2 class="courses-title">Courses We Serve</h2>
            <p class="courses-description">Best-in-class curriculum for professional and technical career growth.</p>
        </div>
        <div class="courses-grid">
            <div v-for="(course, i) in courses" :key="course.title" 
                 class="course-card glassy-panel" 
                 :style="{ '--accent': course.color }"
                 data-aos="fade-up" :data-aos-delay="i * 50">
                <div class="card-reflection"></div>
                <div class="course-accent-bar"></div>
                <h3 class="course-title">{{ course.title }}</h3>
                <p class="course-sub">{{ course.sub }}</p>
                <p class="course-desc">{{ course.desc }}</p>
                <div class="course-link">Learn more →</div>
            </div>
        </div>
        <div class="courses-cta" data-aos="zoom-in" data-aos-delay="200">
            <Link href="/auth/register" class="cta-btn glassy-panel">Explore All Courses</Link>
        </div>
    </div>
</section>
</template>

<style scoped>
.courses-section {
    padding: 100px 20px;
    background: #060d1a;
    position: relative;
    overflow: hidden;
}

.courses-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 56px;
    position: relative;
    z-index: 2;
}

.courses-header { text-align: center; }

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

.courses-title {
    font-size: 48px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 16px;
}

.courses-description {
    font-size: 16px;
    color: rgba(255,255,255,0.6);
    font-weight: 500;
    margin: 0;
}

.courses-grid {
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

.course-card {
    border-radius: 20px;
    padding: 36px 28px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.4s ease;
}

.course-card > * { position: relative; z-index: 2; }

.course-card:hover {
    border-color: var(--accent);
    transform: translateY(-8px);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 15px 35px rgba(0,0,0,0.4);
}

.course-accent-bar {
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 3px;
    background: var(--accent);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 3;
}

.course-card:hover .course-accent-bar { opacity: 1; }

.course-title {
    font-size: 20px;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 6px;
}

.course-sub {
    font-size: 12px;
    font-weight: 700;
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0 0 16px;
}

.course-desc {
    font-size: 14px;
    color: rgba(255,255,255,0.6);
    line-height: 1.65;
    margin: 0;
    flex-grow: 1;
}

.course-link {
    font-size: 13px;
    font-weight: 700;
    color: var(--accent);
    margin-top: 24px;
    cursor: pointer;
    opacity: 0.7;
    transition: all 0.3s ease;
}
.course-card:hover .course-link { 
    opacity: 1; 
    transform: translateX(4px);
}

.courses-cta { text-align: center; }

.cta-btn {
    display: inline-flex;
    align-items: center;
    height: 52px;
    padding: 0 40px;
    background: rgba(59, 130, 246, 0.1);
    color: #60a5fa;
    font-size: 15px;
    font-weight: 700;
    border-radius: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.cta-btn:hover { 
    background: rgba(59, 130, 246, 0.2); 
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 10px 20px rgba(0,0,0,0.3);
}

@media (max-width: 900px) {
    .courses-grid { grid-template-columns: 1fr; }
    .courses-title { font-size: 36px; }
}
</style>
