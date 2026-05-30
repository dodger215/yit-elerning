<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<div class="process-card">
            <div class="process-icon-wrapper">
                {icon || (
                    <div class="process-step-number">{stepNumber}</div>
                )}
            </div>
            <h4 class="process-title">{title}</h4>
            <p class="process-description">{description}</p>
        </div>
</template>

<style scoped>
.process-card {
    flex: 1;
    min-width: 200px;
    padding: var(--spacing-lg);
    background: var(--bg-elevated);
    border: 1px solid var(--border-primary);
    border-radius: var(--radius-lg);
    text-align: center;
    transition: all var(--transition-base);
    cursor: pointer;
}

.process-card:hover {
    
    border-color: var(--color-primary);
}

.process-icon-wrapper {
    margin-bottom: var(--spacing-md);
}

.process-step-number {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    border-radius: var(--radius-full);
    background: var(--color-primary);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 700;
    transition: all var(--transition-base);
}

.process-card:hover .process-step-number {
    
}

.process-title {
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: var(--spacing-sm);
}

.process-description {
    font-size: 14px;
    color: #64748b;
    line-height: 1.6;
    margin: 0;
}

@media (max-width: 768px) {
    .process-card {
        min-width: auto;
    }

    .process-step-number {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }

    .process-title {
        font-size: 18px;
    }
}
</style>
