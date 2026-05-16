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
    TrendingUp, 
    Download,
    Calendar,
    ArrowUpRight
} from 'lucide-vue-next';

const props = defineProps<{
    report: any;
}>();

const exportReport = () => {
    window.open(route('reports.export'), '_blank');
};
</script>

<template>
    <AppLayout>
        <Head title="Platform Analytics" />

        <div class="space-y-8 max-w-[1600px] mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Platform Analytics</h1>
                    <p class="text-slate-500 font-medium mt-1">Global performance metrics and growth trends.</p>
                </div>
                
                <button @click="exportReport" class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 text-slate-200 border border-white/10 rounded-xl font-bold text-sm transition-all">
                    <Download class="w-4 h-4" />
                    Export Data (JSON)
                </button>
            </div>

            <!-- Global Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatsCard label="Total Platform Users" :value="report.overview.total_users" :icon="Users" color="blue" />
                <StatsCard label="Total Courses" :value="report.overview.total_courses" :icon="BookOpen" color="green" />
                <StatsCard label="Platform Enrollments" :value="report.overview.total_enrollments" :icon="TrendingUp" color="purple" />
                <StatsCard label="Active Conferences" :value="report.overview.active_meetings" :icon="Video" color="orange" trendLabel="Real-time" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- User Growth Chart Placeholder -->
                <Card title="User Growth" subtitle="New registrations over the last 30 days" class="lg:col-span-2">
                    <div class="h-64 flex items-end gap-1 px-2">
                        <div v-for="(day, idx) in report.user_growth" :key="idx" 
                             class="flex-1 bg-blue-600/20 hover:bg-blue-600/40 transition-all rounded-t-sm relative group"
                             :style="{ height: `${(day.count / Math.max(...report.user_growth.map(d => d.count))) * 100}%` }">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[8px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 font-bold">
                                {{ day.date }}: {{ day.count }}
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-4 text-[9px] font-black text-slate-600 uppercase tracking-widest px-2">
                        <span>{{ report.user_growth[report.user_growth.length-1]?.date }}</span>
                        <span>Today</span>
                    </div>
                </Card>

                <!-- Popular Courses -->
                <Card title="Popular Courses" subtitle="Highest enrollment counts">
                    <div class="space-y-4">
                        <div v-for="course in report.popular_courses" :key="course.id" 
                             class="flex items-center justify-between p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-blue-500/20 transition-all group cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-600/10 flex items-center justify-center text-blue-500">
                                    <BookOpen class="w-4 h-4" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-white truncate w-32">{{ course.title }}</p>
                                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">{{ course.enrollments_count }} students</p>
                                </div>
                            </div>
                            <ArrowUpRight class="w-3.5 h-3.5 text-slate-600 group-hover:text-blue-500 transition-colors" />
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
