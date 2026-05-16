<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StatsCard from '@/components/StatsCard.vue';
import Card from '@/components/Card.vue';
import { 
    BookOpen, 
    Play, 
    Video, 
    Award,
    Clock,
    CheckCircle2
} from 'lucide-vue-next';

const props = defineProps<{
    report: any;
}>();
</script>

<template>
    <AppLayout>
        <Head title="My Progress" />

        <div class="space-y-8 max-w-[1200px] mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">My Progress</h1>
                    <p class="text-slate-500 font-medium mt-1">Track your learning journey and academic performance.</p>
                </div>
            </div>

            <!-- Student Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <StatsCard label="Enrolled Courses" :value="report.learning_stats.enrolled_courses" :icon="BookOpen" color="blue" />
                <StatsCard label="Overall Progress" :value="report.learning_stats.overall_progress + '%'" :icon="Play" color="green" />
                <StatsCard label="Academic Score" :value="report.learning_stats.overall_score" :icon="Award" color="orange" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Course Progress List -->
                <Card title="Active Courses" subtitle="Your current learning track">
                    <div class="space-y-6">
                        <div v-for="(course, idx) in report.my_courses" :key="idx" class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-blue-500">
                                        <CheckCircle2 class="w-4 h-4" v-if="course.progress_percentage === 100" />
                                        <Play class="w-4 h-4" v-else />
                                    </div>
                                    <h4 class="text-sm font-bold text-white">{{ course.course_title }}</h4>
                                </div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ course.progress_percentage }}%</span>
                            </div>
                            
                            <div class="relative w-full h-2 bg-white/5 rounded-full overflow-hidden">
                                <div class="absolute h-full bg-blue-600 rounded-full transition-all duration-1000 ease-out" 
                                     :style="{ width: `${course.progress_percentage}%` }"></div>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div class="flex items-center gap-4">
                                    <span class="text-[9px] font-bold text-slate-500 uppercase flex items-center gap-1">
                                        <Award class="w-3 h-3" />
                                        Score: {{ course.average_score }}
                                    </span>
                                    <span class="text-[9px] font-bold text-slate-500 uppercase flex items-center gap-1">
                                        <Clock class="w-3 h-3" />
                                        {{ course.status }}
                                    </span>
                                </div>
                                <button class="text-[9px] font-black text-blue-500 uppercase tracking-widest hover:text-blue-400 transition-colors">
                                    Resume Lesson
                                </button>
                            </div>
                        </div>
                    </div>
                </Card>

                <!-- Upcoming Sessions -->
                <Card title="Upcoming Live Sessions" subtitle="Don't miss your scheduled meetings">
                    <div v-if="report.learning_stats.upcoming_meetings === 0" class="py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4">
                            <Video class="w-6 h-6 text-slate-600" />
                        </div>
                        <p class="text-xs text-slate-500 font-medium">No upcoming sessions scheduled.</p>
                    </div>
                    <div v-else class="space-y-4">
                        <!-- Meeting items would go here -->
                    </div>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
