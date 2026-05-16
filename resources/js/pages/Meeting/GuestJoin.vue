<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { 
    Video, 
    User, 
    ArrowRight,
    ShieldCheck,
    Globe
} from 'lucide-vue-next';

const props = defineProps<{
    meeting: any;
}>();

const form = useForm({
    guest_name: '',
});

const submit = () => {
    // Redirect to the same URL but with the guest_name query parameter
    window.location.href = route('meeting.join', { 
        room_id: props.meeting.room_id, 
        guest_name: form.guest_name 
    });
};
</script>

<template>
    <Head :title="'Join Meeting: ' + meeting.title" />

    <div class="min-h-screen bg-[#0a0c10] flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Glow -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-600/10 rounded-full blur-[120px]"></div>

        <div class="w-full max-w-[480px] relative z-10">
            <div class="bg-[#0d1117] border border-white/10 rounded-[2.5rem] p-10 shadow-2xl shadow-black/60 text-center">
                
                <div class="w-20 h-20 bg-blue-600/10 border border-blue-500/20 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
                    <Globe class="w-10 h-10 text-blue-500" />
                </div>

                <h1 class="text-3xl font-black text-white tracking-tight mb-2">Public Meeting</h1>
                <p class="text-slate-500 font-medium mb-8">You are joining <span class="text-white font-bold">{{ meeting.title }}</span> as a guest.</p>

                <form @submit.prevent="submit" class="space-y-6 text-left">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">What's your display name?</label>
                        <div class="relative group">
                            <User class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500 group-focus-within:text-blue-500 transition-colors" />
                            <input v-model="form.guest_name" type="text" placeholder="e.g. John Doe"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all placeholder:text-slate-600"
                                   required autofocus>
                        </div>
                        <p class="text-[10px] text-slate-600 mt-3 italic text-center font-medium">This name will be visible to everyone in the meeting room.</p>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-4 rounded-2xl flex items-center justify-center gap-2 transition-all active:scale-[0.98] shadow-xl shadow-blue-600/20">
                        Join Meeting Now
                        <ArrowRight class="w-5 h-5" />
                    </button>
                </form>

                <div class="mt-10 pt-8 border-t border-white/5 flex items-center justify-center gap-2">
                    <ShieldCheck class="w-4 h-4 text-slate-600" />
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">End-to-End Encrypted Session</span>
                </div>
            </div>

            <p class="text-center text-slate-600 text-xs mt-8 font-medium">
                Want to host your own meetings? 
                <Link href="/register" class="text-blue-500 hover:underline font-bold">Create an account</Link>
            </p>
        </div>
    </div>
</template>
