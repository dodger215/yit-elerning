<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    User, 
    Mail, 
    ArrowRight, 
    AtSign,
    Loader2
} from 'lucide-vue-next';

const props = defineProps<{
    email?: string;
}>();

const form = useForm({
    email: props.email || '',
    username: '',
    first_name: '',
    last_name: '',
});

const submit = () => {
    form.post(route('register.store'));
};
</script>

<template>
    <Head title="Create Account" />
    
    <div class="min-h-screen bg-[#0a0c10] flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Glows -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px]"></div>

        <div class="w-full max-w-md relative z-10">
            <!-- Brand -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-4">
                    <img src="/images/logo.png" alt="Youth In Tech" class="w-20 h-20 object-contain drop-shadow-2xl rounded-lg" />
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">Join YIT Learning</h1>
                <p class="text-slate-500 font-medium mt-1">Start your learning journey today.</p>
            </div>

            <div class="bg-[#0d1117] border border-white/10 rounded-[2.5rem] p-8 shadow-2xl shadow-black/40">
                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Email (Read-only if coming from OTP) -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Email Address</label>
                        <div class="relative">
                            <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                            <input v-model="form.email" type="email" placeholder="name@company.com" 
                                   :readonly="!!email"
                                   :class="email ? 'opacity-50 cursor-not-allowed' : ''"
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                        </div>
                        <p v-if="form.errors.email" class="text-red-500 text-[10px] mt-1 font-bold">{{ form.errors.email }}</p>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Unique Username</label>
                        <div class="relative">
                            <AtSign class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                            <input v-model="form.username" type="text" placeholder="ked_learner" 
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                        </div>
                        <p v-if="form.errors.username" class="text-red-500 text-[10px] mt-1 font-bold">{{ form.errors.username }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">First Name</label>
                            <input v-model="form.first_name" type="text" placeholder="Ked" 
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                            <p v-if="form.errors.first_name" class="text-red-500 text-[10px] mt-1 font-bold">{{ form.errors.first_name }}</p>
                        </div>
                        <!-- Last Name -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Last Name</label>
                            <input v-model="form.last_name" type="text" placeholder="Enos" 
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                            <p v-if="form.errors.last_name" class="text-red-500 text-[10px] mt-1 font-bold">{{ form.errors.last_name }}</p>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" :disabled="form.processing"
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-3 rounded-2xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-2">
                            <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                            Create Workspace
                            <ArrowRight class="w-4 h-4" />
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center mt-8 text-[11px] font-medium text-slate-600">
                Already have an account? 
                <Link :href="route('login')" class="text-blue-500 hover:text-blue-400 font-black ml-1">Log In</Link>
            </p>
        </div>
    </div>
</template>
