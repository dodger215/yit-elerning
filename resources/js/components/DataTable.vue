<script setup lang="ts">
defineProps<{
    headers: string[];
    items: any[];
    loading?: boolean;
}>();
</script>

<template>
    <div class="overflow-x-auto scrollbar-hide border border-white/5 rounded-2xl bg-[#0d1117] shadow-xl shadow-black/20">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/[0.02] border-b border-white/5">
                    <th v-for="header in headers" :key="header" class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                        {{ header }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <tr v-if="loading" v-for="i in 3" :key="i" class="animate-pulse">
                    <td v-for="h in headers" :key="h" class="px-6 py-4">
                        <div class="h-4 bg-white/5 rounded w-full"></div>
                    </td>
                </tr>
                <tr v-else-if="items.length === 0">
                    <td :colspan="headers.length" class="px-6 py-12 text-center text-slate-500 italic text-sm">
                        No data available in this view.
                    </td>
                </tr>
                <tr v-for="(item, idx) in items" :key="idx" class="hover:bg-white/[0.01] transition-colors group">
                    <slot name="row" :item="item" />
                </tr>
            </tbody>
        </table>
    </div>
</template>
