<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    Shield, 
    GraduationCap, 
    Video, 
    ArrowRight,
    Fingerprint
} from 'lucide-vue-next';

const props = defineProps<{
    roles: any[];
}>();

const selectRole = (role: string) => {
    router.post(route('auth.role.select'), { role });
};

const getRoleIcon = (roleName: string) => {
    switch (roleName.toLowerCase()) {
        case 'supervisor': return Shield;
        case 'instructor': return GraduationCap;
        case 'editor': return Video;
        default: return Fingerprint;
    }
};

const getRoleColor = (roleName: string) => {
    switch (roleName.toLowerCase()) {
        case 'supervisor': return 'text-purple-500 bg-purple-500/10 border-purple-500/20';
        case 'instructor': return 'text-blue-500 bg-blue-500/10 border-blue-500/20';
        case 'editor': return 'text-green-500 bg-green-500/10 border-green-500/20';
        default: return 'text-slate-500 bg-slate-500/10 border-slate-500/20';
    }
};
</script>

<template>
    <Head title="Select Workspace" />
    
    <div class="min-h-screen bg-[#0a0c10] flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Glows -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px]"></div>

        <div class="w-full max-w-2xl relative z-10">
        <div class="text-center mb-12">
                <div class="flex items-center justify-center mb-4">
                    <img src="/images/logo.png" alt="Youth In Tech" class="w-20 h-20 object-contain drop-shadow-2xl" />
                </div>
                <h1 class="text-4xl font-black text-white tracking-tight">Choose Your Workspace</h1>
                <p class="text-slate-500 font-medium mt-2 text-lg">You have multiple roles assigned. Select one to proceed.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <button v-for="role in roles" :key="role.id" 
                        @click="selectRole(role.name)"
                        class="group p-8 bg-[#0d1117] border border-white/10 rounded-[2.5rem] text-left hover:border-blue-500/50 hover:bg-blue-600/[0.02] transition-all hover:-translate-y-2 shadow-2xl shadow-black/40">
                    <div :class="getRoleColor(role.name)" 
                         class="w-14 h-14 rounded-2xl border flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <component :is="getRoleIcon(role.name)" class="w-7 h-7" />
                    </div>
                    
                    <h3 class="text-xl font-black text-white capitalize mb-2">{{ role.name }}</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed mb-6">
                        Access the {{ role.name }} dashboard and management tools.
                    </p>

                    <div class="flex items-center gap-2 text-[10px] font-black text-blue-500 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                        Enter Workspace
                        <ArrowRight class="w-3.5 h-3.5" />
                    </div>
                </button>
            </div>

            <p class="text-center mt-12 text-xs font-medium text-slate-600">
                Logged in as <span class="text-white">{{ $page.props.auth.user.email }}</span>. 
                <Link :href="route('logout')" method="post" as="button" class="text-red-500 hover:text-red-400 font-black ml-1">Log Out</Link>
            </p>
        </div>
    </div>
</template>
