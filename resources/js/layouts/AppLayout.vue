<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Users, 
    GraduationCap, 
    ShieldCheck, 
    Video, 
    BookOpen, 
    MessageSquare, 
    Film, 
    Upload, 
    Compass, 
    BarChart3, 
    FileText, 
    PieChart, 
    TrendingUp,
    User as UserIcon,
    Settings,
    Bell,
    Search,
    Menu,
    X,
    ChevronDown,
    LogOut,
    Globe,
    ArrowRight
} from 'lucide-vue-next';

const page = usePage();
const navigation = computed(() => page.props.navigation as any[]);
const user = computed(() => page.props.auth.user as any);
const activePublicMeetings = computed(() => (page.props.auth as any).active_public_meetings as any[]);
const activeRole = computed(() => page.props.auth.active_role as string);
const userRoles = computed<string[]>(() => {
    const roles = (page.props.auth.user as any)?.roles;
    if (!roles) return [];
    // Roles come as a plain array of strings from the backend
    return Array.isArray(roles) ? roles : [];
});

const isSidebarOpen = ref(true);
const isMobileMenuOpen = ref(false);
const dismissPopup = ref(false);
const isRoleMenuOpen = ref(false);
const isSwitchingRole = ref(false);

const activeMeeting = computed(() => {
    if (dismissPopup.value || !activePublicMeetings.value?.length) return null;
    return activePublicMeetings.value[0];
});

const switchRole = (roleName: string) => {
    if (roleName === activeRole.value || isSwitchingRole.value) return;
    isSwitchingRole.value = true;
    isRoleMenuOpen.value = false;
    router.post(route('auth.role.select'), { role: roleName }, {
        onFinish: () => { isSwitchingRole.value = false; }
    });
};

const roleColors: Record<string, string> = {
    supervisor: 'text-purple-400 bg-purple-500/10 border-purple-500/20',
    instructor: 'text-blue-400 bg-blue-500/10 border-blue-500/20',
    editor: 'text-green-400 bg-green-500/10 border-green-500/20',
    student: 'text-orange-400 bg-orange-500/10 border-orange-500/20',
};

const icons: Record<string, any> = {
    LayoutDashboard, Users, GraduationCap, ShieldCheck, Video, 
    BookOpen, MessageSquare, Film, Upload, Compass, 
    BarChart3, FileText, PieChart, TrendingUp, UserIcon, Settings
};

const toggleSidebar = () => isSidebarOpen.value = !isSidebarOpen.value;
</script>

