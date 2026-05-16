<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    Mail, 
    Lock,
    ArrowRight, 
    Fingerprint,
    ShieldCheck,
    Loader2
} from 'lucide-vue-next';

const step = ref<'login' | 'otp'>('login');

const loginForm = useForm({
    email: '',
});

const otpForm = useForm({
    email: '',
    code: '',
});

const sendOtp = () => {
    loginForm.post(route('auth.otp.send'), {
        onSuccess: () => {
            otpForm.email = loginForm.email;
            step.value = 'otp';
        },
    });
};

const verifyOtp = () => {
    otpForm.post(route('auth.otp.verify'));
};
</script>

<template>
    <Head title="Login" />
    
    <div class="min-h-screen bg-[#0a0c10] flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Glows -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px]"></div>

        <div class="w-full max-w-md relative z-10">
            <!-- Brand -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-600/10 border border-blue-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-blue-500/5">
                    <Fingerprint class="w-8 h-8 text-blue-500" />
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">Welcome Back</h1>
                <p class="text-slate-500 font-medium mt-1">Access your YIT Learning workspace.</p>
            </div>

            <div class="bg-[#0d1117] border border-white/10 rounded-[2.5rem] p-8 shadow-2xl shadow-black/40">
                <!-- Step 1: Login -->
                <div v-if="step === 'login'" class="space-y-6">
                    <div class="space-y-4">
                        <a :href="route('auth.google')" 
                           class="flex items-center justify-center gap-3 w-full bg-white text-black font-black py-3 rounded-2xl hover:bg-slate-200 transition-all active:scale-[0.98]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                            </svg>
                            Continue with Google
                        </a>
                        
                        <div class="relative flex items-center py-2">
                            <div class="flex-grow border-t border-white/5"></div>
                            <span class="flex-shrink mx-4 text-[10px] font-black text-slate-600 uppercase tracking-widest">or email sign-in</span>
                            <div class="flex-grow border-t border-white/5"></div>
                        </div>

                        <form @submit.prevent="sendOtp" class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Email Address</label>
                                <div class="relative">
                                    <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                                    <input v-model="loginForm.email" type="email" placeholder="name@company.com" required
                                           class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                                </div>
                                <p v-if="loginForm.errors.email" class="text-red-500 text-[10px] mt-1 font-bold">{{ loginForm.errors.email }}</p>
                            </div>

                            <button type="submit" :disabled="loginForm.processing"
                                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-3 rounded-2xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-2">
                                <Loader2 v-if="loginForm.processing" class="w-4 h-4 animate-spin" />
                                Send Magic Code
                                <ArrowRight class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Step 2: OTP -->
                <div v-else class="space-y-6">
                    <div class="text-center space-y-2">
                        <div class="w-12 h-12 bg-blue-600/10 border border-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <ShieldCheck class="w-6 h-6 text-blue-500" />
                        </div>
                        <h2 class="text-xl font-bold text-white">Enter Magic Code</h2>
                        <p class="text-xs text-slate-500">We've sent a 6-digit code to <span class="text-white font-bold">{{ loginForm.email }}</span></p>
                    </div>

                    <form @submit.prevent="verifyOtp" class="space-y-6">
                        <div>
                            <div class="flex justify-center gap-2">
                                <input v-model="otpForm.code" type="text" maxlength="6" placeholder="000000" required
                                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-4 text-center text-3xl font-black text-white tracking-[0.3em] focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                            </div>
                            <p v-if="otpForm.errors.code" class="text-center text-red-500 text-[10px] mt-2 font-bold">{{ otpForm.errors.code }}</p>
                        </div>

                        <button type="submit" :disabled="otpForm.processing"
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-3 rounded-2xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-2">
                            <Loader2 v-if="otpForm.processing" class="w-4 h-4 animate-spin" />
                            Verify & Continue
                        </button>

                        <button type="button" @click="step = 'login'" class="w-full text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">
                            Use a different email
                        </button>
                    </form>
                </div>
            </div>

            <p class="text-center mt-8 text-[11px] font-medium text-slate-600">
                New to YIT? 
                <Link :href="route('register')" class="text-blue-500 hover:text-blue-400 font-black ml-1">Create Account</Link>
            </p>
        </div>
    </div>
</template>
