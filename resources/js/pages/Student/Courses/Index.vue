<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import { 
    Play, 
    Clock, 
    BookOpen, 
    CheckCircle2, 
    ArrowRight,
    Search
} from 'lucide-vue-next';

const props = defineProps<{
    enrolledCourses: any;
}>();
</script>

<template>
    <AppLayout>
        <Head title="My Learning" />

        <div class="space-y-8 max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">My Classroom</h1>
                    <p class="text-slate-500 font-medium mt-1">Continue your learning journey where you left off.</p>
                </div>
                
                <div class="relative w-full sm:w-64">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input type="text" placeholder="Search my courses..." 
                           class="bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-xs w-full focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                </div>
            </div>

            <div v-if="enrolledCourses.data.length === 0" class="py-20 text-center space-y-6">
                <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto">
                    <BookOpen class="w-10 h-10 text-slate-700" />
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">No courses enrolled yet</h3>
                    <p class="text-slate-500 text-sm mt-2">Explore our catalog to find your next learning adventure.</p>
                </div>
                <Link :href="route('courses.index')" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-blue-600/20">
                    Explore Catalog
                </Link>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="enrollment in enrolledCourses.data" :key="enrollment.id" 
                     class="group bg-[#0d1117] border border-white/5 rounded-3xl overflow-hidden shadow-xl shadow-black/20 hover:border-blue-500/30 transition-all">
                    
                    <div class="aspect-video bg-slate-800 relative">
                        <img v-if="enrollment.course.thumbnail_url" :src="enrollment.course.thumbnail_url" class="w-full h-full object-cover">
                        <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-600/10 to-purple-600/10">
                            <Play class="w-12 h-12 text-blue-500/20" />
                        </div>
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center shadow-2xl">
                                <Play class="w-6 h-6 text-white fill-current" />
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="space-y-1">
                            <h3 class="text-sm font-bold text-white line-clamp-1 group-hover:text-blue-400 transition-colors">{{ enrollment.course.title }}</h3>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Instructor: {{ enrollment.course.instructor?.username }}</p>
                        </div>

                        <!-- Progress Bar -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-widest">
                                <span class="text-slate-500">Course Progress</span>
                                <span class="text-blue-500">{{ enrollment.progress_percentage }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" :style="{ width: `${enrollment.progress_percentage}%` }"></div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-[9px] font-black text-slate-500 uppercase tracking-widest">
                                <CheckCircle2 class="w-3.5 h-3.5" :class="enrollment.progress_percentage === 100 ? 'text-green-500' : 'text-slate-700'" />
                                {{ enrollment.status }}
                            </div>
                            <Link :href="route('courses.show', enrollment.course.id)" 
                                  class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-blue-600 text-slate-300 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                Continue
                                <ArrowRight class="w-3.5 h-3.5" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
