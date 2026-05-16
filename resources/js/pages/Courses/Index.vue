<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import Modal from '@/components/Modal.vue';
import { 
    BookOpen, 
    Plus, 
    Users, 
    Play, 
    Star, 
    Clock, 
    ArrowRight,
    Search,
    Filter,
    Compass,
    GraduationCap,
    CheckCircle2
} from 'lucide-vue-next';

const props = defineProps<{
    courses: any;
}>();

const page = usePage();
const user = computed(() => page.props.auth.user as any);
const isInstructor = computed(() => user.value.roles.includes('instructor'));

const activeTab = ref(isInstructor.value ? 'my-courses' : 'explore');

// Course Creation Form
const showCreateModal = ref(false);
const createForm = useForm({
    title: '',
    description: '',
    short_description: '',
    level: 'beginner',
    is_free: true,
    price: 0
});

const submitCreate = () => {
    createForm.post(route('courses.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
        }
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Course Catalog" />

        <div class="space-y-8 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Learning Center</h1>
                    <p class="text-slate-500 font-medium mt-1">Explore, learn, and master new skills with YIT.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <button v-if="isInstructor" @click="showCreateModal = true" 
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                        <Plus class="w-4 h-4" />
                        Create New Course
                    </button>
                    <div class="relative hidden sm:block">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                        <input type="text" placeholder="Search courses..." 
                               class="bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-xs w-64 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex items-center gap-1 p-1 bg-white/[0.02] border border-white/5 rounded-2xl w-fit">
                <button @click="activeTab = 'explore'" 
                        :class="activeTab === 'explore' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-500 hover:text-white'"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                    <Compass class="w-4 h-4" />
                    Explore Library
                </button>
                <button v-if="isInstructor" @click="activeTab = 'my-courses'" 
                        :class="activeTab === 'my-courses' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-500 hover:text-white'"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                    <GraduationCap class="w-4 h-4" />
                    My Curriculum
                </button>
                <button v-if="!isInstructor" @click="activeTab = 'my-learning'" 
                        :class="activeTab === 'my-learning' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-500 hover:text-white'"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                    <CheckCircle2 class="w-4 h-4" />
                    Enrolled Courses
                </button>
            </div>

            <!-- Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div v-for="course in courses.data" :key="course.id" 
                     class="group bg-[#0d1117] border border-white/5 rounded-3xl overflow-hidden shadow-xl shadow-black/20 hover:border-blue-500/30 transition-all">
                    <!-- Thumbnail -->
                    <div class="aspect-video bg-slate-800 relative overflow-hidden">
                        <img v-if="course.thumbnail_url" :src="course.thumbnail_url" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-600/20 to-purple-600/20">
                            <BookOpen class="w-12 h-12 text-blue-500/30" />
                        </div>
                        <div class="absolute top-3 right-3 px-2 py-1 bg-black/60 backdrop-blur-md rounded-lg text-[9px] font-black text-white uppercase tracking-widest border border-white/10">
                            {{ course.level }}
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5 space-y-4">
                        <div class="space-y-1">
                            <h3 class="text-sm font-bold text-white line-clamp-2 leading-snug group-hover:text-blue-400 transition-colors">
                                {{ course.title }}
                            </h3>
                            <p class="text-[11px] text-slate-500 font-medium line-clamp-1">By {{ course.instructor?.username }}</p>
                        </div>

                        <div class="flex items-center justify-between text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <div class="flex items-center gap-1.5">
                                <Users class="w-3.5 h-3.5" />
                                {{ course.total_students }} Students
                            </div>
                            <div class="flex items-center gap-1.5">
                                <Star class="w-3.5 h-3.5 text-orange-400 fill-current" />
                                4.9
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                            <span class="text-lg font-black text-white tracking-tighter">
                                {{ course.is_free ? 'FREE' : `$${course.price}` }}
                            </span>
                            <Link :href="route('courses.show', course.id)" 
                                  class="flex items-center gap-2 px-3 py-1.5 bg-white/5 hover:bg-blue-600 text-slate-300 hover:text-white rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                View Course
                                <ArrowRight class="w-3 h-3" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-center gap-2 pt-8">
                <Link v-if="courses.prev_page_url" :href="courses.prev_page_url" class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs text-slate-400 hover:text-white transition-all">Previous</Link>
                <Link v-if="courses.next_page_url" :href="courses.next_page_url" class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs text-slate-400 hover:text-white transition-all">Next</Link>
            </div>
        </div>

        <!-- Create Course Modal -->
        <Modal :show="showCreateModal" title="Launch New Course" @close="showCreateModal = false">
            <form @submit.prevent="submitCreate" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Course Title</label>
                    <input v-model="createForm.title" type="text" placeholder="Advanced Full-Stack Development"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Short Description</label>
                    <input v-model="createForm.short_description" type="text" placeholder="Master the art of building scalable apps."
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Difficulty Level</label>
                        <select v-model="createForm.level" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Pricing</label>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="createForm.is_free = !createForm.is_free" 
                                    :class="createForm.is_free ? 'bg-blue-600 text-white' : 'bg-white/5 text-slate-500'"
                                    class="px-3 py-2 rounded-xl text-[10px] font-black uppercase transition-all">FREE</button>
                            <input v-if="!createForm.is_free" v-model="createForm.price" type="number" step="0.01" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Full Description</label>
                    <textarea v-model="createForm.description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all" required></textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showCreateModal = false" class="flex-1 px-4 py-2 bg-white/5 text-slate-300 font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" :disabled="createForm.processing" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-500 transition-all disabled:opacity-50">
                        {{ createForm.processing ? 'Creating...' : 'Launch Course' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
