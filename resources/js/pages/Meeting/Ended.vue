<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PageLoader from '@/Components/PageLoader.vue';

const props = defineProps<{
    meeting: any;
    returnUrl: string;
    isGuest?: boolean;
}>();

const COUNTDOWN = 12;
const count = ref(COUNTDOWN);
let timer: ReturnType<typeof setInterval> | null = null;

const circumference = 2 * Math.PI * 52; // r=52 circle
const dashOffset = computed(() => circumference - (count.value / COUNTDOWN) * circumference);

const revisit = () => {
    if (timer) clearInterval(timer);
    router.visit(route('meeting.join', props.meeting.room_id));
};

const leave = () => {
    if (timer) clearInterval(timer);
    if (props.isGuest) {
        router.visit('/');
        return;
    }
    // Guard: never redirect back to the meeting room itself
    const meetingRoomPath = `/meeting/${props.meeting.room_id}`;
    const target = props.returnUrl && !props.returnUrl.includes(meetingRoomPath)
        ? props.returnUrl
        : route('meetings.index');
    router.visit(target);
};

onMounted(() => {
    timer = setInterval(() => {
        count.value--;
        if (count.value <= 0) {
            clearInterval(timer!);
            leave();
        }
    }, 1000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});
</script>

<template>
    <Head :title="`Meeting Ended — ${meeting.title}`" />
    <PageLoader />

    <div class="ended-page">
        <!-- Animated background -->
        <div class="ended-bg"></div>
        <div class="ended-radial"></div>

        <div class="ended-card">
            <!-- Icon -->
            <div class="ended-icon-wrap">
                <svg class="ended-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 3.75 18 6m0 0 2.25 2.25M18 6l2.25-2.25M18 6l-2.25 2.25m-10.5 6 4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                </svg>
            </div>

            <h1 class="ended-title">Meeting Ended</h1>
            <p class="ended-subtitle">
                <span class="ended-meeting-name">{{ meeting.title }}</span>
                has ended. Thanks for joining!
            </p>

            <!-- Countdown Ring -->
            <div class="countdown-ring-wrap">
                <svg class="countdown-svg" viewBox="0 0 120 120">
                    <!-- Track -->
                    <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="6" />
                    <!-- Progress arc -->
                    <circle
                        cx="60" cy="60" r="52"
                        fill="none"
                        stroke="url(#countGrad)"
                        stroke-width="6"
                        stroke-linecap="round"
                        :stroke-dasharray="circumference"
                        :stroke-dashoffset="dashOffset"
                        class="countdown-arc"
                    />
                    <defs>
                        <linearGradient id="countGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#60a5fa"/>
                            <stop offset="100%" stop-color="#c084fc"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="countdown-number-wrap">
                    <span class="countdown-number">{{ count }}</span>
                    <span class="countdown-label">secs</span>
                </div>
            </div>

            <p class="redirect-note">
                Redirecting you {{ isGuest ? 'to the homepage' : 'back' }} in <strong>{{ count }}</strong> seconds…
            </p>

            <!-- Actions -->
            <div class="ended-actions">
                <button @click="revisit" class="btn-revisit">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    Rejoin Meeting
                </button>
                <button @click="leave" class="btn-leave">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                    </svg>
                    {{ isGuest ? 'Go to Homepage' : 'Return to Dashboard' }}
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.ended-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #060d1a;
    position: relative;
    overflow: hidden;
    font-family: 'Outfit', 'Inter', sans-serif;
}

.ended-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 20% 30%, rgba(59,130,246,0.12) 0%, transparent 55%),
        radial-gradient(ellipse at 80% 70%, rgba(192,132,252,0.1) 0%, transparent 55%);
    pointer-events: none;
}

.ended-radial {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, rgba(239,68,68,0.06) 0%, transparent 60%);
    pointer-events: none;
}

.ended-card {
    position: relative;
    z-index: 2;
    background: rgba(15,23,42,0.5);
    backdrop-filter: blur(32px) saturate(150%);
    -webkit-backdrop-filter: blur(32px) saturate(150%);
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.15), 0 30px 60px rgba(0,0,0,0.5);
    border-radius: 32px;
    padding: 56px 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    max-width: 480px;
    width: 90%;
    text-align: center;
    animation: card-in 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
}

@keyframes card-in {
    from { opacity: 0; transform: translateY(30px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.ended-icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1), 0 0 30px rgba(239,68,68,0.15);
}

.ended-icon {
    width: 34px;
    height: 34px;
    color: #f87171;
}

.ended-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin: 0;
    letter-spacing: -0.5px;
}

.ended-subtitle {
    font-size: 15px;
    color: rgba(255,255,255,0.55);
    margin: 0;
    line-height: 1.6;
    max-width: 340px;
}

.ended-meeting-name {
    color: #93c5fd;
    font-weight: 700;
}

/* Countdown Ring */
.countdown-ring-wrap {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 8px 0;
}

.countdown-svg {
    width: 120px;
    height: 120px;
    transform: rotate(-90deg);
}

.countdown-arc {
    transition: stroke-dashoffset 1s linear;
}

.countdown-number-wrap {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
}

.countdown-number {
    font-size: 36px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1;
    font-family: 'Space Grotesk', sans-serif;
}

.countdown-label {
    font-size: 10px;
    font-weight: 700;
    color: rgba(255,255,255,0.4);
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

.redirect-note {
    font-size: 13px;
    color: rgba(255,255,255,0.45);
    margin: 0;
}
.redirect-note strong { color: rgba(255,255,255,0.75); }

/* Action Buttons */
.ended-actions {
    display: flex;
    gap: 12px;
    width: 100%;
    margin-top: 8px;
}

.btn-revisit, .btn-leave {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 52px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-revisit {
    background: rgba(59,130,246,0.15);
    color: #93c5fd;
    border: 1px solid rgba(59,130,246,0.3);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
}
.btn-revisit:hover {
    background: rgba(59,130,246,0.25);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2), 0 8px 20px rgba(59,130,246,0.2);
}

.btn-leave {
    background: rgba(255,255,255,0.05);
    color: rgba(255,255,255,0.6);
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.08);
}
.btn-leave:hover {
    background: rgba(255,255,255,0.1);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.15), 0 8px 16px rgba(0,0,0,0.2);
}

.btn-icon { width: 16px; height: 16px; flex-shrink: 0; }

@media (max-width: 480px) {
    .ended-card { padding: 40px 24px; }
    .ended-title { font-size: 26px; }
    .ended-actions { flex-direction: column; }
}
</style>
