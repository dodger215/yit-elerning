<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PageLoader from '@/Components/PageLoader.vue';

const props = defineProps<{
    meeting: any;
    participantName: string;
    isHost?: boolean;
}>();

const jitsiContainer = ref<HTMLElement | null>(null);
const api = ref<any>(null);
const error = ref<string | null>(null);
const isLobbyEnabled = ref(false);

const isRecording = ref(false);
const isUploading = ref(false);
let mediaRecorder: MediaRecorder | null = null;
let recordedChunks: Blob[] = [];

const toggleRecording = async () => {
    if (isRecording.value) {
        stopRecording();
    } else {
        await startRecording();
    }
};

const startRecording = async () => {
    try {
        const stream = await navigator.mediaDevices.getDisplayMedia({
            video: true,
            audio: true
        });

        let mimeType = 'video/webm';
        if (MediaRecorder.isTypeSupported('video/mp4')) {
            mimeType = 'video/mp4';
        } else if (MediaRecorder.isTypeSupported('video/webm;codecs=vp9,opus')) {
            mimeType = 'video/webm;codecs=vp9,opus';
        }

        mediaRecorder = new MediaRecorder(stream, { mimeType });
        recordedChunks = [];

        mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                recordedChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = async () => {
            const blob = new Blob(recordedChunks, { type: mimeType });
            await uploadRecording(blob, mimeType);
            stream.getTracks().forEach(track => track.stop());
        };

        mediaRecorder.start();
        isRecording.value = true;
    } catch (err) {
        console.error("Error starting screen recording", err);
        alert("Could not start recording. Please ensure permissions are granted.");
    }
};

const stopRecording = () => {
    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.stop();
        isRecording.value = false;
    }
};

