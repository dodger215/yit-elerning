<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import DataTable from '@/components/DataTable.vue';
import Modal from '@/components/Modal.vue';
import { 
    Film, 
    Upload, 
    Search, 
    Filter, 
    Play, 
    MoreVertical, 
    Trash2, 
    Edit, 
    Clock,
    CheckCircle2,
    XCircle
} from 'lucide-vue-next';

const props = defineProps<{
    videos: any;
}>();

// Upload Form
const showUploadModal = ref(false);
const uploadForm = useForm({
    title: '',
    description: '',
    video_file: null as File | null,
    video_type: 'long',
    duration: 0,
    category_id: null
});

const handleVideoFile = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (!target.files || target.files.length === 0) return;
    
    const file = target.files[0];
    uploadForm.video_file = file;

    // Auto-detect if short or long video based on duration
    const video = document.createElement('video');
    video.preload = 'metadata';
    video.onloadedmetadata = () => {
        window.URL.revokeObjectURL(video.src);
        if (video.duration < 60) {
            uploadForm.video_type = 'short';
        } else {
            uploadForm.video_type = 'long';
        }
        uploadForm.duration = Math.floor(video.duration);
    };
    video.src = URL.createObjectURL(file);
};

const submitUpload = () => {
    uploadForm.post(route('videos.store'), {
        onSuccess: () => {
            showUploadModal.value = false;
            uploadForm.reset();
        }
    });
};

// Edit Form
const showEditModal = ref(false);
const selectedVideo = ref<any>(null);
const editForm = useForm({
    title: '',
    description: '',
    status: 'active'
});

const openEditModal = (video: any) => {
    selectedVideo.value = video;
    editForm.title = video.title;
    editForm.description = video.description;
    editForm.status = video.status;
    showEditModal.value = true;
};

const submitUpdate = () => {
    editForm.put(route('videos.update', selectedVideo.value.id), {
        onSuccess: () => showEditModal.value = false
    });
};

