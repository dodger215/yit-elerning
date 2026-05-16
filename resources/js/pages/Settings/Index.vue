<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import { 
    Settings, 
    Bell, 
    Shield, 
    Smartphone,
    Globe,
    CreditCard,
    ChevronRight
} from 'lucide-vue-next';

const settingsGroups = [
    {
        title: 'Account',
        icon: Smartphone,
        items: [
            { label: 'Security & Password', sub: 'Manage your login methods', icon: Shield },
            { label: 'Notifications', sub: 'Control email and SMS alerts', icon: Bell },
            { label: 'Localization', sub: 'Change language and timezone', icon: Globe },
        ]
    },
    {
        title: 'Billing & Platform',
        icon: CreditCard,
        items: [
            { label: 'Subscription', sub: 'Manage your plan and billing', icon: CreditCard },
            { label: 'Appearance', sub: 'Customize dashboard theme', icon: Settings },
        ]
    }
];
</script>

<template>
    <AppLayout>
        <Head title="Settings" />

        <div class="max-w-[800px] mx-auto space-y-8">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight">Settings</h1>
                <p class="text-slate-500 font-medium mt-1">Configure your experience on the YIT platform.</p>
            </div>

            <div class="space-y-8">
                <div v-for="group in settingsGroups" :key="group.title" class="space-y-4">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] px-4">{{ group.title }}</h3>
                    <div class="space-y-2">
                        <button v-for="item in group.items" :key="item.label" 
                                class="w-full flex items-center justify-between p-6 bg-[#0d1117] border border-white/5 rounded-[2rem] hover:border-blue-500/30 hover:bg-white/[0.02] transition-all group">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <component :is="item.icon" class="w-6 h-6 text-slate-400 group-hover:text-blue-400" />
                                </div>
                                <div class="text-left">
                                    <h4 class="font-bold text-white group-hover:text-blue-400 transition-colors">{{ item.label }}</h4>
                                    <p class="text-xs text-slate-500 font-medium">{{ item.sub }}</p>
                                </div>
                            </div>
                            <ChevronRight class="w-5 h-5 text-slate-600 group-hover:text-blue-500 transform translate-x-0 group-hover:translate-x-1 transition-all" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="p-8 bg-red-500/5 border border-red-500/10 rounded-[2.5rem] mt-12">
                <h3 class="text-red-500 font-black text-lg mb-2">Danger Zone</h3>
                <p class="text-slate-500 text-sm mb-6">Permanently delete your account and all associated data. This action cannot be undone.</p>
                <button class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl text-sm transition-all active:scale-95">
                    Delete My Account
                </button>
            </div>
        </div>
    </AppLayout>
</template>
