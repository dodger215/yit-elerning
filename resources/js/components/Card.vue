<script setup lang="ts">
defineProps<{
    title?: string;
    subtitle?: string;
    padding?: boolean;
}>();
</script>

<template>
    <div class="bg-[#0d1117] border border-white/5 rounded-2xl overflow-hidden shadow-xl shadow-black/20">
        <div v-if="title || subtitle" class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
            <div>
                <h3 v-if="title" class="font-bold text-white tracking-tight">{{ title }}</h3>
                <p v-if="subtitle" class="text-xs text-slate-500 mt-0.5">{{ subtitle }}</p>
            </div>
            <slot name="header-action" />
        </div>
        <div :class="padding !== false ? 'p-6' : ''">
            <slot />
        </div>
        <div v-if="$slots.footer" class="px-6 py-4 bg-white/[0.01] border-t border-white/5">
            <slot name="footer" />
        </div>
    </div>
</template>