const deleteVideo = (video: any) => {
    if (confirm('Are you sure you want to delete this video? This will permanently remove the file.')) {
        router.delete(route('videos.destroy', video.id));
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Video Library" />

        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Video Content Library</h1>
                    <p class="text-xs text-slate-500 font-medium">Manage, upload, and monitor all platform video assets.</p>
                </div>
                
                <button @click="showUploadModal = true" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                    <Upload class="w-4 h-4" />
                    Upload Video
                </button>
            </div>

            <Card padding="false">
                <!-- Filters -->
                <div class="p-4 border-b border-white/5 flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div class="relative w-full sm:w-64">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                        <input type="text" placeholder="Search videos..." 
                               class="bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-xs w-full focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                    </div>
                    
                    <button class="p-2 bg-white/5 border border-white/10 rounded-xl text-slate-400 hover:text-white transition-colors">
                        <Filter class="w-4 h-4" />
                    </button>
                </div>

                <DataTable :headers="['Content', 'Uploader', 'Stats', 'Status', 'Actions']" :items="videos.data">
                    <template #row="{ item }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-14 rounded-lg bg-slate-800 border border-white/10 flex items-center justify-center text-slate-600 overflow-hidden relative group cursor-pointer">
                                    <img v-if="item.thumbnail_url" :src="item.thumbnail_url" class="w-full h-full object-cover">
                                    <Play v-else class="w-5 h-5 group-hover:scale-110 transition-transform" />
                                    <div class="absolute bottom-1 right-1 px-1 bg-black/60 rounded text-[8px] font-bold text-white">
                                        {{ item.duration ? Math.floor(item.duration / 60) + ':' + (item.duration % 60).toString().padStart(2, '0') : '0:00' }}
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-white truncate w-48">{{ item.title }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">Added on {{ new Date(item.created_at).toLocaleDateString() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-slate-400">{{ item.uploader?.username || 'System' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">{{ item.file_size ? (item.file_size / 1024 / 1024).toFixed(1) : '0.0' }} MB</span>
                                <span class="text-[10px] text-blue-500 font-black uppercase">{{ item.video_type }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span :class="{
                                'bg-green-500/10 text-green-400 border-green-500/20': item.status === 'active',
                                'bg-blue-500/10 text-blue-400 border-blue-500/20': item.status === 'processing',
                                'bg-red-500/10 text-red-400 border-red-500/20': item.status === 'inactive'
                            }" class="px-2 py-0.5 rounded-md border text-[9px] font-black uppercase tracking-widest">
                                {{ item.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEditModal(item)" class="p-1.5 hover:bg-white/5 rounded-lg text-slate-500 hover:text-white transition-all">
                                    <Edit class="w-4 h-4" />
                                </button>
                                <button @click="deleteVideo(item)" class="p-1.5 hover:bg-red-500/10 rounded-lg text-slate-500 hover:text-red-500 transition-all">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </template>
                </DataTable>

                <div class="p-4 border-t border-white/5 flex items-center justify-between">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                        Total {{ videos.total }} Video Assets
                    </p>
                    <div class="flex gap-2">
                        <Link v-if="videos.prev_page_url" :href="videos.prev_page_url" class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-slate-400 hover:text-white transition-all">Prev</Link>
                        <Link v-if="videos.next_page_url" :href="videos.next_page_url" class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-slate-400 hover:text-white transition-all">Next</Link>
                    </div>
                </div>
            </Card>
        </div>

        <!-- Upload Video Modal -->
        <Modal :show="showUploadModal" title="Upload Video Content" @close="showUploadModal = false">
            <form @submit.prevent="submitUpload" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Video Title</label>
                    <input v-model="uploadForm.title" type="text" placeholder="Introduction to React Hooks"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">File Upload</label>
                    <div class="border-2 border-dashed border-white/10 rounded-2xl p-8 text-center hover:border-blue-500/30 transition-all cursor-pointer relative overflow-hidden group">
                        <input type="file" @change="handleVideoFile" accept="video/mp4,video/quicktime,video/x-msvideo"
                               class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        <div class="relative z-0">
                            <Upload class="w-8 h-8 mx-auto mb-2 text-slate-500 group-hover:text-blue-500 transition-colors" />
                            <p class="text-sm font-bold text-slate-300">
                                {{ uploadForm.video_file ? uploadForm.video_file.name : 'Select video file to upload' }}
                            </p>
                            <p class="text-[10px] text-slate-500 mt-1 font-medium">MP4, MOV or AVI (Max 500MB)</p>
                        </div>
                    </div>
                    <div v-if="uploadForm.progress" class="mt-4">
                        <div class="flex justify-between text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">
                            <span>Uploading...</span>
                            <span>{{ uploadForm.progress.percentage }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 transition-all" :style="{ width: `${uploadForm.progress.percentage}%` }"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Video Type</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="uploadForm.video_type = 'long'"
                                :class="uploadForm.video_type === 'long' ? 'bg-blue-600 text-white border-blue-500' : 'bg-white/5 text-slate-400 border-white/10'"
                                class="px-3 py-2 rounded-xl border text-[10px] font-black uppercase tracking-widest transition-all">
                            Long Video (Standard)
                        </button>
                        <button type="button" @click="uploadForm.video_type = 'short'"
                                :class="uploadForm.video_type === 'short' ? 'bg-blue-600 text-white border-blue-500' : 'bg-white/5 text-slate-400 border-white/10'"
                                class="px-3 py-2 rounded-xl border text-[10px] font-black uppercase tracking-widest transition-all">
                            Short Video (&lt; 60s)
                        </button>
                    </div>
                    <p class="text-[9px] text-slate-500 mt-1">This will be auto-detected when you select a video, but you can override it.</p>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                    <textarea v-model="uploadForm.description" rows="3"
                              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all"></textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showUploadModal = false" class="flex-1 px-4 py-2 bg-white/5 text-slate-300 font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" :disabled="uploadForm.processing || !uploadForm.video_file" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-500 transition-all disabled:opacity-50">
                        {{ uploadForm.processing ? 'Uploading...' : 'Start Upload' }}
                    </button>
                </div>
            </form>
        </Modal>

        <!-- Edit Video Modal -->
        <Modal :show="showEditModal" :title="`Edit Video: ${selectedVideo?.title}`" @close="showEditModal = false">
            <form @submit.prevent="submitUpdate" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Title</label>
                    <input v-model="editForm.title" type="text"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Status</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button v-for="status in ['processing', 'active', 'inactive']" :key="status"
                                type="button"
                                @click="editForm.status = status"
                                :class="editForm.status === status ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-600/20' : 'bg-white/5 text-slate-400 border-white/10 hover:bg-white/10'"
                                class="px-3 py-2 rounded-xl border text-[10px] font-black uppercase tracking-widest transition-all">
                            {{ status }}
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                    <textarea v-model="editForm.description" rows="3"
                              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all"></textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showEditModal = false" class="flex-1 px-4 py-2 bg-white/5 text-slate-300 font-bold rounded-xl text-sm hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" :disabled="editForm.processing" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-500 transition-all disabled:opacity-50">
                        {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
