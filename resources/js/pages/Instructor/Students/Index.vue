<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import { 
    Users, 
    Mail, 
    BookOpen, 
    MessageCircle, 
    ChevronRight,
    Search
} from 'lucide-vue-next';

const props = defineProps<{
    students: any;
}>();
</script>

<template>
    <AppLayout>
        <Head title="My Students" />

        <div class="space-y-6 max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Student Community</h1>
                    <p class="text-xs text-slate-500 font-medium">Manage and support the students enrolled in your courses.</p>
                </div>
                
                <div class="relative w-full sm:w-64">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input type="text" placeholder="Search students..." 
                           class="bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-xs w-full focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                </div>
            </div>

            <Card padding="false">
                <DataTable :headers="['Student', 'Course', 'Progress', 'Academic Score', 'Actions']" :items="students.data">
                    <template #row="{ item }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center font-bold text-green-500 text-[10px]">
                                    {{ item.username?.[0] }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ item.username }}</p>
                                    <p class="text-[9px] text-slate-500">{{ item.email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-300">{{ item.course_title }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-24 h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" :style="{ width: `${item.progress_percentage}%` }"></div>
                                </div>
                                <span class="text-[9px] font-black text-slate-500">{{ item.progress_percentage }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-white">{{ item.average_score }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button class="p-2 hover:bg-white/5 rounded-lg text-slate-500 hover:text-white transition-all" title="Send Message">
                                    <MessageCircle class="w-4 h-4" />
                                </button>
                                <button class="p-2 hover:bg-blue-600/10 rounded-lg text-slate-500 hover:text-blue-500 transition-all">
                                    <ChevronRight class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </template>
                </DataTable>

                <div class="p-4 border-t border-white/5 flex items-center justify-between">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                        Total Enrolled Students: {{ students.total }}
                    </p>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
