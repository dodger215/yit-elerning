<script setup lang="ts">
import { computed, ref } from 'vue';
import { usePage, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StatsCard from '@/components/StatsCard.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import Modal from '@/components/Modal.vue';
import { 
    Users, 
    GraduationCap, 
    Video, 
    Play,
    Plus,
    Calendar,
    ArrowRight,
    Check,
    Clock,
    Shield,
    BookOpen,
    Settings,
    HelpCircle
} from 'lucide-vue-next';

const props = defineProps<{
    stats: {
        total_users: number;
        active_meetings: number;
        total_courses: number;
        total_videos: number;
        new_students_count: number;
        upcoming_meetings: any[];
        recent_courses: any[];
        recent_users: any[];
    }
}>();

const page = usePage();
const user = computed(() => page.props.auth.user as any);
const activeRole = computed(() => page.props.auth.active_role as string);

const isSupervisor = computed(() => activeRole.value === 'supervisor');
const isInstructor = computed(() => activeRole.value === 'instructor');
const isRegular = computed(() => activeRole.value === 'student' || activeRole.value === 'regular');

// Board Meeting Form
const showBoardModal = ref(false);
const boardForm = useForm({
    title: '',
    description: '',
    meeting_type: 'board',
    start_time: '',
    roles: ['instructor', 'editor'] as string[]
});

const submitBoardMeeting = () => {
    boardForm.post(route('meetings.store'), {
        onSuccess: () => {
            showBoardModal.value = false;
            boardForm.reset();
        }
    });
};

const toggleRole = (role: string) => {
    if (boardForm.roles.includes(role)) {
        boardForm.roles = boardForm.roles.filter(r => r !== role);
    } else {
        boardForm.roles.push(role);
    }
};
</script>

<template>
    <AppLayout>
        <div class="space-y-8 max-w-[1600px] mx-auto">
            <!-- Hero Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        Welcome back, <span class="text-blue-500">{{ user.first_name }}</span>
                    </h1>
                    <p class="text-slate-500 font-medium mt-1">Here's what's happening on your platform today.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <button v-if="isSupervisor" @click="showBoardModal = true" class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-purple-600/20 transition-all active:scale-95">
                        <Video class="w-4 h-4" />
                        Schedule Board Meeting
                    </button>
                    <button v-if="isInstructor" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                        <Plus class="w-4 h-4" />
                        Create Course
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 text-slate-200 border border-white/10 rounded-xl font-bold text-sm transition-all">
                        <Calendar class="w-4 h-4" />
                        View Schedule
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <template v-if="isSupervisor">
                    <StatsCard label="Total Users" :value="props.stats.total_users.toLocaleString()" :icon="Users" color="blue" />
                    <StatsCard label="Total Courses" :value="props.stats.total_courses.toString()" :icon="GraduationCap" color="green" />
                    <StatsCard label="Active Meetings" :value="props.stats.active_meetings.toString()" :icon="Video" color="purple" trendLabel="Live now" />
                    <StatsCard label="New Students" :value="props.stats.new_students_count.toString()" :icon="Play" color="orange" trendLabel="Last 30 days" />
                </template>
                <template v-else-if="isInstructor">
                    <StatsCard label="Enrolled Students" value="--" :icon="Users" color="blue" />
                    <StatsCard label="Course Views" value="--" :icon="Play" color="purple" />
                    <StatsCard label="Active Courses" :value="props.stats.total_courses.toString()" :icon="BookOpen" color="green" />
                    <StatsCard label="Average Rating" value="--" :icon="GraduationCap" color="orange" />
                </template>
                <template v-else>
                    <StatsCard label="Courses Enrolled" value="--" :icon="BookOpen" color="blue" />
                    <StatsCard label="Lessons Completed" value="--" :icon="Play" color="green" />
                    <StatsCard label="Overall Progress" value="--" :icon="GraduationCap" color="purple" />
                    <StatsCard label="Average Score" value="--" :icon="Users" color="orange" />
                </template>
            </div>

            <!-- Content Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Activity Column -->
                <div class="lg:col-span-2 space-y-8">
                    <Card v-if="isSupervisor" title="Recent User Activity" subtitle="Real-time registration log">
                        <DataTable :headers="['User', 'Role', 'Status', 'Joined']" :items="props.stats.recent_users">
                            <template #row="{ item }">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center font-bold text-blue-500 text-[10px]">
                                            {{ item.name[0] }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white">{{ item.name }}</p>
                                            <p class="text-[10px] text-slate-500">{{ item.email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-widest border border-blue-500/20">
                                        {{ item.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                                        <span class="text-xs text-slate-300 font-medium">Active</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-medium">{{ item.joined }}</td>
                            </template>
                        </DataTable>
                    </Card>

                    <Card v-if="!isSupervisor" title="Continue Learning" subtitle="Pick up where you left off">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-for="i in 2" :key="i" class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-blue-500/30 transition-all group">
                                <div class="h-32 rounded-xl bg-slate-800 mb-4 overflow-hidden relative">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/40 transform scale-90 group-hover:scale-100 transition-transform">
                                            <Play class="w-5 h-5 text-white fill-current" />
                                        </div>
                                    </div>
                                </div>
                                <h4 class="font-bold text-white text-sm mb-1">Advanced React Patterns</h4>
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex-1 bg-white/5 h-1 rounded-full overflow-hidden mr-4">
                                        <div class="bg-blue-600 h-full w-[65%]"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-500">65%</span>
                                </div>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Sidebar Activity Column -->
                <div class="space-y-8">
                    <Card title="Upcoming Meetings" subtitle="Live interactive sessions">
                        <div v-if="props.stats.upcoming_meetings.length > 0" class="space-y-4">
                            <div v-for="(meeting, idx) in props.stats.upcoming_meetings" :key="idx" 
                                 class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all cursor-pointer">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="text-sm font-bold text-white">{{ meeting.title }}</h4>
                                    <Video class="w-4 h-4 text-blue-500" />
                                </div>
                                <div class="flex items-center gap-2 text-[10px] font-medium text-slate-500">
                                    <Calendar class="w-3.5 h-3.5" />
                                    <span>{{ new Date(meeting.start_time).toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-6 text-slate-500 text-xs italic">
                            No upcoming meetings.
                        </div>
                        <template #footer>
                            <Link :href="route('meetings.index')" class="w-full flex items-center justify-center gap-2 text-[11px] font-black uppercase tracking-widest text-blue-500 hover:text-blue-400 transition-colors py-1">
                                View Full Calendar
                                <ArrowRight class="w-3.5 h-3.5" />
                            </Link>
                        </template>
                    </Card>

                    <!-- Role Quick Actions -->
                    <Card title="Quick Actions">
                        <div class="grid grid-cols-2 gap-3">
                            <button v-if="isSupervisor" class="p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:bg-blue-600/10 hover:border-blue-500/20 transition-all text-center group">
                                <Users class="w-5 h-5 mx-auto mb-2 text-slate-500 group-hover:text-blue-400" />
                                <span class="text-[10px] font-bold text-slate-400 group-hover:text-white uppercase tracking-tighter">Invite User</span>
                            </button>
                            <button v-if="isInstructor" class="p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:bg-green-600/10 hover:border-green-500/20 transition-all text-center group">
                                <Plus class="w-5 h-5 mx-auto mb-2 text-slate-500 group-hover:text-green-400" />
                                <span class="text-[10px] font-bold text-slate-400 group-hover:text-white uppercase tracking-tighter">New Lesson</span>
                            </button>
                            <button class="p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:bg-purple-600/10 hover:border-purple-500/20 transition-all text-center group">
                                <Settings class="w-5 h-5 mx-auto mb-2 text-slate-500 group-hover:text-purple-400" />
                                <span class="text-[10px] font-bold text-slate-400 group-hover:text-white uppercase tracking-tighter">Profile</span>
                            </button>
                            <button class="p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:bg-orange-600/10 hover:border-orange-500/20 transition-all text-center group">
                                <HelpCircle class="w-5 h-5 mx-auto mb-2 text-slate-500 group-hover:text-orange-400" />
                                <span class="text-[10px] font-bold text-slate-400 group-hover:text-white uppercase tracking-tighter">Support</span>
                            </button>
                        </div>
                    </Card>
                </div>
            </div>
        </div>

        <!-- Create Board Meeting Modal -->
        <Modal :show="showBoardModal" title="Schedule Board Meeting" @close="showBoardModal = false">
            <form @submit.prevent="submitBoardMeeting" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Meeting Title</label>
                    <input v-model="boardForm.title" type="text" placeholder="Strategic Planning Q3"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description (Optional)</label>
                    <textarea v-model="boardForm.description" rows="2" placeholder="Brief agenda..."
                              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Date & Time</label>
                        <div class="relative">
                            <Clock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                            <input v-model="boardForm.start_time" type="datetime-local"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-xs text-white focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Invite Roles (Email & SMS)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <button v-for="role in ['instructor', 'editor', 'supervisor']" :key="role"
                                type="button"
                                @click="toggleRole(role)"
                                :class="boardForm.roles.includes(role) ? 'bg-purple-600 text-white border-purple-500 shadow-lg shadow-purple-600/20' : 'bg-white/5 text-slate-400 border-white/10 hover:bg-white/10'"
                                class="flex items-center justify-between px-3 py-2 rounded-xl border transition-all">
                            <span class="text-[10px] font-black uppercase tracking-widest">{{ role }}</span>
                            <Check v-if="boardForm.roles.includes(role)" class="w-3 h-3" />
                        </button>
                    </div>
                    <p class="text-[9px] text-slate-500 mt-2 font-medium italic">Selected roles will receive immediate Email and SMS notifications.</p>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showBoardModal = false" class="flex-1 px-4 py-2 bg-white/5 text-slate-300 font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" :disabled="boardForm.processing" class="flex-1 px-4 py-2 bg-purple-600 text-white font-bold rounded-xl text-sm hover:bg-purple-500 transition-all disabled:opacity-50">
                        {{ boardForm.processing ? 'Scheduling...' : 'Create & Notify' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
