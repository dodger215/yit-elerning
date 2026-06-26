<script setup lang="ts">
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
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

// Minimized state variables
const isMinimized = ref(false);
const isPreviewMuted = ref(false);
const floatingVideoRef = ref<HTMLElement | null>(null);

// Recording state variables
const isRecording = ref(false);
const isUploading = ref(false);
let videoRecorder: MediaRecorder | null = null;
let audioRecorder: MediaRecorder | null = null;
let videoChunks: Blob[] = [];
let audioChunks: Blob[] = [];
let displayStreamRef: MediaStream | null = null;
let voiceStreamRef: MediaStream | null = null;
let audioContextRef: AudioContext | null = null;

// Toast Notifications
const toastMessage = ref('');
const toastType = ref('info');
let toastTimeout: any = null;

const showToast = (message: string, type: string = 'info') => {
    toastMessage.value = message;
    toastType.value = type;
    if (toastTimeout) clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        toastMessage.value = '';
    }, 4000);
};

const playNotificationSound = () => {
    try {
        const audioCtx = new (window.AudioContext || (window as any).webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(600, audioCtx.currentTime); // 600 Hz
        oscillator.frequency.exponentialRampToValueAtTime(1000, audioCtx.currentTime + 0.1);

        gainNode.gain.setValueAtTime(0.5, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);

        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);

        oscillator.start();
        oscillator.stop(audioCtx.currentTime + 0.3);
    } catch (e) {
        console.error('Audio playback failed', e);
    }
};

const broadcastToParticipants = (msgType: string, payload: any = {}) => {
    if (!api.value) return;
    const msg = JSON.stringify({ type: msgType, ...payload });
    const participants = api.value.getParticipantsInfo();
    participants.forEach((p: any) => {
        api.value.executeCommand('sendEndpointTextMessage', p.participantId, msg);
    });
};

// Custom Chat State
const isChatOpen = ref(false);
const chatMessages = ref<any[]>([]);
const newChatMessage = ref('');
const unreadChatCount = ref(0);
const chatContainerRef = ref<HTMLElement | null>(null);

const scrollToBottom = async () => {
    await nextTick();
    if (chatContainerRef.value) {
        chatContainerRef.value.scrollTop = chatContainerRef.value.scrollHeight;
    }
};

const fetchChatHistory = async () => {
    try {
        const response = await fetch(route('meeting.chat.index', props.meeting.room_id));
        if (response.ok) {
            const data = await response.json();
            chatMessages.value = data.messages || [];
            scrollToBottom();
        }
    } catch (e) {
        console.error("Failed to load chat history", e);
    }
};

const sendChatMessage = async () => {
    if (!newChatMessage.value.trim()) return;

    const text = newChatMessage.value.trim();
    newChatMessage.value = ''; // Optimistically clear input

    const token = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='))?.split('=')[1];

    try {
        const response = await fetch(route('meeting.chat.store', props.meeting.room_id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': decodeURIComponent(token || ''),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                sender_name: props.participantName,
                text: text,
                is_host: props.isHost || false
            })
        });

        if (response.ok) {
            const data = await response.json();
            chatMessages.value.push(data.message);
            scrollToBottom();

            // Broadcast to other participants via Jitsi Data Channels so they see it instantly
            broadcastToParticipants('CUSTOM_CHAT_MESSAGE', { message: data.message });
        }
    } catch (e) {
        console.error("Failed to send chat message", e);
        showToast('Failed to send message', 'error');
    }
};

const toggleChat = () => {
    isChatOpen.value = !isChatOpen.value;
    if (isChatOpen.value) {
        unreadChatCount.value = 0;
        scrollToBottom();
    }
};

// Minimization functions
const minimizeMeeting = () => {
    if (api.value) {
        isPreviewMuted.value = false;
        isMinimized.value = true;
        // Mute video when minimized to save bandwidth, keep audio active
        api.value.executeCommand('toggleVideo');
    }
};