<template>
    <div class="min-h-screen bg-[#0a0c10] text-slate-200 selection:bg-blue-500/30 selection:text-blue-200">
        <!-- Sidebar Overlay (Mobile) -->
        <div v-if="isMobileMenuOpen" 
             @click="isMobileMenuOpen = false"
             class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="[
            'fixed top-0 left-0 z-50 h-full bg-[#0d1117] border-r border-white/5 transition-all duration-300 ease-in-out lg:translate-x-0',
            isSidebarOpen ? 'w-64' : 'w-20',
            isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'
        ]">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center h-16 px-4 border-b border-white/5">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <img src="/images/logo.png" alt="Youth In Tech" class="w-9 h-9 shrink-0 object-contain" />
                        <span v-if="isSidebarOpen" class="font-black text-base tracking-tight text-white whitespace-nowrap">Youth In <span class="text-blue-400">Tech</span></span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-hide">
                    <div v-for="(item, idx) in navigation" :key="idx" class="space-y-1">
                        <!-- Parent Item -->
                        <div v-if="!item.children">
                            <Link :href="route(item.route)" 
                                  class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                  :class="route().current(item.route) ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'">
                                <component :is="icons[item.icon]" class="w-5 h-5 shrink-0" />
                                <span v-if="isSidebarOpen" class="font-medium text-sm">{{ item.label }}</span>
                                
                                <!-- Tooltip for collapsed -->
                                <div v-if="!isSidebarOpen" class="absolute left-full ml-4 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-[100]">
                                    {{ item.label }}
                                </div>
                            </Link>
                        </div>

                        <!-- Nested Items -->
                        <div v-else class="space-y-1">
                            <div class="flex items-center gap-3 px-3 py-2.5 text-slate-500 font-semibold text-[10px] uppercase tracking-[0.2em] mt-4 mb-1">
                                <component :is="icons[item.icon]" class="w-4 h-4 shrink-0" v-if="!isSidebarOpen" />
                                <span v-if="isSidebarOpen">{{ item.label }}</span>
                            </div>
                            <Link v-for="(child, cIdx) in item.children" :key="cIdx"
                                  :href="route(child.route)"
                                  class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                  :class="route().current(child.route) ? 'bg-blue-600/10 text-blue-400 border border-blue-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'">
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover:bg-blue-500 shrink-0 ml-1.5 transition-colors"
                                     :class="{'bg-blue-500': route().current(child.route)}"></div>
                                <span v-if="isSidebarOpen" class="font-medium text-sm">{{ child.label }}</span>
                            </Link>
                        </div>
                    </div>
                </nav>

                <!-- User Footer -->
                <div class="p-4 border-t border-white/5 bg-white/[0.02]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center font-bold text-blue-500 shrink-0">
                            {{ user.first_name?.[0] }}{{ user.last_name?.[0] }}
                        </div>
                        <div v-if="isSidebarOpen" class="min-w-0">
                            <p class="text-sm font-bold text-white truncate">{{ user.first_name }} {{ user.last_name }}</p>
                            <p class="text-[10px] text-blue-500 font-black uppercase tracking-widest truncate">{{ $page.props.auth.active_role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div :class="[
            'transition-all duration-300 ease-in-out',
            isSidebarOpen ? 'lg:ml-64' : 'lg:ml-20'
        ]">
            <!-- Navbar -->
            <header class="sticky top-0 z-30 h-16 bg-[#0a0c10]/80 backdrop-blur-xl border-b border-white/5 px-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="toggleSidebar" class="hidden lg:block p-2 hover:bg-white/5 rounded-lg text-slate-400 transition-colors">
                        <Menu v-if="!isSidebarOpen" class="w-5 h-5" />
                        <X v-else class="w-5 h-5" />
                    </button>
                    <button @click="isMobileMenuOpen = true" class="lg:hidden p-2 hover:bg-white/5 rounded-lg text-slate-400 transition-colors">
                        <Menu class="w-5 h-5" />
                    </button>
                    
                    <div class="relative hidden sm:block">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                        <input type="text" placeholder="Search resources..." 
                               class="bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-1.5 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Role Switcher -->
                    <div v-if="userRoles.length > 1" class="relative">
                        <!-- Backdrop to close dropdown -->
                        <div v-if="isRoleMenuOpen" @click="isRoleMenuOpen = false" class="fixed inset-0 z-[55]"></div>

                        <button
                            @click.stop="isRoleMenuOpen = !isRoleMenuOpen"
                            class="relative z-[56] flex items-center gap-2 px-3 py-1.5 rounded-xl border transition-all text-xs font-black uppercase tracking-widest"
                            :class="roleColors[activeRole] ?? 'text-slate-400 bg-white/5 border-white/10'"
                        >
                            <ShieldCheck class="w-3.5 h-3.5" />
                            <span class="hidden sm:block">{{ activeRole }}</span>
                            <ChevronDown class="w-3 h-3 transition-transform" :class="isRoleMenuOpen ? 'rotate-180' : ''" />
                        </button>

                        <!-- Dropdown -->
                        <Transition
                            enter-active-class="transition duration-150 ease-out"
                            enter-from-class="opacity-0 scale-95 -translate-y-1"
                            enter-to-class="opacity-100 scale-100 translate-y-0"
                            leave-active-class="transition duration-100 ease-in"
                            leave-from-class="opacity-100 scale-100 translate-y-0"
                            leave-to-class="opacity-0 scale-95 -translate-y-1"
                        >
                            <div
                                v-if="isRoleMenuOpen"
                                class="absolute right-0 top-full mt-2 w-48 bg-[#0d1117] border border-white/10 rounded-2xl shadow-2xl shadow-black/60 overflow-hidden z-[60]"
                            >
                                <div class="p-2 border-b border-white/5">
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] px-2 py-1">Switch Role</p>
                                </div>
                                <div class="p-2 space-y-1">
                                    <button
                                        v-for="roleName in userRoles"
                                        :key="roleName"
                                        @click="switchRole(roleName)"
                                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-left border"
                                        :class="roleName === activeRole
                                            ? (roleColors[roleName] ?? 'bg-blue-500/10 text-blue-400 border-blue-500/20')
                                            : 'text-slate-300 hover:bg-white/5 hover:text-white border-transparent'"
                                    >
                                        <ShieldCheck class="w-4 h-4 shrink-0 text-current" />
                                        <span class="text-xs font-bold capitalize text-current">{{ roleName }}</span>
                                        <span v-if="roleName === activeRole" class="ml-auto text-[8px] font-black uppercase tracking-widest opacity-70">Active</span>
                                    </button>
                                </div>
                            </div>
                        </Transition>
                    </div>

                    <!-- Single role badge (no switcher needed) -->
                    <div v-else class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl border text-xs font-black uppercase tracking-widest"
                         :class="roleColors[activeRole] ?? 'text-slate-400 bg-white/5 border-white/10'">
                        <ShieldCheck class="w-3.5 h-3.5" />
                        {{ activeRole }}
                    </div>

                    <div class="h-6 w-px bg-white/10 hidden sm:block"></div>

                    <button class="relative p-2 hover:bg-white/5 rounded-lg text-slate-400 transition-colors">
                        <Bell class="w-5 h-5" />
                        <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border-2 border-[#0a0c10]"></span>
                    </button>
                    <div class="h-6 w-px bg-white/10 hidden sm:block"></div>
                    <Link :href="route('logout')" method="post" as="button" class="flex items-center gap-2 p-2 hover:bg-red-500/10 text-slate-400 hover:text-red-500 rounded-lg transition-all text-sm font-medium">
                        <LogOut class="w-4 h-4" />
                        <span class="hidden md:block">Logout</span>
                    </Link>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                <slot />
            </main>
        </div>

        <!-- Floating Live Meeting Popup -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="translate-y-20 opacity-0 scale-95"
            enter-to-class="translate-y-0 opacity-100 scale-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="translate-y-0 opacity-100 scale-100"
            leave-to-class="translate-y-20 opacity-0 scale-95"
        >
            <div v-if="activeMeeting" 
                 class="fixed bottom-8 right-8 z-[100] w-full max-w-sm">
                <div class="bg-[#161b22]/90 backdrop-blur-2xl border border-blue-500/30 rounded-[2rem] p-5 shadow-2xl shadow-blue-500/10 flex items-center gap-4 relative overflow-hidden group">
                    <!-- Pulsing Background -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-transparent"></div>
                    
                    <div class="w-12 h-12 rounded-2xl bg-blue-600/20 flex items-center justify-center shrink-0 relative">
                        <Globe class="w-6 h-6 text-blue-500" />
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-[#161b22] animate-pulse"></span>
                    </div>

                    <div class="flex-grow min-w-0">
                        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-0.5">Live Now</p>
                        <h4 class="text-sm font-bold text-white truncate">{{ activeMeeting.title }}</h4>
                        <p class="text-[10px] text-slate-500 font-medium">Join this public session.</p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <button @click="dismissPopup = true" class="p-1 hover:bg-white/10 rounded-lg text-slate-500 transition-colors">
                            <X class="w-4 h-4" />
                        </button>
                        <Link :href="route('meeting.join', activeMeeting.room_id)" 
                              class="p-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
