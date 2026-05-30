<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<div class="checklist-item">
            {checked && (
                <CheckCircleFilled class="checklist-icon" />
            )}
            <span class="checklist-text">{text}</span>
        </div>
</template>

<style scoped>
.checklist-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}

.checklist-icon {
    font-size: 20px;
    color: var(--color-success);
    flex-shrink: 0;
}

.checklist-text {
    font-size: 16px;
    color: #64748b;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .checklist-text {
        font-size: 14px;
    }
}
</style>
