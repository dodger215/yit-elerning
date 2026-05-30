<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<section class="how-it-works-section" id="how-it-works">
            <div class="how-it-works-container">
                <div class="how-it-works-header">
                    <h2 class="section-title">How We Work</h2>
                    <p class="section-subtitle">5 STAGES. ZERO GUESSWORK.</p>
                </div>

                <div class="how-it-works-grid">
                    {processSteps.map((step) => (
                        <div key={step.id} class="process-card">
                            <div class="card-header-row">
                                <span class="card-number">{step.id}</span>
                                <div class="card-icon-wrapper">
                                    <img
                                        src={step.iconPath}
                                        alt={`${step.tag} icon`}
                                        class="card-icon-img"
                                    />
                                </div>
                            </div>

                            <div class="card-tag">{step.tag}</div>

                            <h3 class="card-title">{step.title}</h3>
                            <p class="card-text">{step.text}</p>

                            <div class="card-footer-phase" style={{ color: step.color }}>
                                {step.phase}
                            </div>
                        </div>
                    ))}
                </div>

                <div class="stats-row">
                    {stats.map((stat, index) => (
                        <div key={index} class="stat-item">
                            <div class="stat-value">{stat.value}</div>
                            <div class="stat-label">{stat.label}</div>
                            <div class="stat-underline" style={{ background: stat.color }}></div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
</template>

<style scoped>
.how-it-works-section {
    padding: 100px 20px;
    background: transparent;
    /* Dark Navy / Adaptive */
    position: relative;
    overflow: hidden;
}

.how-it-works-container {
    max-width: 1400px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

/* Header */
.how-it-works-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-title {
    font-size: 48px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 16px;
    font-family: 'Montserrat', sans-serif;
}

.section-subtitle {
    font-size: 14px;
    font-weight: 600;
    color: #4A90E2;
    /* Blue/Grey from image */
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* Cards Grid */
.how-it-works-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
    margin-bottom: 80px;
}

/* Individual Card */
.process-card {
    background: #ffffff;
    /* Darker Navy */
    border-radius: 4px;
    padding: 30px 24px;
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 500px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e2e8f0;
    /* Subtle border */
    overflow: hidden;
}

.process-card:hover {
    
    border-color: rgba(0, 0, 0, 0.05);
}

/* Card Header: Number & Icon */
.card-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.card-number {
    font-size: 32px;
    font-weight: 700;
    color: rgba(0, 0, 0, 0.05);
    /* Faded number */
    font-family: 'Montserrat', sans-serif;
}

.card-icon-wrapper {
    width: 44px;
    /* Slightly larger wrapper */
    height: 44px;
    border-radius: 4px;
    /* Smoother radius */
    background: transparent;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
}

.card-icon-img {
    width: 20px;
    height: 20px;
    object-fit: contain;
    filter: brightness(0) invert(1);
    /* Ensure icons are white if they aren't already */
}

/* Tag */
.card-tag {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 6px 10px;
    background: rgba(0, 0, 0, 0.02);
    border-radius: 4px;
    display: inline-block;
    color: #64748b;
    margin-bottom: 20px;
    align-self: flex-start;
}

/* Title & Content */
.card-title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 16px;
    min-height: 50px;
    /* Align titles */
}

.card-text {
    font-size: 13px;
    line-height: 1.6;
    color: #475569;
    margin-bottom: 20px;
    /* Removed flex-grow to let margin-top: auto on footer handle spacing */
}

/* Footer (Phase Name) */
.card-footer-phase {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: auto;
    /* Push to bottom */
    padding-top: 30px;
    /* Removed border-top as per image */
}

/* Stats Row */
.stats-row {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
}

.stat-item {
    background: #ffffff;
    /* Consistent with cards */
    padding: 30px 50px;
    border-radius: 4px;
    text-align: center;
    border: 1px solid #e2e8f0;
    min-width: 200px;
}

.stat-value {
    font-size: 36px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
    font-family: 'Montserrat', sans-serif;
}

.stat-label {
    font-size: 12px;
    font-weight: 600;
    color: #4A90E2;
    /* Blue/Grey */
    text-transform: uppercase;
    letter-spacing: 1.5px;
    position: relative;
    display: inline-block;
}

.stat-underline {
    width: 40px;
    height: 3px;
    background: #4A90E2;
    /* Match label color or vary */
    margin: 12px auto 0;
    border-radius: 4px;
}

/* Responsive */
@media (max-width: 1200px) {
    .how-it-works-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 900px) {
    .how-it-works-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .how-it-works-grid {
        grid-template-columns: 1fr;
    }

    .section-title {
        font-size: 32px;
    }

    .process-card {
        min-height: auto;
    }
}
</style>