const restoreMeeting = () => {
    isMinimized.value = false;
    if (api.value) {
        // Restore video when coming back
        api.value.executeCommand('toggleVideo');
    }
};

const togglePreviewMute = () => {
    isPreviewMuted.value = !isPreviewMuted.value;
    if (api.value) {
        api.value.executeCommand('toggleAudio');
    }
};

const leaveCallFromPreview = () => {
    if (confirm('Leave the meeting?')) {
        if (isRecording.value) stopRecording();
        if (api.value) {
            api.value.executeCommand('hangup');
            api.value.dispose();
        }
        router.visit(route('meeting.ended', props.meeting.room_id));
    }
};

// Recording functions
const toggleRecording = async () => {
    if (isRecording.value) {
        stopRecording();
    } else {
        await startRecording();
    }
};

const startRecording = async () => {
    try {
        const displayStream = await navigator.mediaDevices.getDisplayMedia({
            video: true,
            audio: true
        });

        let voiceStream = null;
        try {
            voiceStream = await navigator.mediaDevices.getUserMedia({
                audio: true,
                video: false
            });
        } catch (e) {
            console.warn("Microphone not available or permission denied", e);
        }

        displayStreamRef = displayStream;
        voiceStreamRef = voiceStream;

        const audioContext = new (window.AudioContext || (window as any).webkitAudioContext)();
        if (audioContext.state === 'suspended') {
            await audioContext.resume();
        }
        audioContextRef = audioContext;
        const dest = audioContext.createMediaStreamDestination();

        if (displayStream.getAudioTracks().length > 0) {
            const displaySource = audioContext.createMediaStreamSource(displayStream);
            displaySource.connect(dest);
        }

        if (voiceStream && voiceStream.getAudioTracks().length > 0) {
            const voiceSource = audioContext.createMediaStreamSource(voiceStream);
            voiceSource.connect(dest);
        }

        const videoStream = new MediaStream(displayStream.getVideoTracks());
        const audioStream = new MediaStream(dest.stream.getAudioTracks());

        let videoMimeType = 'video/webm';
        if (MediaRecorder.isTypeSupported('video/mp4')) {
            videoMimeType = 'video/mp4';
        } else if (MediaRecorder.isTypeSupported('video/webm;codecs=vp9')) {
            videoMimeType = 'video/webm;codecs=vp9';
        }

        let audioMimeType = 'audio/webm';
        if (MediaRecorder.isTypeSupported('audio/webm;codecs=opus')) {
            audioMimeType = 'audio/webm;codecs=opus';
        }

        videoRecorder = new MediaRecorder(videoStream, { mimeType: videoMimeType });
        audioRecorder = new MediaRecorder(audioStream, { mimeType: audioMimeType });

        videoChunks = [];
        audioChunks = [];

        videoRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) videoChunks.push(event.data);
        };

        audioRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) audioChunks.push(event.data);
        };

        let uploadPromises: Promise<any>[] = [];

        videoRecorder.onstop = () => {
            const videoBlob = new Blob(videoChunks, { type: videoMimeType });
            uploadPromises.push(uploadRecording(videoBlob, 'video'));
        };

        audioRecorder.onstop = async () => {
            const audioBlob = new Blob(audioChunks, { type: audioMimeType });
            uploadPromises.push(uploadRecording(audioBlob, 'audio'));

            // Wait for both to finish uploading
            try {
                await Promise.all(uploadPromises);
            } catch (err) {
                console.error('Upload error', err);
            }

            if (displayStreamRef) displayStreamRef.getTracks().forEach(track => track.stop());
            if (voiceStreamRef) voiceStreamRef.getTracks().forEach(track => track.stop());
            if (audioContextRef && audioContextRef.state !== 'closed') audioContextRef.close();

            displayStreamRef = null;
            voiceStreamRef = null;
            audioContextRef = null;
        };

        displayStream.getVideoTracks()[0].onended = () => {
            stopRecording();
        };

        videoRecorder.start();
        audioRecorder.start();
        isRecording.value = true;

        showToast('This meeting is being recorded.', 'error');
        playNotificationSound();
        broadcastToParticipants('RECORDING_STARTED');
    } catch (err) {
        console.error("Error starting screen recording", err);
        alert("Could not start recording. Please ensure permissions are granted.");
    }
};

