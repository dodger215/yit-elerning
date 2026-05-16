<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import { 
    Search, 
    Filter, 
    Shield, 
    UserMinus, 
    Mail,
    Plus,
    X,
    Check
} from 'lucide-vue-next';
import Modal from '@/components/Modal.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    users: any;
    roles: any[];
    filters: any;
}>();

// Invite Form
const showInviteModal = ref(false);
const inviteForm = useForm({
    email: '',
    role: 'instructor'
});

const submitInvite = () => {
    inviteForm.post(route('admin.invite'), {
        onSuccess: () => {
            showInviteModal.value = false;
            inviteForm.reset();
        }
    });
};

// Role Edit Form
const showRoleModal = ref(false);
const selectedUser = ref<any>(null);
const roleForm = useForm({
    roles: [] as string[]
});

const openRoleModal = (user: any) => {
    selectedUser.value = user;
    roleForm.roles = user.roles.map((r: any) => r.name);
    showRoleModal.value = true;
};

const submitRoles = () => {
    roleForm.put(route('admin.users.roles.update', selectedUser.value.id), {
        onSuccess: () => showRoleModal.value = false
    });
};

const search = ref(props.filters.search || '');
const roleFilter = ref(props.filters.role || '');

const handleSearch = () => {
    router.get(route('admin.users.index'), { search: search.value, role: roleFilter.value }, { preserveState: true });
};

const toggleStatus = (user: any) => {
    router.post(route('admin.users.toggle', user.id));
};

const deleteUser = (user: any) => {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        router.delete(route('admin.users.destroy', user.id));
    }
};
</script>

