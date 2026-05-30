<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<div class="metric-card">
            {icon && <div class="metric-icon">{icon}</div>}
            <div class="metric-value">{value}</div>
            <div class="metric-label">{label}</div>
        </div>
</template>

<style scoped>
.metric-card {
    flex: 1;
    min-width: 150px;
    padding: var(--spacing-xl);
    background: transparent;
    border: 2px solid var(--border-primary);
    border-radius: var(--radius-lg);
    text-align: center;
    transition: all var(--transition-base);
}

.metric-card:hover {
    border-color: var(--color-primary);
    
}

.metric-icon {
    font-size: 32px;
    margin-bottom: var(--spacing-md);
    color: var(--color-primary);
}

.metric-value {
    font-size: 48px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: var(--spacing-sm);
    line-height: 1;
}

.metric-label {
    font-size: 14px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1px;
}

@media (max-width: 768px) {
    .metric-card {
        min-width: auto;
        padding: var(--spacing-lg);
    }

    .metric-value {
        font-size: 36px;
    }

    .metric-label {
        font-size: 12px;
    }
}
</style>