const uploadRecording = async (blob: Blob, mimeType: string = 'video/webm') => {
    isUploading.value = true;
    const extension = mimeType.includes('mp4') ? 'mp4' : 'webm';
    const formData = new FormData();
    formData.append('recording', blob, `meeting-${props.meeting.id}-recording.${extension}`);

    try {
        const token = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='))?.split('=')[1];
        const response = await fetch(route('meetings.upload-recording', props.meeting.id), {
            method: 'POST',
            body: formData,
            headers: {
                'X-XSRF-TOKEN': decodeURIComponent(token || ''),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Upload failed');
        alert('Recording uploaded successfully!');
    } catch (err) {
        console.error('Error uploading recording', err);
        alert('Failed to upload recording.');
    } finally {
        isUploading.value = false;
    }
};

const loadJitsiScript = () => {
    return new Promise((resolve, reject) => {
        if ((window as any).JitsiMeetExternalAPI) {
            resolve(true);
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://jitsi.riot.im/external_api.js';
        script.async = true;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
};

const initJitsi = async () => {
    try {
        await loadJitsiScript();

        const domain = 'jitsi.riot.im';
        const options = {
            roomName: props.meeting.room_id,
            width: '100%',
            height: '100%',
            parentNode: jitsiContainer.value,
            userInfo: {
                displayName: props.participantName
            },
            configOverwrite: {
                startWithAudioMuted: false,
                startWithVideoMuted: false,
                disableDeepLinking: true,
                prejoinPageEnabled: false,
                enableWelcomePage: false,
                enableClosePage: false,
                // Watermark and Branding
                hideConferenceTimer: true,
                showJitsiWatermark: false,
                showBrandWatermark: false,
                showPoweredBy: false,
                enableNoisyMicDetection: true,
                disableThirdPartyRequests: false,
                hideLobbyButton: !props.isHost,
                p2p: {
                    enabled: true,
                    useStunTurn: true,
                }
            },
            interfaceConfigOverwrite: {
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'chat', 'recording', 'share',
                    'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    'videoquality', 'filmstrip', 'feedback', 'stats', 'shortcuts',
                    'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone',
                    'security'
                ],
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                SHOW_BRAND_WATERMARK: false,
                BRAND_WATERMARK_LINK: '',
                JITSI_WATERMARK_LINK: '',
                HIDE_DEEP_LINKING_LOGO: true,
                DEFAULT_REMOTE_DISPLAY_NAME: 'Participant',
                DEFAULT_LOCAL_DISPLAY_NAME: 'Me',
                APP_NAME: 'Youth In Tech',
            }
        };

        api.value = new (window as any).JitsiMeetExternalAPI(domain, options);

        // Event Listeners
        api.value.addEventListeners({
            readyToClose: () => leaveRoom(),
            videoConferenceLeft: () => leaveRoom(),
            endpointTextMessageReceived: (event: any) => {
                console.log('Message received:', event);
            }
        });

    } catch (e: any) {
        console.error('Failed to initialize Jitsi', e);
        error.value = 'Failed to load the meeting room. Please check your connection.';
    }
};

// Host Commands
const muteEveryone = () => {
    if (api.value && props.isHost) {
        api.value.executeCommand('muteEveryone');
    }
};

const toggleLobby = () => {
    if (api.value && props.isHost) {
        isLobbyEnabled.value = !isLobbyEnabled.value;
        api.value.executeCommand('toggleLobby', isLobbyEnabled.value);
    }
};
const leaveRoom = () => {
    if (isRecording.value) {
        stopRecording();
    }
    router.visit(route('meeting.ended', props.meeting.room_id));
};

const copied = ref(false);
const copyShareLink = () => {
    const url = route('meeting.join', props.meeting.room_id);
    navigator.clipboard.writeText(url).then(() => {
        copied.value = true;
        setTimeout(() => { copied.value = false; }, 2500);
    });
};

onMounted(() => {
    initJitsi();
});

onUnmounted(() => {
    if (api.value) {
        api.value.dispose();
    }
});
</script>

<template>
    <Head :title="`Meeting: ${meeting.title}`" />
    <PageLoader />

    <div class="fixed inset-0 bg-black text-white flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="h-14 px-6 border-b border-slate-800 flex items-center justify-between bg-slate-900/95 backdrop-blur-md z-10 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-blue-600 rounded flex items-center justify-center font-bold text-sm shadow-lg shadow-blue-500/20">Y</div>
                <div>
                    <h2 class="text-xs font-semibold leading-tight text-slate-100">{{ meeting.title }}</h2>
                    <p class="text-[9px] text-slate-400 font-medium uppercase tracking-wider">
                        {{ meeting.meeting_type === 'board' ? 'Board Session' : 'Live Meeting' }}
                    </p>
                </div>
            </div>

            <!-- Host Panel (Floating inline or here) -->
            <div v-if="isHost" class="hidden md:flex items-center gap-2 bg-slate-800/50 p-1 rounded-lg border border-white/5">
                <button v-if="meeting.recording_enabled" @click="toggleRecording" :title="isRecording ? 'Stop Recording' : 'Start Recording'" class="p-1.5 hover:bg-slate-700 rounded transition-colors flex items-center gap-1" :class="isRecording ? 'text-red-500' : 'text-slate-300'">
                    <svg v-if="isRecording" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><rect x="6" y="6" width="12" height="12" rx="2" ry="2"></rect></svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle></svg>
                    <span v-if="isUploading" class="text-[9px] font-bold text-blue-400 uppercase tracking-tighter animate-pulse">Uploading...</span>
                </button>
                <div v-if="meeting.recording_enabled" class="w-px h-4 bg-white/10 mx-1"></div>
                <button @click="muteEveryone" title="Mute Everyone" class="p-1.5 hover:bg-slate-700 rounded transition-colors text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                </button>
                <button @click="toggleLobby" :title="isLobbyEnabled ? 'Unlock Room' : 'Lock Room (Lobby)'" class="p-1.5 hover:bg-slate-700 rounded transition-colors" :class="isLobbyEnabled ? 'text-yellow-500' : 'text-slate-300'">
                    <svg v-if="isLobbyEnabled" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>
                </button>
                <span class="text-[8px] font-bold text-slate-500 px-2 uppercase tracking-tighter">Host Tools</span>
            </div>

            <div class="flex items-center gap-4 text-[10px] text-slate-400">
                <!-- Public Share Link -->
                <button
                    v-if="meeting.meeting_type === 'public'"
                    @click="copyShareLink"
                    :title="copied ? 'Copied!' : 'Copy Share Link'"
                    class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg border transition-all text-[9px] font-bold uppercase tracking-widest"
                    :class="copied ? 'bg-green-500/10 border-green-500/20 text-green-400' : 'bg-white/5 border-white/10 text-slate-400 hover:text-white hover:bg-white/10'"
                >
                    <svg v-if="!copied" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                    <svg v-else class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    {{ copied ? 'Copied!' : 'Share Link' }}
                </button>

                <span class="flex items-center gap-1.5 px-2 py-0.5 bg-green-500/10 border border-green-500/20 rounded-full text-green-400">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                    Live
                </span>
                <span class="opacity-30">|</span>
                <span class="font-medium text-slate-300 uppercase tracking-tighter">{{ participantName }}</span>
            </div>
        </header>

        <!-- Jitsi IFrame Container -->
        <main class="flex-1 bg-black relative">
            <!-- Overlay to hide Jitsi Watermark COMPLETELY -->
            <!-- <div class="absolute top-0 left-0 w-48 h-16 bg-slate-950 z-50 flex items-center px-4 pointer-events-none select-none border-b border-r border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                    <div>
                        <span class="block text-[10px] font-black text-slate-100 uppercase tracking-[0.2em] leading-none mb-1">Secure Session</span>
                        <span class="block text-[7px] text-slate-500 uppercase tracking-widest font-medium">Youth In Tech • Encrypted</span>
                    </div>
                </div>
            </div> -->

            <div ref="jitsiContainer" class="absolute inset-0 w-full h-full"></div>

            <!-- Loading State Overlay -->
            <div v-if="!api" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-950 z-0">
                <div class="w-10 h-10 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p class="text-[10px] text-slate-500 font-medium uppercase tracking-widest animate-pulse">Initializing Secure Meeting...</p>
            </div>
        </main>
    </div>
</template>

<style scoped>
:deep(iframe) {
    border: none;
    width: 100% !important;
    height: 100% !important;
}
</style>
