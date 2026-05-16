<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StatsCard from '@/components/StatsCard.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import { 
    Film, 
    Upload, 
    Clock, 
    FileText,
    Play
} from 'lucide-vue-next';

const props = defineProps<{
    report: any;
}>();
</script>

<template>
    <AppLayout>
        <Head title="Content Engagement" />

        <div class="space-y-8 max-w-[1400px] mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Content Activity</h1>
                    <p class="text-slate-500 font-medium mt-1">Monitor video processing and engagement metrics.</p>
                </div>
            </div>

            <!-- Editor Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <StatsCard label="Total Videos Managed" :value="report.video_stats.total_videos" :icon="Film" color="blue" />
                <StatsCard label="Currently Processing" :value="report.video_stats.processing_videos" :icon="Clock" color="orange" trendLabel="Awaiting transcoding" />
            </div>

            <Card title="Recent Video Activity" subtitle="Latest uploads across the platform">
                <DataTable :headers="['Video', 'Uploader', 'Duration', 'Status']" :items="report.recent_uploads">
                    <template #row="{ item }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-8 rounded bg-slate-800 border border-white/10 flex items-center justify-center text-slate-600">
                                    <Play class="w-4 h-4" />
                                </div>
                                <span class="text-sm font-bold text-white">{{ item.title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-slate-400">{{ item.uploader?.username }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ Math.floor(item.duration_seconds / 60) }}m</span>
                        </td>
                        <td class="px-6 py-4">
                            <span :class="item.status === 'active' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20'" 
                                  class="px-2 py-0.5 rounded-md border text-[9px] font-black uppercase tracking-widest">
                                {{ item.status }}
                            </span>
                        </td>
                    </template>
                </DataTable>
            </Card>
        </div>
    </AppLayout>
</template>
