<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<div class="feature-card">
            {label && <div class="feature-label">{label}</div>}
            <div class="feature-icon">{icon}</div>
            <h4 class="feature-title">{title}</h4>
            <p class="feature-description">{description}</p>
        </div>
</template>

<style scoped>
.feature-card {
    padding: var(--spacing-xl);
    background: var(--bg-elevated);
    border: 1px solid var(--border-primary);
    border-radius: var(--radius-lg);
    text-align: center;
    transition: all var(--transition-base);
    position: relative;
}

.feature-card:hover {
    
    border-color: var(--color-primary);
}

.feature-label {
    position: absolute;
    top: var(--spacing-md);
    left: var(--spacing-md);
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.feature-icon {
    font-size: 40px;
    margin-bottom: var(--spacing-md);
    color: var(--color-primary);
}

.feature-title {
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: var(--spacing-sm);
}

.feature-description {
    font-size: 14px;
    color: #64748b;
    line-height: 1.6;
    margin: 0;
}

@media (max-width: 768px) {
    .feature-card {
        padding: var(--spacing-lg);
    }

    .feature-icon {
        font-size: 32px;
    }

    .feature-title {
        font-size: 18px;
    }
}
</style>
