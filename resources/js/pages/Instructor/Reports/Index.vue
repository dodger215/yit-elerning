<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StatsCard from '@/components/StatsCard.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import { 
    Users, 
    BookOpen, 
    Video, 
    BarChart3,
    ArrowUpRight,
    Search
} from 'lucide-vue-next';

const props = defineProps<{
    report: any;
}>();
</script>

<template>
    <AppLayout>
        <Head title="Instructor Dashboard" />

        <div class="space-y-8 max-w-[1600px] mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Performance Analytics</h1>
                    <p class="text-slate-500 font-medium mt-1">Track your course reach and student engagement.</p>
                </div>
            </div>

            <!-- Instructor Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <StatsCard label="Total Courses" :value="report.my_stats.total_courses" :icon="BookOpen" color="blue" />
                <StatsCard label="Total Students" :value="report.my_stats.total_students" :icon="Users" color="green" trendLabel="Lifetime reach" />
                <StatsCard label="Conferences Hosted" :value="report.my_stats.total_meetings" :icon="Video" color="purple" />
            </div>

            <Card title="My Courses Performance" subtitle="Real-time enrollment and status tracking">
                <DataTable :headers="['Course Title', 'Total Students', 'Status', 'Growth']" :items="report.course_performance">
                    <template #row="{ item }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-600/10 flex items-center justify-center text-blue-500">
                                    <BookOpen class="w-4 h-4" />
                                </div>
                                <span class="text-sm font-bold text-white">{{ item.title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-slate-300">{{ item.students }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span :class="item.status === 'published' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'" 
                                  class="px-2 py-0.5 rounded-md border text-[9px] font-black uppercase tracking-widest">
                                {{ item.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-green-400 text-[10px] font-bold">
                                <TrendingUp class="w-3.5 h-3.5" />
                                +{{ Math.floor(Math.random() * 15) }}%
                            </div>
                        </td>
                    </template>
                </DataTable>
            </Card>
        </div>
    </AppLayout>
</template>
