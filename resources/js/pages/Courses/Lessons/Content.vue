<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { ArrowLeft, Save, UploadCloud, FileText, Loader2 } from 'lucide-vue-next';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps<{
    lesson: any;
    course: any;
}>();

const form = useForm({
    content: props.lesson.content || ''
});

const isExtracting = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);
const quillEditor = ref<any>(null);

const saveContent = () => {
    form.put(route('lessons.content.update', props.lesson.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Show success toast
        }
    });
};

const handleFileUpload = async (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (!target.files || target.files.length === 0) return;

    const file = target.files[0];
    const formData = new FormData();
    formData.append('document', file);

    isExtracting.value = true;

    try {
        const xsrfToken = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='))?.split('=')[1];
        
        const response = await fetch(route('lessons.content.extract'), {
            method: 'POST',
            body: formData,
            headers: {
                'X-XSRF-TOKEN': decodeURIComponent(xsrfToken || ''),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok && data.text) {
            // Append the extracted text to the current content
            // Need to convert plain text with newlines to HTML for Quill
            const htmlText = data.text.split('\n').map((line: string) => `<p>${line}</p>`).join('');
            
            if (form.content) {
                form.content += '<br/>' + htmlText;
            } else {
                form.content = htmlText;
            }
        } else {
            alert(data.error || 'Failed to extract text.');
        }
    } catch (error) {
        console.error('Extraction error:', error);
        alert('An error occurred while extracting text.');
    } finally {
        isExtracting.value = false;
        if (fileInput.value) {
            fileInput.value.value = '';
        }
    }
};

const triggerFileUpload = () => {
    fileInput.value?.click();
};
</script>

<template>
    <AppLayout>
        <Head :title="`Edit Content: ${lesson.title}`" />

        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <Link :href="route('courses.show', course.id)" class="inline-flex items-center gap-2 text-slate-500 hover:text-white text-xs font-bold transition-colors mb-2">
                        <ArrowLeft class="w-4 h-4" />
                        Back to Course
                    </Link>
                    <h1 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
                        <FileText class="w-6 h-6 text-blue-500" />
                        Edit Lesson Content
                    </h1>
                    <p class="text-sm text-slate-400">
                        <span class="font-bold text-white">{{ course.title }}</span> &raquo; {{ lesson.title }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="triggerFileUpload" :disabled="isExtracting"
                            class="flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold text-sm transition-all border border-slate-700 disabled:opacity-50">
                        <Loader2 v-if="isExtracting" class="w-4 h-4 animate-spin" />
                        <UploadCloud v-else class="w-4 h-4 text-blue-400" />
                        {{ isExtracting ? 'Extracting...' : 'Extract from PDF/DOCX' }}
                    </button>
                    <input type="file" ref="fileInput" @change="handleFileUpload" accept=".pdf,.docx,.txt" class="hidden" />
                    
                    <button @click="saveContent" :disabled="form.processing"
                            class="flex items-center gap-2 px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-black text-sm shadow-lg shadow-blue-600/20 transition-all disabled:opacity-50">
                        <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                        <Save v-else class="w-4 h-4" />
                        Save Content
                    </button>
                </div>
            </div>

            <!-- Editor Container -->
            <div class="bg-white/[0.02] border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
                <div class="bg-white/5 border-b border-white/10 px-6 py-3 flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Rich Text Editor</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Auto-ready</span>
                    </div>
                </div>
                
                <div class="p-0 bg-white min-h-[500px] text-slate-900 editor-wrapper">
                    <QuillEditor 
                        v-model:content="form.content" 
                        contentType="html" 
                        theme="snow" 
                        toolbar="full"
                        class="min-h-[500px]"
                        ref="quillEditor"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
/* Adjust Quill editor inside dark mode app to be readable */
.editor-wrapper .ql-toolbar {
    border: none;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    padding: 12px 24px;
}
.editor-wrapper .ql-container {
    border: none;
    font-family: inherit;
    font-size: 16px;
}
.editor-wrapper .ql-editor {
    min-height: 500px;
    padding: 24px;
}
</style>
