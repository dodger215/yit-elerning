<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StatsCard from '@/components/StatsCard.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import { 
    ArrowLeft, 
    BookOpen, 
    Users, 
    Video, 
    CheckCircle2, 
    XCircle,
    Archive
} from 'lucide-vue-next';

const props = defineProps<{
    instructor: any;
    courses: any;
}>();

const updateCourseStatus = (course: any, status: string) => {
    router.put(route('admin.courses.status.update', course.id), { status });
};
</script>

<template>
    <AppLayout>
        <Head :title="`Instructor: ${instructor.first_name}`" />

        <div class="space-y-8 max-w-[1400px] mx-auto">
            <Link :href="route('admin.instructors.index')" class="inline-flex items-center gap-2 text-slate-500 hover:text-white text-xs font-bold transition-colors">
                <ArrowLeft class="w-4 h-4" />
                Back to Instructors
            </Link>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center font-black text-blue-500 text-3xl shadow-xl shadow-blue-500/5">
                        {{ instructor.first_name[0] }}{{ instructor.last_name[0] }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-white tracking-tight">{{ instructor.first_name }} {{ instructor.last_name }}</h1>
                        <p class="text-slate-500 font-medium">{{ instructor.email }}</p>
                        <div class="flex gap-2 mt-2">
                            <span class="px-2 py-0.5 rounded-md bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-widest border border-blue-500/20">Instructor</span>
                            <span v-if="instructor.is_active" class="px-2 py-0.5 rounded-md bg-green-500/10 text-green-400 text-[10px] font-black uppercase tracking-widest border border-green-500/20">Active</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <StatsCard label="Total Courses" :value="instructor.courses_count" :icon="BookOpen" color="blue" />
                <StatsCard label="Total Enrollments" :value="instructor.enrollments_count" :icon="Users" color="green" />
                <StatsCard label="Live Sessions" :value="instructor.hosted_meetings_count" :icon="Video" color="purple" />
            </div>

            <!-- Instructor's Courses -->
            <Card title="Course Management" subtitle="Approve or manage courses created by this instructor">
                <DataTable :headers="['Course', 'Students', 'Status', 'Created At', 'Actions']" :items="courses.data">
                    <template #row="{ item }">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-white">{{ item.title }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-slate-300">{{ item.enrollments_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span :class="item.status === 'published' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'" 
                                  class="px-2 py-0.5 rounded-md border text-[9px] font-black uppercase tracking-widest">
                                {{ item.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                            {{ new Date(item.created_at).toLocaleDateString() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button v-if="item.status !== 'published'" 
                                        @click="updateCourseStatus(item, 'published')"
                                        class="p-2 hover:bg-green-500/10 rounded-lg text-slate-500 hover:text-green-500 transition-all" title="Approve & Publish">
                                    <CheckCircle2 class="w-4 h-4" />
                                </button>
                                <button v-if="item.status === 'published'" 
                                        @click="updateCourseStatus(item, 'draft')"
                                        class="p-2 hover:bg-yellow-500/10 rounded-lg text-slate-500 hover:text-yellow-500 transition-all" title="Move to Draft">
                                    <XCircle class="w-4 h-4" />
                                </button>
                                <button @click="updateCourseStatus(item, 'archived')"
                                        class="p-2 hover:bg-red-500/10 rounded-lg text-slate-500 hover:text-red-500 transition-all" title="Archive Course">
                                    <Archive class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </template>
                </DataTable>
            </Card>
        </div>
    </AppLayout>
</template>