<template>
    <AppLayout>
        <Head title="User Management" />

        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">User Management</h1>
                    <p class="text-xs text-slate-500 font-medium">Manage platform users, roles and account statuses.</p>
                </div>
                
                <button @click="showInviteModal = true" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                    <Plus class="w-4 h-4" />
                    Invite Staff
                </button>
            </div>

            <Card padding="false">
                <!-- Filters Bar -->
                <div class="p-4 border-b border-white/5 flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div class="relative w-full sm:w-64">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                        <input v-model="search" @keyup.enter="handleSearch" type="text" placeholder="Search users..." 
                               class="bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-xs w-full focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                    </div>
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <select v-model="roleFilter" @change="handleSearch"
                                class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all flex-1 sm:flex-none">
                            <option value="">All Roles</option>
                            <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                        </select>
                        <button class="p-2 bg-white/5 border border-white/10 rounded-xl text-slate-400 hover:text-white transition-colors">
                            <Filter class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <DataTable :headers="['User', 'Roles', 'Status', 'Last Active', 'Actions']" :items="users.data">
                    <template #row="{ item }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center font-bold text-blue-500 text-xs">
                                    {{ item.first_name?.[0] }}{{ item.last_name?.[0] }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ item.first_name }} {{ item.last_name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ item.email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                <button @click="openRoleModal(item)" v-for="role in item.roles" :key="role.id" 
                                      class="px-2 py-0.5 rounded-md bg-white/5 border border-white/10 text-slate-400 text-[9px] font-black uppercase tracking-widest hover:border-blue-500/50 hover:text-blue-400 transition-all">
                                    {{ role.name }}
                                </button>
                                <button @click="openRoleModal(item)" class="p-0.5 rounded-md bg-blue-500/10 text-blue-500 hover:bg-blue-500/20 transition-all">
                                    <Plus class="w-3 h-3" />
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <button @click="toggleStatus(item)" 
                                    class="flex items-center gap-2 group transition-all"
                                    :title="item.is_active ? 'Deactivate' : 'Activate'">
                                <div :class="item.is_active ? 'bg-green-500' : 'bg-slate-600'" 
                                     class="w-1.5 h-1.5 rounded-full shadow-[0_0_8px_rgba(34,197,94,0.4)] transition-colors"></div>
                                <span :class="item.is_active ? 'text-green-400' : 'text-slate-500'" class="text-xs font-bold uppercase tracking-tighter">
                                    {{ item.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                            {{ item.last_active_at || 'Never' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openRoleModal(item)" class="p-1.5 hover:bg-white/5 rounded-lg text-slate-500 hover:text-white transition-all" title="Manage Roles">
                                    <Shield class="w-4 h-4" />
                                </button>
                                <button @click="deleteUser(item)" class="p-1.5 hover:bg-red-500/10 rounded-lg text-slate-500 hover:text-red-500 transition-all" title="Delete User">
                                    <UserMinus class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </template>
                </DataTable>

                <div class="p-4 border-t border-white/5 flex items-center justify-between">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                        Showing {{ users.from }}-{{ users.to }} of {{ users.total }} users
                    </p>
                    <div class="flex gap-2">
                        <Link v-if="users.prev_page_url" :href="users.prev_page_url" class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-slate-400 hover:text-white transition-all">Prev</Link>
                        <Link v-if="users.next_page_url" :href="users.next_page_url" class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-slate-400 hover:text-white transition-all">Next</Link>
                    </div>
                </div>
            </Card>
        </div>

        <!-- Invite User Modal -->
        <Modal :show="showInviteModal" title="Invite Staff Member" @close="showInviteModal = false">
            <form @submit.prevent="submitInvite" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Email Address</label>
                    <input v-model="inviteForm.email" type="email" placeholder="staff@yit.com"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all" required>
                    <p v-if="inviteForm.errors.email" class="text-red-500 text-[10px] mt-1 font-bold">{{ inviteForm.errors.email }}</p>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Assigned Role</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button v-for="role in roles.filter(r => r.name !== 'regular')" :key="role.id"
                                type="button"
                                @click="inviteForm.role = role.name"
                                :class="inviteForm.role === role.name ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-600/20' : 'bg-white/5 text-slate-400 border-white/10 hover:bg-white/10'"
                                class="px-3 py-2 rounded-xl border text-[10px] font-black uppercase tracking-widest transition-all">
                            {{ role.name }}
                        </button>
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showInviteModal = false" class="flex-1 px-4 py-2 bg-white/5 text-slate-300 font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" :disabled="inviteForm.processing" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-500 transition-all disabled:opacity-50">
                        {{ inviteForm.processing ? 'Sending...' : 'Send Invite' }}
                    </button>
                </div>
            </form>
        </Modal>

        <!-- Manage Roles Modal -->
        <Modal :show="showRoleModal" :title="`Manage Roles: ${selectedUser?.first_name}`" @close="showRoleModal = false">
            <div class="space-y-4">
                <p class="text-xs text-slate-400 mb-4">Select the roles to assign to this user. They will gain all associated permissions immediately.</p>
                
                <div class="space-y-2">
                    <button v-for="role in roles" :key="role.id"
                            @click="roleForm.roles.includes(role.name) ? roleForm.roles = roleForm.roles.filter(r => r !== role.name) : roleForm.roles.push(role.name)"
                            class="w-full flex items-center justify-between p-3 rounded-2xl border transition-all"
                            :class="roleForm.roles.includes(role.name) ? 'bg-blue-600/10 border-blue-500/50 text-white' : 'bg-white/5 border-white/10 text-slate-500 hover:bg-white/10'">
                        <div class="flex items-center gap-3">
                            <Shield class="w-4 h-4" :class="roleForm.roles.includes(role.name) ? 'text-blue-400' : 'text-slate-600'" />
                            <span class="text-xs font-bold uppercase tracking-widest">{{ role.name }}</span>
                        </div>
                        <div v-if="roleForm.roles.includes(role.name)" class="w-5 h-5 rounded-full bg-blue-600 flex items-center justify-center">
                            <Check class="w-3 h-3 text-white" />
                        </div>
                    </button>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showRoleModal = false" class="flex-1 px-4 py-2 bg-white/5 text-slate-300 font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button @click="submitRoles" :disabled="roleForm.processing" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-500 transition-all disabled:opacity-50">
                        {{ roleForm.processing ? 'Saving...' : 'Update Roles' }}
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
