<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StatsCard from '@/components/StatsCard.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import { 
    GraduationCap, 
    BookOpen, 
    Video, 
    ChevronRight,
    Search,
    UserCheck
} from 'lucide-vue-next';

const props = defineProps<{
    instructors: any;
}>();
</script>

<template>
    <AppLayout>
        <Head title="Instructor Management" />

        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Instructor Oversight</h1>
                    <p class="text-xs text-slate-500 font-medium">Monitor instructor performance and course activity.</p>
                </div>
            </div>

            <Card padding="false">
                <DataTable :headers="['Instructor', 'Courses', 'Sessions', 'Recent Content', 'Actions']" :items="instructors.data">
                    <template #row="{ item }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center font-bold text-blue-500 text-xs">
                                    {{ item.first_name?.[0] }}{{ item.last_name?.[0] }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ item.first_name }} {{ item.last_name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ item.email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-slate-300">{{ item.courses_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-slate-300">{{ item.hosted_meetings_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span v-for="course in item.courses" :key="course.id" class="text-[9px] text-slate-400 truncate w-32 font-medium">
                                    • {{ course.title }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <Link :href="route('admin.instructors.show', item.id)" 
                                  class="flex items-center gap-2 text-blue-500 hover:text-blue-400 text-[11px] font-black uppercase tracking-widest transition-colors">
                                View Profile
                                <ChevronRight class="w-3.5 h-3.5" />
                            </Link>
                        </td>
                    </template>
                </DataTable>

                <div class="p-4 border-t border-white/5 flex items-center justify-between">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                        Total {{ instructors.total }} Instructors
                    </p>
                    <div class="flex gap-2">
                        <Link v-if="instructors.prev_page_url" :href="instructors.prev_page_url" class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-slate-400 hover:text-white transition-all">Prev</Link>
                        <Link v-if="instructors.next_page_url" :href="instructors.next_page_url" class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-slate-400 hover:text-white transition-all">Next</Link>
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
