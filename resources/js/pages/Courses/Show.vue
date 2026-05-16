<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import Modal from '@/components/Modal.vue';
import { 
    BookOpen, 
    Plus, 
    Play, 
    CheckCircle2, 
    ChevronRight,
    ArrowLeft,
    Clock,
    Lock,
    Settings,
    User,
    Video,
    BarChart3,
    FileEdit
} from 'lucide-vue-next';

const props = defineProps<{
    course: any;
    isEnrolled: boolean;
    progress: any[];
    availableVideos?: any[];
}>();

const page = usePage();
const user = computed(() => page.props.auth.user as any);
const isInstructorOfCourse = computed(() => user.value.id === props.course.instructor_id || user.value.roles.includes('supervisor'));

const activeLesson = ref<any>(null);
const showSectionModal = ref(false);
const showLessonModal = ref(false);
const selectedSection = ref<any>(null);

// Section Form
const sectionForm = useForm({
    title: '',
    description: ''
});

const submitSection = () => {
    sectionForm.post(route('courses.sections.store', props.course.id), {
        onSuccess: () => {
            showSectionModal.value = false;
            sectionForm.reset();
        }
    });
};

// Lesson Form
const lessonForm = useForm({
    title: '',
    description: '',
    video_id: '',
    is_preview: false
});

const openLessonModal = (section: any) => {
    selectedSection.value = section;
    showLessonModal.value = true;
};

const submitLesson = () => {
    lessonForm.post(route('sections.lessons.store', selectedSection.value.id), {
        onSuccess: () => {
            showLessonModal.value = false;
            lessonForm.reset();
        }
    });
};

const enroll = () => {
    router.post(route('courses.enroll', props.course.id));
};

const isLessonCompleted = (lessonId: number) => {
    return props.progress.some(p => p.lesson_id === lessonId && p.status === 'completed');
};
</script>

