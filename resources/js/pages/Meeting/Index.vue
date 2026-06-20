<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import Modal from '@/components/Modal.vue';
import {
    Video,
    Calendar,
    Users,
    Plus,
    ArrowRight,
    Search,
    Clock,
    Check,
    ShieldCheck,
    Edit3,
    Trash2,
    Globe,
    Briefcase,
    Download,
    Mail
} from 'lucide-vue-next';

defineProps<{
    meetings: any[];
}>();

const page = usePage();
const activeRole = computed(() => page.props.auth.active_role as string);
const isSupervisor = computed(() => activeRole.value === 'supervisor');
const authUser = computed(() => page.props.auth.user as any);

const isHostOrSupervisor = (meeting: any) => {
    return isSupervisor.value || authUser.value.id === meeting.host_user_id;
};

// Meeting Form
const showBoardModal = ref(false);
const boardForm = useForm({
    id: null as string | null,
    title: '',
    description: '',
    meeting_type: 'board',
    start_time: '',
    roles: ['instructor', 'editor'] as string[],
    recording_enabled: false,
    guest_emails: '',
});

const handleEdit = (meeting: any) => {
    boardForm.id = meeting.id;
    boardForm.title = meeting.title;
    boardForm.description = meeting.description;
    boardForm.meeting_type = meeting.meeting_type;
    boardForm.recording_enabled = !!meeting.recording_enabled;
    boardForm.guest_emails = meeting.guest_emails ? meeting.guest_emails.join(', ') : '';
    // Format date for datetime-local input
    const date = new Date(meeting.start_time);
    const localDate = new Date(date.getTime() - date.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
    boardForm.start_time = localDate;

    showBoardModal.value = true;
};

const openCreateModal = () => {
    boardForm.reset();
    boardForm.id = null;
    showBoardModal.value = true;
};

const setToday = () => {
    const date = new Date();
    const localDate = new Date(date.getTime() - date.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
    boardForm.start_time = localDate;
};

const handleDelete = (meeting: any) => {
    if (confirm('Are you sure you want to delete this meeting?')) {
        router.delete(route('meetings.destroy', meeting.id));
    }
};

const handleResendInvites = (meeting: any) => {
    if (confirm('Are you sure you want to resend email invites to all saved guests?')) {
        router.post(route('meetings.resend-invites', meeting.id), {}, { preserveScroll: true });
    }
};

const submitBoardMeeting = () => {
    if (boardForm.id) {
        boardForm.patch(route('meetings.update', boardForm.id), {
            onSuccess: () => {
                showBoardModal.value = false;
                boardForm.reset();
            }
        });
    } else {
        boardForm.post(route('meetings.store'), {
            onSuccess: () => {
                showBoardModal.value = false;
                boardForm.reset();
            }
        });
    }
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
        <Head title="Meetings" />

        <div class="space-y-8 max-w-[1200px] mx-auto">
            <div class="flex items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Meetings</h1>
                    <p class="text-slate-500 font-medium mt-1">Manage and join your interactive sessions.</p>
                </div>

                <div class="flex flex-direction-row justify-content-space-between m-4">
                    <button @click="openCreateModal" class="flex items-center gap-2 px-4 py-2 mr-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                        <Plus class="w-4 h-4" />
                        New Meeting
                    </button>

                    <Link :href="route('meetings.recordings')"
                        class="z-50 flex items-center gap-3 px-6 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl shadow-2xl shadow-blue-600/30 transition-all hover:-translate-y-1 active:scale-95 group border border-blue-400/20">
                        <Video class="w-5 h-5 group-hover:scale-110 transition-transform" />
                        <span class="font-black text-sm tracking-tight">Recordings</span>
                    </Link>
                </div>

            </div>

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-grow">
                    <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                    <input type="text" placeholder="Filter meetings by title or host..."
                           class="w-full bg-[#0d1117] border border-white/10 rounded-2xl pl-12 pr-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                </div>
            </div>

            <!-- Meetings Grid -->
            <div v-if="meetings.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="meeting in meetings" :key="meeting.id"
                     class="group bg-[#0d1117] border border-white/10 rounded-[2.5rem] p-8 hover:border-blue-500/30 transition-all shadow-2xl shadow-black/40 relative overflow-hidden">

                    <!-- Type Badge -->
                    <div class="absolute top-8 right-8">
                        <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 text-[10px] font-black uppercase tracking-widest border border-blue-500/20">
                            {{ meeting.meeting_type }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center">
                            <Video class="w-7 h-7 text-blue-500" />
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white group-hover:text-blue-400 transition-colors">{{ meeting.title }}</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Room ID: {{ meeting.room_id }}</p>
                        </div>
                    </div>

                    <p class="text-sm text-slate-400 leading-relaxed mb-8 line-clamp-2">
                        {{ meeting.description || 'No description provided for this session.' }}
                    </p>

                    <div class="flex flex-col gap-4 mb-8">
                        <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
                            <Calendar class="w-4 h-4 text-blue-500/60" />
                            <span>{{ new Date(meeting.start_time).toLocaleString() }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
                            <Clock class="w-4 h-4 text-blue-500/60" />
                            <span>Approx. 1 hour</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-white/5">
                        <div class="flex -space-x-2">
                            <div v-for="i in 3" :key="i" class="w-7 h-7 rounded-full bg-slate-800 border-2 border-[#0d1117] flex items-center justify-center text-[8px] font-bold text-slate-400">
                                U{{ i }}
                            </div>
                            <div class="w-7 h-7 rounded-full bg-blue-600/20 border-2 border-[#0d1117] flex items-center justify-center text-[8px] font-bold text-blue-400">
                                +12
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button v-if="isHostOrSupervisor(meeting) && meeting.meeting_type === 'public' && meeting.guest_emails && meeting.guest_emails.length > 0"
                                    @click="handleResendInvites(meeting)"
                                    class="p-2.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 rounded-xl transition-all active:scale-95" title="Resend Invites">
                                <Mail class="w-4 h-4" />
                            </button>
                            <button v-if="isHostOrSupervisor(meeting)" @click="handleEdit(meeting)"
                                    class="p-2.5 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all active:scale-95" title="Edit Meeting">
                                <Edit3 class="w-4 h-4" />
                            </button>
                            <button v-if="isHostOrSupervisor(meeting)" @click="handleDelete(meeting)"
                                    class="p-2.5 bg-white/5 hover:bg-red-500/10 text-slate-400 hover:text-red-500 rounded-xl transition-all active:scale-95" title="Delete Meeting">
                                <Trash2 class="w-4 h-4" />
                            </button>
                            <a v-if="meeting.recording_url && activeRole !== 'regular'" :href="meeting.recording_url + '?download=1'"
                                    class="flex items-center gap-2 px-4 py-2.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 rounded-xl font-bold text-xs transition-all active:scale-95" title="Download Recording">
                                <Download class="w-4 h-4" />
                            </a>
                            <button v-if="meeting.meeting_type === 'public'" @click="() => { navigator.clipboard.writeText(route('meeting.join', meeting.room_id)); alert('Link copied!'); }"
                                    class="flex items-center gap-2 px-4 py-2.5 bg-white/5 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl font-bold text-xs transition-all active:scale-95" title="Copy Share Link">
                                <Globe class="w-4 h-4" />
                            </button>
                            <Link :href="route('meeting.join', meeting.room_id)"
                                  class="flex items-center gap-2 px-6 py-2.5 bg-white/5 hover:bg-blue-600 text-slate-300 hover:text-white rounded-xl font-black text-xs transition-all active:scale-95">
                                Join Now
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20 bg-[#0d1117] border border-white/10 rounded-[2.5rem] border-dashed">
                <Video class="w-12 h-12 text-slate-700 mx-auto mb-4" />
                <h3 class="text-xl font-bold text-white">No meetings found</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto">You don't have any upcoming meetings scheduled at the moment.</p>
            </div>
        </div>

        <!-- Floating Action Button for Recordings -->
        <!-- <Link :href="route('meetings.recordings')"
              class="fixed bottom-8 right-8 z-50 flex items-center gap-3 px-6 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-full shadow-2xl shadow-blue-600/30 transition-all hover:-translate-y-1 active:scale-95 group border border-blue-400/20">
            <Video class="w-5 h-5 group-hover:scale-110 transition-transform" />
            <span class="font-black text-sm tracking-tight">Recordings</span>
        </Link> -->

        <!-- Create/Edit Meeting Modal -->
        <Modal :show="showBoardModal" :title="boardForm.id ? 'Edit Meeting' : 'Schedule Meeting'" @close="showBoardModal = false">
            <form @submit.prevent="submitBoardMeeting" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Meeting Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="boardForm.meeting_type = 'board'"
                                :class="boardForm.meeting_type === 'board' ? 'bg-blue-600 border-blue-500 text-white shadow-lg shadow-blue-600/20' : 'bg-white/5 border-white/10 text-slate-400 hover:bg-white/10'"
                                class="flex items-center justify-center gap-2 py-3 rounded-xl border transition-all">
                            <Briefcase class="w-4 h-4" />
                            <span class="text-xs font-bold">Board Meeting</span>
                        </button>
                        <button type="button" @click="boardForm.meeting_type = 'public'"
                                :class="boardForm.meeting_type === 'public' ? 'bg-blue-600 border-blue-500 text-white shadow-lg shadow-blue-600/20' : 'bg-white/5 border-white/10 text-slate-400 hover:bg-white/10'"
                                class="flex items-center justify-center gap-2 py-3 rounded-xl border transition-all">
                            <Globe class="w-4 h-4" />
                            <span class="text-xs font-bold">Public Meeting</span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Meeting Title</label>
                    <input v-model="boardForm.title" type="text" placeholder="Strategic Planning Q3"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description (Optional)</label>
                    <textarea v-model="boardForm.description" rows="2" placeholder="Brief agenda..."
                              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Date & Time</label>
                            <button type="button" @click="setToday" class="text-[10px] font-bold text-blue-500 hover:text-blue-400 uppercase tracking-widest">Set Today</button>
                        </div>
                        <div class="relative">
                            <Clock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                            <input v-model="boardForm.start_time" type="datetime-local"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-xs text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all" required>
                        </div>
                    </div>

                    <div class="flex items-center mt-4 sm:mt-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" v-model="boardForm.recording_enabled" class="sr-only peer" />
                                <div class="w-10 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-300">Record Meeting</span>
                        </label>
                    </div>
                </div>

                <div v-if="boardForm.meeting_type === 'public'">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Guest Emails (Comma separated)</label>
                    <input v-model="boardForm.guest_emails" type="text" placeholder="guest1@example.com, guest2@example.com"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                </div>

                <div v-if="!boardForm.id && boardForm.meeting_type === 'board'">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Invite Roles (Email & SMS)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <button v-for="role in ['instructor', 'editor', 'supervisor']" :key="role"
                                type="button"
                                @click="toggleRole(role)"
                                :class="boardForm.roles.includes(role) ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-600/20' : 'bg-white/5 text-slate-400 border-white/10 hover:bg-white/10'"
                                class="flex items-center justify-between px-3 py-2 rounded-xl border transition-all">
                            <span class="text-[10px] font-black uppercase tracking-widest">{{ role }}</span>
                            <Check v-if="boardForm.roles.includes(role)" class="w-3 h-3" />
                        </button>
                    </div>
                    <p class="text-[9px] text-slate-500 mt-2 font-medium italic">Selected roles will receive immediate Email and SMS notifications.</p>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showBoardModal = false" class="flex-1 px-4 py-2 bg-white/5 text-slate-300 font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" :disabled="boardForm.processing" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-500 transition-all disabled:opacity-50">
                        {{ boardForm.processing ? (boardForm.id ? 'Saving...' : 'Scheduling...') : (boardForm.id ? 'Update Meeting' : 'Create & Notify') }}
                    </button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