const stopRecording = () => {
    if (videoRecorder && videoRecorder.state !== 'inactive') {
        videoRecorder.stop();
    }
    if (audioRecorder && audioRecorder.state !== 'inactive') {
        audioRecorder.stop();
    }
    isRecording.value = false;
    broadcastToParticipants('RECORDING_STOPPED');
};

const uploadRecording = async (blob: Blob, type: 'video' | 'audio') => {
    isUploading.value = true;
    const extension = type === 'video' ? 'mp4' : 'webm'; // video as mp4, audio as webm
    const CHUNK_SIZE = 1024 * 1024; // 1MB chunks
    const totalChunks = Math.ceil(blob.size / CHUNK_SIZE);

    try {
        const token = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='))?.split('=')[1];

        for (let i = 0; i < totalChunks; i++) {
            const start = i * CHUNK_SIZE;
            const end = Math.min(start + CHUNK_SIZE, blob.size);
            const chunk = blob.slice(start, end);

            const formData = new FormData();
            formData.append('recording', chunk, `meeting-${props.meeting.id}-recording.${extension}`);
            formData.append('chunk_index', i.toString());
            formData.append('total_chunks', totalChunks.toString());
            formData.append('file_type', type);

            const response = await fetch(route('meetings.upload-recording', props.meeting.id), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-XSRF-TOKEN': decodeURIComponent(token || ''),
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const errData = await response.json();
                throw new Error(errData.message || 'Upload failed');
            }
        }

        if (type === 'video') {
            showToast('Video recording uploaded successfully!', 'info');
        }
    } catch (err: any) {
        console.error('Error uploading recording', err);
        showToast('Failed to upload recording: ' + err.message, 'error');
    } finally {
        isUploading.value = false;
    }
};