<template>
    <AppLayout>
        <Head :title="course.title" />

        <div class="max-w-[1600px] mx-auto space-y-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content (Player & Curriculum) -->
                <div class="flex-1 space-y-6">
                    <Link :href="route('courses.index')" class="inline-flex items-center gap-2 text-slate-500 hover:text-white text-xs font-bold transition-colors mb-2">
                        <ArrowLeft class="w-4 h-4" />
                        Back to Library
                    </Link>

                    <!-- Player Placeholder / Intro -->
                    <div v-if="!activeLesson" class="aspect-video w-full bg-[#0d1117] rounded-3xl overflow-hidden border border-white/5 shadow-2xl relative flex flex-col items-center justify-center text-center p-8">
                        <div class="w-20 h-20 rounded-full bg-blue-600/10 flex items-center justify-center mb-6">
                            <BookOpen class="w-10 h-10 text-blue-500" />
                        </div>
                        <h2 class="text-2xl font-black text-white mb-2">{{ course.title }}</h2>
                        <p class="text-slate-500 max-w-md mx-auto mb-8">{{ course.short_description }}</p>
                        
                        <button v-if="!isEnrolled && !isInstructorOfCourse" @click="enroll" 
                                class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-sm shadow-xl shadow-blue-600/20 transition-all active:scale-95">
                            Enroll Now - {{ course.is_free ? 'FREE' : `$${course.price}` }}
                        </button>
                        <p v-else-if="isEnrolled" class="text-blue-500 font-bold uppercase tracking-widest text-xs">Select a lesson to start learning</p>
                    </div>

                    <!-- Curriculum Accordion -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-black text-white tracking-tight">Course Curriculum</h3>
                            <button v-if="isInstructorOfCourse" @click="showSectionModal = true" 
                                    class="flex items-center gap-2 px-3 py-1.5 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest border border-white/10 transition-all">
                                <Plus class="w-3.5 h-3.5" />
                                Add Section
                            </button>
                        </div>

                        <div v-for="section in course.sections" :key="section.id" class="bg-white/[0.02] border border-white/5 rounded-3xl overflow-hidden">
                            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between bg-white/[0.02]">
                                <div>
                                    <h4 class="text-sm font-bold text-white uppercase tracking-tight">{{ section.title }}</h4>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ section.lessons.length }} Lessons</p>
                                </div>
                                <button v-if="isInstructorOfCourse" @click="openLessonModal(section)" 
                                        class="p-2 hover:bg-blue-600/10 rounded-lg text-slate-500 hover:text-blue-500 transition-all">
                                    <Plus class="w-4 h-4" />
                                </button>
                            </div>
                            
                            <div class="divide-y divide-white/5">
                                <div v-for="lesson in section.lessons" :key="lesson.id" 
                                     class="px-6 py-4 flex items-center justify-between hover:bg-white/[0.01] transition-colors group">
                                    <div class="flex items-center gap-4">
                                        <div :class="isLessonCompleted(lesson.id) ? 'bg-green-500/10 text-green-500' : 'bg-white/5 text-slate-600'" 
                                             class="w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                                            <CheckCircle2 v-if="isLessonCompleted(lesson.id)" class="w-4 h-4" />
                                            <Play v-else class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-300 group-hover:text-white transition-colors">{{ lesson.title }}</p>
                                            <div class="flex items-center gap-3 mt-1">
                                                <span v-if="lesson.is_preview" class="text-[8px] font-black text-blue-500 uppercase tracking-widest bg-blue-500/10 px-1.5 py-0.5 rounded">Preview</span>
                                                <span class="text-[9px] text-slate-500 font-medium flex items-center gap-1">
                                                    <Clock class="w-3 h-3" />
                                                    12:45
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <Link v-if="isInstructorOfCourse" :href="route('lessons.content.edit', lesson.id)"
                                              class="p-2 bg-white/5 rounded-lg text-slate-500 hover:text-blue-500 hover:bg-blue-600/10 transition-all opacity-0 group-hover:opacity-100"
                                              title="Edit Lesson Content">
                                            <FileEdit class="w-4 h-4" />
                                        </Link>
                                        <button v-if="isEnrolled || isInstructorOfCourse || lesson.is_preview" 
                                                class="p-2 bg-white/5 rounded-lg text-slate-500 hover:text-white transition-all opacity-0 group-hover:opacity-100">
                                            <ChevronRight class="w-4 h-4" />
                                        </button>
                                        <Lock v-else class="w-4 h-4 text-slate-700" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Stats & Info) -->
                <div class="w-full lg:w-[400px] space-y-6">
                    <!-- Instructor Info -->
                    <Card title="Instructor">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-500 font-black text-lg">
                                {{ course.instructor?.username?.[0] }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">{{ course.instructor?.username }}</h4>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Master Instructor</p>
                            </div>
                        </div>
                    </Card>

                    <!-- Course Meta -->
                    <Card title="Course Information">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 font-bold uppercase tracking-tighter flex items-center gap-2">
                                    <Clock class="w-4 h-4" /> Duration
                                </span>
                                <span class="text-xs font-black text-white">12h 45m</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 font-bold uppercase tracking-tighter flex items-center gap-2">
                                    <Video class="w-4 h-4" /> Lessons
                                </span>
                                <span class="text-xs font-black text-white">{{ course.sections.reduce((acc, s) => acc + s.lessons.length, 0) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 font-bold uppercase tracking-tighter flex items-center gap-2">
                                    <Users class="w-4 h-4" /> Students
                                </span>
                                <span class="text-xs font-black text-white">{{ course.total_students }}</span>
                            </div>
                        </div>
                    </Card>

                    <!-- Instructor Management Actions -->
                    <div v-if="isInstructorOfCourse" class="space-y-4">
                        <h3 class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] px-2">Management</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="flex flex-col items-center justify-center gap-2 p-4 bg-white/[0.02] border border-white/5 rounded-2xl hover:bg-white/5 transition-all text-slate-400 hover:text-white group">
                                <Settings class="w-5 h-5 group-hover:rotate-45 transition-transform" />
                                <span class="text-[9px] font-black uppercase tracking-widest">Settings</span>
                            </button>
                            <button class="flex flex-col items-center justify-center gap-2 p-4 bg-white/[0.02] border border-white/5 rounded-2xl hover:bg-white/5 transition-all text-slate-400 hover:text-white group">
                                <BarChart3 class="w-5 h-5 group-hover:scale-110 transition-transform" />
                                <span class="text-[9px] font-black uppercase tracking-widest">Analytics</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Section Modal -->
        <Modal :show="showSectionModal" title="New Course Section" @close="showSectionModal = false">
            <form @submit.prevent="submitSection" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Section Title</label>
                    <input v-model="sectionForm.title" type="text" placeholder="e.g. Getting Started"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all" required>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showSectionModal = false" class="flex-1 px-4 py-2 bg-white/5 text-slate-300 font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" :disabled="sectionForm.processing" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-500 transition-all disabled:opacity-50">
                        Create Section
                    </button>
                </div>
            </form>
        </Modal>

        <!-- Add Lesson Modal -->
        <Modal :show="showLessonModal" :title="`New Lesson for ${selectedSection?.title}`" @close="showLessonModal = false">
            <form @submit.prevent="submitLesson" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Lesson Title</label>
                    <input v-model="lessonForm.title" type="text" placeholder="e.g. Setting up Environment"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Attach Video Content</label>
                    <select v-model="lessonForm.video_id" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <option value="">No Video (Text Only)</option>
                        <option v-for="video in availableVideos" :key="video.id" :value="video.id">{{ video.title }}</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-2xl border border-white/10">
                    <input v-model="lessonForm.is_preview" type="checkbox" id="isPreview" class="w-4 h-4 rounded bg-white/5 border-white/10 text-blue-600 focus:ring-blue-500/50">
                    <label for="isPreview" class="text-xs font-bold text-slate-300 cursor-pointer">Allow free preview for this lesson</label>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showLessonModal = false" class="flex-1 px-4 py-2 bg-white/5 text-slate-300 font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" :disabled="lessonForm.processing" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-500 transition-all disabled:opacity-50">
                        Add Lesson
                    </button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
