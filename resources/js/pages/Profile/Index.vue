<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import { 
    User, 
    Mail, 
    Shield, 
    Calendar,
    Settings,
    Edit3,
    CheckCircle
} from 'lucide-vue-next';

defineProps<{
    user: any;
}>();
</script>

<template>
    <AppLayout>
        <Head title="My Profile" />

        <div class="max-w-[1000px] mx-auto space-y-8">
            <!-- Header/Cover -->
            <div class="relative h-48 rounded-[2.5rem] bg-gradient-to-r from-blue-600/20 to-purple-600/20 border border-white/5 overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(59,130,246,0.1),transparent)]"></div>
            </div>

            <div class="px-8 -mt-20 relative z-10 flex flex-col md:flex-row items-end gap-6">
                <div class="w-32 h-32 rounded-[2.5rem] bg-[#0d1117] border-4 border-[#0a0c10] p-1 shadow-2xl">
                    <div class="w-full h-full rounded-[2.2rem] bg-slate-800 flex items-center justify-center font-black text-4xl text-blue-500">
                        {{ user.first_name?.[0] }}
                    </div>
                </div>
                <div class="flex-grow pb-2">
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-black text-white tracking-tight">{{ user.first_name }} {{ user.last_name }}</h1>
                        <CheckCircle class="w-5 h-5 text-blue-500" />
                    </div>
                    <p class="text-slate-500 font-medium">@{{ user.username || user.email.split('@')[0] }}</p>
                </div>
                <button class="flex items-center gap-2 px-6 py-2.5 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold text-sm border border-white/10 transition-all active:scale-95 mb-2">
                    <Edit3 class="w-4 h-4" />
                    Edit Profile
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar -->
                <div class="space-y-6">
                    <Card title="About Me">
                        <p class="text-sm text-slate-400 leading-relaxed">
                            {{ user.bio || 'No bio provided. Tell us about yourself!' }}
                        </p>
                    </Card>

                    <Card title="Badges & Roles">
                        <div class="flex flex-wrap gap-2">
                            <span v-for="role in user.roles" :key="role" 
                                  class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-widest border border-blue-500/20">
                                {{ role }}
                            </span>
                        </div>
                    </Card>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <Card title="Personal Information">
                        <div class="space-y-6 py-2">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center shrink-0">
                                    <Mail class="w-5 h-5 text-slate-400" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Email Address</p>
                                    <p class="text-sm font-bold text-white">{{ user.email }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center shrink-0">
                                    <Shield class="w-5 h-5 text-slate-400" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Primary Role</p>
                                    <p class="text-sm font-bold text-white capitalize">{{ user.roles?.[0] || 'Member' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center shrink-0">
                                    <Calendar class="w-5 h-5 text-slate-400" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Member Since</p>
                                    <p class="text-sm font-bold text-white">May 2026</p>
                                </div>
                            </div>
                        </div>
                    </Card>

                    <Card title="Recent Activity">
                        <div class="text-center py-12">
                            <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4">
                                <Settings class="w-6 h-6 text-slate-600" />
                            </div>
                            <p class="text-slate-500 text-sm italic">Activity logs are loading...</p>
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
