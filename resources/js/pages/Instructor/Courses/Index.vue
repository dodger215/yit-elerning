<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import { 
    BookOpen, 
    Plus, 
    Users, 
    Settings, 
    BarChart3, 
    MoreVertical,
    Clock,
    CheckCircle2
} from 'lucide-vue-next';

const props = defineProps<{
    courses: any;
}>();
</script>

<template>
    <AppLayout>
        <Head title="Course Management" />

        <div class="space-y-6 max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Curriculum Control</h1>
                    <p class="text-xs text-slate-500 font-medium">Create, edit, and monitor your educational content.</p>
                </div>
                
                <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                    <Plus class="w-4 h-4" />
                    Launch New Course
                </button>
            </div>

            <Card padding="false">
                <DataTable :headers="['Course', 'Students', 'Status', 'Performance', 'Actions']" :items="courses.data">
                    <template #row="{ item }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-10 rounded bg-slate-800 border border-white/10 flex items-center justify-center text-blue-500">
                                    <BookOpen class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white truncate w-48">{{ item.title }}</p>
                                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">{{ item.level }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-300">
                                <Users class="w-3.5 h-3.5 text-slate-500" />
                                {{ item.total_students }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span :class="item.status === 'published' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'" 
                                  class="px-2 py-0.5 rounded-md border text-[9px] font-black uppercase tracking-widest">
                                {{ item.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-blue-500 text-[10px] font-black uppercase tracking-widest">
                                <BarChart3 class="w-3.5 h-3.5" />
                                Stable
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <Link :href="route('courses.show', item.id)" class="p-2 hover:bg-white/5 rounded-lg text-slate-500 hover:text-white transition-all" title="Manage Curriculum">
                                    <Settings class="w-4 h-4" />
                                </Link>
                                <button class="p-2 hover:bg-blue-600/10 rounded-lg text-slate-500 hover:text-blue-500 transition-all">
                                    <MoreVertical class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </template>
                </DataTable>

                <div class="p-4 border-t border-white/5 flex items-center justify-between">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                        Total Courses: {{ courses.total }}
                    </p>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
