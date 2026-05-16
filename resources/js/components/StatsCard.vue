<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    label: string;
    value: string | number;
    icon?: any;
    trend?: number;
    trendLabel?: string;
    color?: 'blue' | 'green' | 'purple' | 'orange' | 'red';
}>();

const colorClasses = computed(() => {
    const map = {
        blue: 'bg-blue-500/10 text-blue-500 border-blue-500/20 shadow-blue-500/10',
        green: 'bg-green-500/10 text-green-500 border-green-500/20 shadow-green-500/10',
        purple: 'bg-purple-500/10 text-purple-500 border-purple-500/20 shadow-purple-500/10',
        orange: 'bg-orange-500/10 text-orange-500 border-orange-500/20 shadow-orange-500/10',
        red: 'bg-red-500/10 text-red-500 border-red-500/20 shadow-red-500/10',
    };
    return map[props.color || 'blue'];
});
</script>

<template>
    <div class="bg-[#0d1117] border border-white/5 rounded-2xl p-6 relative overflow-hidden group shadow-xl shadow-black/20">
        <!-- Background Gradient Glow -->
        <div class="absolute -right-4 -top-4 w-24 h-24 blur-3xl opacity-0 group-hover:opacity-20 transition-opacity" :class="colorClasses.split(' ')[1]"></div>
        
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">{{ label }}</p>
                <h3 class="text-2xl font-black text-white tracking-tight">{{ value }}</h3>
                
                <div v-if="trend !== undefined" class="flex items-center gap-1.5 mt-2">
                    <span :class="trend >= 0 ? 'text-green-400' : 'text-red-400'" class="text-[10px] font-bold flex items-center">
                        {{ trend >= 0 ? '+' : '' }}{{ trend }}%
                    </span>
                    <span class="text-[10px] text-slate-500 font-medium">{{ trendLabel || 'vs last month' }}</span>
                </div>
            </div>

            <div v-if="icon" class="w-12 h-12 rounded-xl border flex items-center justify-center transition-transform group-hover:scale-110 shadow-lg" :class="colorClasses">
                <component :is="icon" class="w-6 h-6" />
            </div>
        </div>
    </div>
</template>
