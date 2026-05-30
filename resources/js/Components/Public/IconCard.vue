<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<div class="icon-card">
            <div class="icon-circle">
                {icon || <span>AI</span>}
            </div>
            {label && <p class="icon-label">{label}</p>}
        </div>
</template>

<style scoped>
.icon-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-sm);
}

.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: var(--radius-full);
    background: var(--bg-elevated);
    border: 2px solid var(--border-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
    transition: all var(--transition-base);
    cursor: pointer;
}

.icon-circle:hover {
    background: var(--color-primary);
    border-color: var(--color-primary);
    color: #FFFFFF;
    
}

.icon-label {
    font-size: 14px;
    color: #64748b;
    margin: 0;
    text-align: center;
}

@media (max-width: 768px) {
    .icon-circle {
        width: 64px;
        height: 64px;
        font-size: 16px;
    }
}
</style>
