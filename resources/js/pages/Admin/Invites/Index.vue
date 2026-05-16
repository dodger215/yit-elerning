<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import { 
    Mail, 
    Clock, 
    Copy, 
    RefreshCcw,
    Plus
} from 'lucide-vue-next';

const props = defineProps<{
    invites: any;
}>();

const copyToClipboard = (token: string) => {
    const url = `${window.location.origin}/invite/${token}`;
    navigator.clipboard.writeText(url);
    alert('Invite link copied to clipboard!');
};
</script>

<template>
    <AppLayout>
        <Head title="Pending Invites" />

        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Onboarding Pipeline</h1>
                    <p class="text-xs text-slate-500 font-medium">Monitor and manage pending staff invitations.</p>
                </div>
            </div>

            <Card padding="false">
                <DataTable :headers="['Target Email', 'Assigned Role', 'Expires', 'Actions']" :items="invites.data">
                    <template #row="{ item }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-slate-400">
                                    <Mail class="w-4 h-4" />
                                </div>
                                <span class="text-sm font-bold text-white">{{ item.email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-md bg-blue-500/10 text-blue-400 text-[9px] font-black uppercase tracking-widest border border-blue-500/20">
                                {{ item.metadata?.role || 'regular' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                                <Clock class="w-3.5 h-3.5" />
                                {{ new Date(item.expires_at).toLocaleDateString() }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="copyToClipboard(item.token)" 
                                        class="p-2 hover:bg-white/5 rounded-lg text-slate-500 hover:text-white transition-all" title="Copy Link">
                                    <Copy class="w-4 h-4" />
                                </button>
                                <button class="p-2 hover:bg-blue-500/10 rounded-lg text-slate-500 hover:text-blue-500 transition-all" title="Resend Invite">
                                    <RefreshCcw class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </template>
                </DataTable>

                <div class="p-4 border-t border-white/5 flex items-center justify-between">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                        {{ invites.total }} Pending Invitations
                    </p>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
