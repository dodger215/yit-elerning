<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import {
  FileText,
  Plus,
  Eye,
  BarChart3,
  Calendar,
  Users,
  GraduationCap,
  Share2,
  Check
} from 'lucide-vue-next';

const props = defineProps<{
  forms: {
    data: Array<{
      id: string;
      title: string;
      description: string;
      is_active: boolean;
      form_type: string;
      cohort: string | null;
      created_at: string;
      user?: {
        name: string;
      };
    }>;
    links: any[];
  };
}>();

const copiedId = ref<string | null>(null);

const copyFormLink = async (id: string) => {
  const url = `${window.location.origin}/forms/${id}`;
  try {
    await navigator.clipboard.writeText(url);
    copiedId.value = id;
    setTimeout(() => { copiedId.value = null; }, 2000);
  } catch (err) {
    console.error('Failed to copy', err);
  }
};
</script>

<template>
  <AppLayout>
    <Head title="My Forms" />

    <div class="max-w-7xl mx-auto py-8 px-4">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-white">My Forms</h1>
          <p class="text-slate-400 mt-1">Manage and create forms for data collection</p>
        </div>
        <Link
          v-if="$page.props.auth.user.roles.includes('instructor') || $page.props.auth.user.roles.includes('supervisor')"
          href="/forms/create"
          class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium transition"
        >
          <Plus class="w-4 h-4" />
          Create New Form
        </Link>
      </div>

      <!-- Forms Grid -->
      <div v-if="forms.data.length === 0" class="text-center py-12">
        <FileText class="w-16 h-16 text-slate-600 mx-auto mb-4" />
        <h3 class="text-xl font-medium text-white mb-2">No forms yet</h3>
        <p class="text-slate-400 mb-4">Create your first form to start collecting data</p>
        <Link
          v-if="$page.props.auth.user.roles.includes('instructor') || $page.props.auth.user.roles.includes('supervisor')"
          href="/forms/create"
          class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white"
        >
          <Plus class="w-4 h-4" />
          Create Form
        </Link>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="form in forms.data"
          :key="form.id"
          class="bg-slate-900/50 border border-slate-800 rounded-2xl hover:border-blue-500/30 transition-all duration-300 overflow-hidden group shadow-lg"
        >
          <div class="p-6">
            <div class="flex items-start justify-between mb-4">
              <div class="p-3 bg-blue-500/10 rounded-xl group-hover:bg-blue-500/20 transition-colors">
                <FileText v-if="form.form_type === 'general'" class="w-6 h-6 text-blue-400" />
                <GraduationCap v-else class="w-6 h-6 text-indigo-400" />
              </div>
              <div class="flex flex-col items-end gap-2">
                <span
                  :class="form.is_active ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20'"
                  class="px-2 py-1 rounded-md border text-[10px] font-bold uppercase tracking-wider"
                >
                  {{ form.is_active ? 'Active' : 'Inactive' }}
                </span>
                <span v-if="form.form_type === 'course_assignment'" class="text-[9px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/20">
                  Assignment
                </span>
              </div>
            </div>

            <h3 class="text-lg font-bold text-white mb-2 line-clamp-1 group-hover:text-blue-400 transition-colors">
              {{ form.title }}
            </h3>

            <p class="text-slate-400 text-sm mb-4 line-clamp-2 h-10">
              {{ form.description || 'No description provided' }}
            </p>

            <div v-if="form.cohort" class="mb-4 flex items-center gap-2 text-xs text-slate-400">
               <Users class="w-3.5 h-3.5" />
               <span>Cohort: <span class="text-white font-medium">{{ form.cohort }}</span></span>
            </div>

            <div class="flex items-center gap-4 text-xs text-slate-500 mb-6 pb-4 border-b border-white/5">
              <div class="flex items-center gap-1">
                <Calendar class="w-3.5 h-3.5" />
                <span>{{ new Date(form.created_at).toLocaleDateString() }}</span>
              </div>
            </div>

            <div class="flex gap-2">
              <Link
                :href="`/forms/${form.id}`"
                class="flex-1 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 rounded-xl text-white text-sm font-bold text-center transition active:scale-[0.98]"
              >
                {{ $page.props.auth.user.roles.includes('regular') ? 'Start Assignment' : 'Preview' }}
              </Link>
              <Link
                v-if="!$page.props.auth.user.roles.includes('regular')"
                :href="`/forms/${form.id}/submissions`"
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-xl text-white transition active:scale-[0.98] shadow-lg shadow-blue-600/20"
                title="View Submissions"
              >
                <BarChart3 class="w-5 h-5" />
              </Link>
              <button
                @click="copyFormLink(form.id)"
                class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 rounded-xl text-white transition active:scale-[0.98]"
                title="Copy Form Link"
              >
                <Check v-if="copiedId === form.id" class="w-5 h-5 text-green-400" />
                <Share2 v-else class="w-5 h-5 text-slate-300" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
