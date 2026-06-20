<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle, Home, FileText } from 'lucide-vue-next';

const props = defineProps<{
  message: string;
}>();
</script>

<template>
  <Head title="Form Submitted" />

  <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
      <div class="mb-6">
        <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
          <CheckCircle class="w-10 h-10 text-green-400" />
        </div>
        <h1 class="text-2xl font-bold text-white mb-2">Thank You!</h1>
        <p class="text-slate-400">{{ message }}</p>
      </div>

      <div class="space-y-3">
        <Link
          href="/"
          class="block w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium transition"
        >
          Return to Home
        </Link>
        <Link
          v-if="$page.props.auth?.user"
          href="/forms"
          class="block w-full px-4 py-3 bg-slate-800 hover:bg-slate-700 rounded-lg text-white font-medium transition"
        >
          View All Forms
        </Link>
      </div>
    </div>
  </div>
</template>
