<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import StatsCard from '@/components/StatsCard.vue';
import { 
    Play, 
    ArrowLeft, 
    Clock, 
    MessageSquare, 
    Share2, 
    ChevronRight,
    User,
    Calendar,
    Eye
} from 'lucide-vue-next';

const props = defineProps<{
    video: any;
}>();
</script>

<template>
    <AppLayout>
        <Head :title="video.title" />

        <div class="max-w-6xl mx-auto space-y-8">
            <Link :href="route('videos.index')" class="inline-flex items-center gap-2 text-slate-500 hover:text-white text-xs font-bold transition-colors mb-4">
                <ArrowLeft class="w-4 h-4" />
                Back to Library
            </Link>

            <!-- Video Player Section -->
            <div class="aspect-video w-full bg-black rounded-3xl overflow-hidden border border-white/5 shadow-2xl relative group">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-20 h-20 rounded-full bg-blue-600 flex items-center justify-center shadow-2xl shadow-blue-600/40 cursor-pointer hover:scale-110 transition-transform">
                        <Play class="w-10 h-10 text-white fill-current" />
                    </div>
                </div>
                <!-- Placeholder image/overlay -->
                <img v-if="video.thumbnail_url" :src="video.thumbnail_url" class="w-full h-full object-cover opacity-60">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Video Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h1 class="text-3xl font-black text-white tracking-tight">{{ video.title }}</h1>
                        <div class="flex items-center gap-4 mt-3">
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-bold uppercase tracking-widest">
                                <Clock class="w-4 h-4" />
                                {{ Math.floor(video.duration_seconds / 60) }} Minutes
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-bold uppercase tracking-widest">
                                <Calendar class="w-4 h-4" />
                                Published {{ new Date(video.created_at).toLocaleDateString() }}
                            </div>
                            <div class="flex items-center gap-2 text-xs text-blue-500 font-black uppercase tracking-widest">
                                <Eye class="w-4 h-4" />
                                1.2k Views
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5">
                        <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3">Description</h3>
                        <p class="text-slate-300 leading-relaxed text-sm">
                            {{ video.description || 'No description provided for this video.' }}
                        </p>
                    </div>

                    <!-- Interactions Placeholder -->
                    <div class="flex items-center gap-3">
                        <button class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 text-slate-200 border border-white/10 rounded-xl font-bold text-xs transition-all">
                            <MessageSquare class="w-4 h-4" />
                            24 Comments
                        </button>
                        <button class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 text-slate-200 border border-white/10 rounded-xl font-bold text-xs transition-all">
                            <Share2 class="w-4 h-4" />
                            Share Video
                        </button>
                    </div>
                </div>

                <!-- Sidebar Metadata -->
                <div class="space-y-6">
                    <Card title="Engagement Stats">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 font-bold">Watch Time</span>
                                <span class="text-xs font-black text-white">45.2 Hours</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 font-bold">Completion Rate</span>
                                <span class="text-xs font-black text-green-400">82%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500 font-bold">File Size</span>
                                <span class="text-xs font-black text-white">{{ (video.size_bytes / 1024 / 1024).toFixed(1) }} MB</span>
                            </div>
                        </div>
                    </Card>

                    <Card title="Uploader Info">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center text-blue-500 font-black">
                                {{ video.uploader?.username?.[0] || 'S' }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">{{ video.uploader?.username || 'System' }}</h4>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Content Editor</p>
                            </div>
                        </div>
                        <template #footer>
                            <button class="w-full flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-500 hover:text-blue-400 transition-colors py-1">
                                View Profile
                                <ChevronRight class="w-3 h-3" />
                            </button>
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