// Jitsi initialization
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

        api.value.addEventListeners({
            readyToClose: () => leaveRoom(),
            videoConferenceLeft: () => leaveRoom(),
            videoConferenceJoined: () => {
                showToast('Welcome! This meeting is currently ongoing.', 'info');
            },
            participantJoined: (event: any) => {
                if (props.isHost) {
                    setTimeout(() => {
                        const msg = JSON.stringify({ type: 'MEETING_ONGOING', isRecording: isRecording.value });
                        api.value.executeCommand('sendEndpointTextMessage', event.id, msg);
                    }, 1500);
                }
            },
            endpointTextMessageReceived: (event: any) => {
                try {
                    const text = event.data?.eventData?.text || event.text || event.eventData?.text;
                    if (!text) return;
                    const data = JSON.parse(text);
                    if (data.type === 'RECORDING_STARTED') {
                        showToast('This meeting is being recorded.', 'error');
                        playNotificationSound();
                    } else if (data.type === 'RECORDING_STOPPED') {
                        showToast('Recording has stopped.', 'info');
                    } else if (data.type === 'MEETING_ONGOING') {
                        if (data.isRecording) {
                            showToast('This meeting is being recorded.', 'error');
                            playNotificationSound();
                        }
                    } else if (data.type === 'CUSTOM_CHAT_MESSAGE') {
                        chatMessages.value.push(data.message);
                        if (!isChatOpen.value) {
                            unreadChatCount.value++;
                            playNotificationSound();
                        } else {
                            scrollToBottom();
                        }
                    }
                } catch (e) {
                    console.log('Message received:', event);
                }
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
    fetchChatHistory();
});

onUnmounted(() => {
    // Only dispose if not minimized to keep connection alive
    if (api.value && !isMinimized.value) {
        api.value.dispose();
    }
});
</script>

<template>
    <Head :title="`Meeting: ${meeting.title}`" />
    <PageLoader />

    <!-- Toast UI -->
    <div v-if="toastMessage" :class="[
        'fixed top-20 right-6 px-4 py-3 rounded-lg shadow-xl border z-[10000] transition-all transform flex items-center gap-3',
        toastType === 'info' ? 'bg-slate-800 border-blue-500/30 text-blue-100' : 'bg-red-900/90 border-red-500/50 text-red-100'
    ]">
        <div :class="['w-2 h-2 rounded-full animate-pulse', toastType === 'info' ? 'bg-blue-500' : 'bg-red-500']"></div>
        <p class="text-sm font-medium">{{ toastMessage }}</p>
    </div>

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

            <!-- Host Panel -->
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

        <main class="flex-1 bg-black relative">
            <!-- Full Meeting View -->
            <div v-if="!isMinimized" class="absolute inset-0">
                <div ref="jitsiContainer" class="w-full h-full"></div>

                <!-- Minimize Button -->
                <button
                    @click="minimizeMeeting"
                    class="absolute bottom-4 right-4 z-50 bg-slate-900/90 backdrop-blur-md hover:bg-slate-800 rounded-full p-2 shadow-lg transition-all border border-white/10 group"
                    title="Minimize meeting"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300 group-hover:text-white">
                        <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
                    </svg>
                </button>

                <!-- Error State -->
                <div v-if="error" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-950">
                    <div class="text-red-500 mb-4 text-center">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>{{ error }}</p>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="!api && !error" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-950">
                    <div class="w-10 h-10 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-widest animate-pulse">
                        Initializing Secure Meeting...
                    </p>
                </div>
            </div>

            <!-- Floating Preview Window (Minimized Mode) -->
            <div
                v-else
                ref="floatingVideoRef"
                class="fixed bottom-6 right-6 w-80 h-48 bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-2xl border border-white/10 overflow-hidden z-[9999] transition-all hover:shadow-xl hover:scale-105 cursor-pointer group"
                @click="restoreMeeting"
            >
                <!-- Mini Preview Content -->
                <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                    <!-- Animated mic indicator -->
                    <div class="relative mb-3">
                        <div class="w-14 h-14 bg-blue-600/20 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400">
                                <rect x="2" y="5" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 5v14M8 5v14M3 9h4M3 15h4M17 9h4M17 15h4"/>
                            </svg>
                        </div>
                        <div v-if="!isPreviewMuted" class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-slate-900 animate-pulse"></div>
                        <div v-else class="absolute -bottom-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-slate-900"></div>
                    </div>

                    <p class="text-sm font-semibold text-white">{{ meeting.title }}</p>
                    <p class="text-[10px] text-slate-400 mt-1">
                        {{ isPreviewMuted ? 'Muted' : 'Live' }} • {{ participantName }}
                    </p>

                    <!-- Recording indicator -->
                    <div v-if="isRecording" class="mt-2 flex items-center gap-1">
                        <div class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></div>
                        <span class="text-[8px] text-red-400 font-mono">REC</span>
                    </div>
                </div>

                <!-- Hover Controls -->
                <!-- <div class="absolute bottom-0 left-0 right-0 flex justify-center gap-2 p-3 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-200">
                    <button
                        @click.stop="togglePreviewMute"
                        class="bg-slate-800/90 backdrop-blur-md rounded-full p-2 hover:bg-slate-700 transition transform hover:scale-110"
                        :class="isPreviewMuted ? 'text-red-400' : 'text-slate-300'"
                        :title="isPreviewMuted ? 'Unmute' : 'Mute'"
                    >
                        <svg v-if="isPreviewMuted" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                            <line x1="12" y1="19" x2="12" y2="23"/>
                            <line x1="8" y1="23" x2="16" y2="23"/>
                            <line x1="4" y1="4" x2="20" y2="20"/>
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                            <line x1="12" y1="19" x2="12" y2="23"/>
                            <line x1="8" y1="23" x2="16" y2="23"/>
                        </svg>
                    </button>

                    <button
                        @click.stop="restoreMeeting"
                        class="bg-blue-600 hover:bg-blue-700 rounded-full p-2 transition transform hover:scale-110"
                        title="Restore meeting"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
                        </svg>
                    </button>

                    <button
                        @click.stop="leaveCallFromPreview"
                        class="bg-red-600/80 hover:bg-red-600 rounded-full p-2 transition transform hover:scale-110"
                        title="Leave meeting"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18.36 6.64A9 9 0 0 1 20.77 15"/>
                            <path d="M6.16 6.16a9 9 0 1 0 12.68 12.68"/>
                            <path d="M12 2v4"/>
                            <path d="m2 2 20 20"/>
                        </svg>
                    </button>
                </div> -->
            </div>

            <!-- Custom Chat UI -->
            <div class="fixed bottom-20 left-6 z-[9999] max-h-[32rem] flex flex-col items-start pointer-events-none">
                <!-- Chat Modal -->
                <div
                    v-show="isChatOpen"
                    class="pointer-events-auto w-80 sm:w-96 h-[32rem] bg-slate-900/95 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl flex flex-col overflow-hidden mb-4 transition-all duration-300 origin-bottom-left"
                    :class="isChatOpen ? 'scale-100 opacity-100' : 'scale-95 opacity-0'"
                >
                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-white/5 flex items-center justify-between bg-slate-800/50">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <h3 class="font-bold text-white text-sm">Meeting Chat</h3>
                        </div>
                        <button @click="toggleChat" class="text-slate-400 hover:text-white p-1 rounded-md hover:bg-white/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Messages list -->
                    <div ref="chatContainerRef" class="flex-1 overflow-y-auto p-4 space-y-4 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
                        <div v-if="chatMessages.length === 0" class="text-center text-slate-500 text-xs mt-10">
                            No messages yet. Start the conversation!
                        </div>
                        <div v-for="msg in chatMessages" :key="msg.id" class="flex flex-col gap-1" :class="msg.sender_name === participantName ? 'items-end' : 'items-start'">
                            <div class="flex items-baseline gap-2" :class="msg.sender_name === participantName ? 'flex-row-reverse' : ''">
                                <span class="text-[10px] font-bold" :class="msg.is_host ? 'text-yellow-500' : 'text-slate-400'">{{ msg.sender_name }}</span>
                                <span class="text-[8px] text-slate-600">{{ new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}</span>
                            </div>
                            <div
                                class="px-3 py-2 rounded-2xl max-w-[85%] text-sm"
                                :class="msg.sender_name === participantName ? 'bg-blue-600 text-white rounded-tr-sm' : 'bg-slate-800 text-slate-200 border border-white/5 rounded-tl-sm'"
                            >
                                {{ msg.text }}
                            </div>
                        </div>
                    </div>

                    <!-- Input area -->
                    <form @submit.prevent="sendChatMessage" class="p-3 bg-slate-950/50 border-t border-white/5 flex gap-2">
                        <input
                            v-model="newChatMessage"
                            type="text"
                            placeholder="Type a message..."
                            class="flex-1 bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition"
                        />
                        <button
                            type="submit"
                            :disabled="!newChatMessage.trim()"
                            class="bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 disabled:text-slate-500 text-white rounded-xl px-3 py-2 transition flex items-center justify-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Floating Button -->
                <button
                    @click="toggleChat"
                    class="pointer-events-auto flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-500 text-white rounded-full shadow-lg shadow-blue-500/30 transition transform hover:scale-105 active:scale-95 border border-blue-400/20 relative"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>

                    <!-- Unread badge -->
                    <span v-if="unreadChatCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-slate-900 shadow-md animate-bounce">
                        {{ unreadChatCount > 9 ? '9+' : unreadChatCount }}
                    </span>
                </button>
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

/* Smooth animation for floating window */
.fixed.bottom-6.right-6 {
    animation: slideInRight 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100px) scale(0.8);
    }
    to {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
}

/* Hover scale effect */
.group:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease;
}
</style>
