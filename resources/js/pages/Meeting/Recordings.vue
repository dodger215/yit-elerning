<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
    Video, 
    Calendar,
    ArrowLeft,
    Download,
    PlayCircle,
    X
} from 'lucide-vue-next';

defineProps<{
    meetings: any[];
}>();

const isVideoModalOpen = ref(false);
const currentMeeting = ref<any>(null);
const videoPlayer = ref<HTMLVideoElement | null>(null);
const audioPlayer = ref<HTMLAudioElement | null>(null);

const openVideoModal = (meeting: any) => {
    currentMeeting.value = meeting;
    isVideoModalOpen.value = true;
};

const closeVideoModal = () => {
    isVideoModalOpen.value = false;
    currentMeeting.value = null;
};

const syncAudio = () => {
    if (videoPlayer.value && audioPlayer.value) {
        audioPlayer.value.currentTime = videoPlayer.value.currentTime;
    }
};

const playAudio = () => { if (audioPlayer.value) audioPlayer.value.play(); };
const pauseAudio = () => { if (audioPlayer.value) audioPlayer.value.pause(); };
const onSeeked = () => syncAudio();
const onRateChange = () => {
    if (audioPlayer.value && videoPlayer.value) {
        audioPlayer.value.playbackRate = videoPlayer.value.playbackRate;
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Recorded Meetings" />

        <div class="space-y-8 max-w-[1200px] mx-auto">
            <div class="flex items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Recorded Meetings</h1>
                    <p class="text-slate-500 font-medium mt-1">Access past sessions and board meetings.</p>
                </div>
                
                <Link :href="route('meetings.index')" class="flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold text-sm transition-all active:scale-95">
                    <ArrowLeft class="w-4 h-4" />
                    Back to Meetings
                </Link>
            </div>

            <!-- Meetings Grid -->
            <div v-if="meetings.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="meeting in meetings" :key="meeting.id" 
                     class="group bg-[#0d1117] border border-white/10 rounded-[2.5rem] p-0 hover:border-blue-500/30 transition-all shadow-2xl shadow-black/40 relative overflow-hidden flex flex-col">
                    
                    <!-- Video Thumbnail -->
                    <div class="w-full h-48 bg-slate-900 relative">
                        <!-- Type Badge -->
                        <div class="absolute top-4 right-4 z-10">
                            <span class="px-3 py-1 rounded-full bg-blue-500/80 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest border border-blue-400/20 shadow-lg">
                                {{ meeting.meeting_type }}
                            </span>
                        </div>
                        <video :src="meeting.recording_url + '#t=0.1'" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity duration-500" preload="metadata" muted></video>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <PlayCircle class="w-16 h-16 text-white/50 group-hover:text-white group-hover:scale-110 transition-all duration-300" />
                        </div>
                    </div>

                    <div class="p-8 flex flex-col flex-grow">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                                <Video class="w-7 h-7 text-blue-500" />
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-white group-hover:text-blue-400 transition-colors line-clamp-1">{{ meeting.title }}</h3>
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
                    </div>

                        <div class="flex items-center justify-between pt-6 border-t border-white/5">
                            <div class="flex-grow"></div>
                            <div class="flex items-center gap-2">
                                <button @click="openVideoModal(meeting)"
                                   class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-black text-xs transition-all active:scale-95 shadow-lg shadow-blue-600/20">
                                    <Video class="w-4 h-4" />
                                    Watch
                                </button>
                                <a :href="meeting.recording_url + '?download=1'"
                                   class="flex items-center gap-2 px-4 py-2.5 bg-white/5 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl font-bold text-xs transition-all active:scale-95" title="Download Recording">
                                    <Download class="w-4 h-4" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20 bg-[#0d1117] border border-white/10 rounded-[2.5rem] border-dashed">
                <Video class="w-12 h-12 text-slate-700 mx-auto mb-4" />
                <h3 class="text-xl font-bold text-white">No recordings found</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto">There are currently no recorded meetings available.</p>
            </div>
        </div>

        <!-- Video Player Modal -->
        <div v-if="isVideoModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
            <div class="bg-slate-900 border border-slate-700 rounded-3xl p-6 max-w-5xl w-full shadow-2xl relative">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-white">{{ currentMeeting?.title }}</h2>
                    <button @click="closeVideoModal" class="bg-white/10 hover:bg-red-500 hover:text-white text-slate-300 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                        <X class="w-5 h-5" />
                    </button>
                </div>
                
                <div class="relative w-full aspect-video bg-black rounded-xl overflow-hidden border border-white/10 shadow-inner">
                    <video ref="videoPlayer" 
                           controls 
                           class="w-full h-full" 
                           :src="currentMeeting?.recording_url"
                           @play="playAudio"
                           @pause="pauseAudio"
                           @seeked="onSeeked"
                           @ratechange="onRateChange"
                           @waiting="pauseAudio"
                           @playing="playAudio"
                    ></video>
                </div>

                <!-- Hidden audio element for the merged sound track -->
                <audio ref="audioPlayer" 
                       v-if="currentMeeting" 
                       :src="currentMeeting.recording_url.replace('.mp4', '.webm')">
                </audio>
            </div>
        </div>
    </AppLayout>
</template>
