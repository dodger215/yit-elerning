<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
  BarChart3,
  ChevronLeft,
  User,
  Calendar,
  CheckCircle2,
  XCircle,
  Clock,
  MessageSquare,
  GraduationCap
} from 'lucide-vue-next';

interface Submission {
  id: string;
  form_id: string;
  user_id: string | null;
  data: string; // JSON string
  email_to_notify: string | null;
  grade: string | null;
  feedback: string | null;
  created_at: string;
  user?: {
    first_name: string;
    last_name: string;
    email: string;
  } | null;
}

const props = defineProps<{
  form: {
    id: string;
    title: string;
    description: string;
    fields: string; // JSON string
  };
  submissions: {
    data: Submission[];
    links: any[];
  };
}>();

const selectedSubmission = ref<Submission | null>(null);
const parsedFields = JSON.parse(props.form.fields);

const gradingForm = useForm({
  grade: '',
  feedback: '',
});

const openGradingModal = (submission: Submission) => {
  selectedSubmission.value = submission;
  gradingForm.grade = submission.grade || '';
  gradingForm.feedback = submission.feedback || '';
};

const closeGradingModal = () => {
  selectedSubmission.value = null;
  gradingForm.reset();
};

const submitGrade = () => {
  if (!selectedSubmission.value) return;

  gradingForm.post(`/forms/submissions/${selectedSubmission.value.id}/grade`, {
    preserveScroll: true,
    onSuccess: () => {
      closeGradingModal();
    },
  });
};

const getParsedData = (dataStr: string) => {
  try {
    return JSON.parse(dataStr);
  } catch (e) {
    return {};
  }
};

const getFieldLabel = (fieldName: string) => {
  const field = parsedFields.find((f: any) => f.name === fieldName);
  return field ? field.label : fieldName;
};
</script>

<template>
  <AppLayout>
    <Head :title="`Submissions - ${form.title}`" />

    <div class="max-w-7xl mx-auto py-8 px-4">
      <!-- Header -->
      <div class="mb-8">
        <Link
          href="/forms"
          class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition mb-4"
        >
          <ChevronLeft class="w-4 h-4" />
          Back to Forms
        </Link>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 class="text-3xl font-bold text-white">{{ form.title }}</h1>
            <p class="text-slate-400 mt-1">Viewing all user submissions and data</p>
          </div>
          <div class="flex items-center gap-3">
             <div class="px-4 py-2 bg-slate-800 rounded-lg border border-slate-700">
                <span class="text-slate-400 text-sm">Total Submissions:</span>
                <span class="text-white font-bold ml-2">{{ submissions.data.length }}</span>
             </div>
          </div>
        </div>
      </div>

      <!-- Submissions Table -->
      <div class="bg-slate-900/50 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-800/50 border-b border-slate-800">
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">User</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Submitted At</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Grade</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
              <tr
                v-for="submission in submissions.data"
                :key="submission.id"
                class="hover:bg-white/[0.02] transition"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600/10 flex items-center justify-center text-blue-400">
                      <User class="w-5 h-5" />
                    </div>
                    <div>
                      <div class="text-white font-medium">
                        {{ submission.user ? `${submission.user.first_name} ${submission.user.last_name}` : 'Anonymous' }}
                      </div>
                      <div class="text-slate-500 text-xs">
                        {{ submission.user ? submission.user.email : (submission.email_to_notify || 'No email') }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="text-slate-300 text-sm">{{ new Date(submission.created_at).toLocaleDateString() }}</span>
                    <span class="text-slate-500 text-xs">{{ new Date(submission.created_at).toLocaleTimeString() }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span
                    v-if="submission.grade"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-500/10 text-green-400 text-xs font-medium border border-green-500/20"
                  >
                    <CheckCircle2 class="w-3 h-3" />
                    Graded
                  </span>
                  <span
                    v-else
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 text-xs font-medium border border-amber-500/20"
                  >
                    <Clock class="w-3 h-3" />
                    Pending
                  </span>
                </td>
                <td class="px-6 py-4 text-white font-bold">
                  {{ submission.grade || '-' }}
                </td>
                <td class="px-6 py-4 text-right">
                  <button
                    @click="openGradingModal(submission)"
                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition"
                  >
                    View & Grade
                  </button>
                </td>
              </tr>
              <tr v-if="submissions.data.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">
                  No submissions yet.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Grading Modal -->
    <div
      v-if="selectedSubmission"
      class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
      @click.self="closeGradingModal"
    >
      <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-800 flex items-center justify-between bg-slate-800/50">
          <div class="flex items-center gap-3">
             <div class="p-2 bg-blue-600 rounded-xl shadow-lg shadow-blue-600/20">
                <GraduationCap class="w-6 h-6 text-white" />
             </div>
             <div>
                <h3 class="text-xl font-bold text-white">Review Submission</h3>
                <p class="text-xs text-slate-400">Submitted by {{ selectedSubmission.user ? `${selectedSubmission.user.first_name} ${selectedSubmission.user.last_name}` : 'Anonymous' }}</p>
             </div>
          </div>
          <button @click="closeGradingModal" class="p-2 text-slate-400 hover:text-white transition rounded-lg hover:bg-white/5">
            <XCircle class="w-6 h-6" />
          </button>
        </div>

        <!-- Modal Content -->
        <div class="flex-1 overflow-y-auto p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Submission Data -->
          <div>
            <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
              <BarChart3 class="w-4 h-4" />
              Response Data
            </h4>
            <div class="space-y-4">
              <div
                v-for="(value, key) in getParsedData(selectedSubmission.data)"
                :key="key"
                class="bg-slate-800/50 rounded-xl p-4 border border-slate-700/50"
              >
                <label class="block text-xs font-black text-blue-400 uppercase tracking-tighter mb-1">
                  {{ getFieldLabel(key as string) }}
                </label>
                <div class="text-white whitespace-pre-wrap">
                  {{ Array.isArray(value) ? value.join(', ') : value }}
                </div>
              </div>
            </div>
          </div>

          <!-- Grading Section -->
          <div class="bg-slate-800/30 p-6 rounded-2xl border border-blue-500/10">
            <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
              <CheckCircle2 class="w-4 h-4" />
              Marking & Feedback
            </h4>

            <form @submit.prevent="submitGrade" class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Grade / Score</label>
                <input
                  v-model="gradingForm.grade"
                  type="text"
                  placeholder="e.g., 85/100, A+, Passed"
                  class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Instructor Feedback</label>
                <textarea
                  v-model="gradingForm.feedback"
                  rows="6"
                  placeholder="Provide constructive feedback for the student..."
                  class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition resize-none"
                ></textarea>
              </div>

              <div class="pt-4 border-t border-slate-800">
                <button
                  type="submit"
                  :disabled="gradingForm.processing"
                  class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98] disabled:opacity-50"
                >
                  {{ gradingForm.processing ? 'Saving Grade...' : 'Save Grade & Feedback' }}
                </button>
              </div>
            </form>

            <div v-if="selectedSubmission.graded_by" class="mt-6 flex items-center gap-2 text-xs text-slate-500">
               <User class="w-3 h-3" />
               <span>Last graded by an instructor</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
