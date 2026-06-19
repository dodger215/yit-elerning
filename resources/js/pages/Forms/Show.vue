<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/Card.vue';
import {
  Send,
  AlertCircle,
  CheckCircle,
  Upload,
  Calendar,
  Mail,
  Lock,
  Phone,
  User,
  FileText,
  MessageSquare,
  ChevronLeft,
  GraduationCap
} from 'lucide-vue-next';

const props = defineProps<{
  form: {
    id: string;
    title: string;
    description: string;
    fields: string;
    is_active: boolean;
    form_type: string;
    cohort: string | null;
  };
}>();

const fields = computed(() => {
  try {
    return JSON.parse(props.form.fields);
  } catch {
    return [];
  }
});

const formData = ref<Record<string, any>>({});
const fileInputs = ref<Record<string, File>>({});

// Initialize form data
const initializeFormData = () => {
  fields.value.forEach((field: any) => {
    if (field.type === 'checkbox' && field.options && field.options.length > 0) {
      formData.value[field.name] = [];
    } else if (field.type === 'checkbox') {
      formData.value[field.name] = false;
    } else if (field.type === 'file' || field.type === 'image') {
      formData.value[field.name] = null;
    } else {
      formData.value[field.name] = '';
    }
  });
};

initializeFormData();

const submitForm = useForm({
  email_to_notify: '',
  data: {} as string
});

const handleFileChange = (fieldName: string, event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    fileInputs.value[fieldName] = target.files[0];
    formData.value[fieldName] = target.files[0].name;
  }
};

const handleSubmit = () => {
  // Prepare data for submission
  const submissionData = JSON.stringify(formData.value);

  submitForm.data = submissionData;

  // For file uploads, we need FormData
  const hasFiles = Object.keys(fileInputs.value).length > 0;

  if (hasFiles) {
    const formDataObj = new FormData();
    formDataObj.append('email_to_notify', submitForm.email_to_notify);
    formDataObj.append('data', submissionData);

    // Append files
    Object.entries(fileInputs.value).forEach(([key, file]) => {
      if (file) {
        formDataObj.append(key, file);
      }
    });

    submitForm.post(`/forms/${props.form.id}/submit`, {
      forceFormData: true,
      onSuccess: () => {
        // Reset form
        initializeFormData();
        fileInputs.value = {};
      }
    });
  } else {
    submitForm.post(`/forms/${props.form.id}/submit`, {
      onSuccess: () => {
        initializeFormData();
      }
    });
  }
};

const getFieldIcon = (type: string) => {
  const icons: Record<string, any> = {
    email: Mail,
    password: Lock,
    tel: Phone,
    number: User,
    date: Calendar,
    file: Upload,
    image: Upload,
    text: FileText,
    textarea: MessageSquare
  };
  return icons[type] || FileText;
};

// Helper to determine input type
const getInputType = (fieldType: string) => {
  const typeMap: Record<string, string> = {
    text: 'text',
    email: 'email',
    password: 'password',
    number: 'number',
    tel: 'tel',
    date: 'date',
    'datetime-local': 'datetime-local',
    time: 'time',
    month: 'month',
    week: 'week'
  };
  return typeMap[fieldType] || 'text';
};
</script>

<template>
  <AppLayout>
    <Head :title="form.title" />

    <div class="max-w-3xl mx-auto py-12 px-4">
      <!-- Back Link -->
      <Link
        v-if="$page.props.auth.user"
        href="/forms"
        class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition mb-6"
      >
        <ChevronLeft class="w-4 h-4" />
        Back to Forms
      </Link>

      <!-- Form Header -->
      <div class="text-center mb-8">
        <div v-if="form.form_type === 'course_assignment'" class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-full text-xs font-bold uppercase tracking-widest mb-4">
           <GraduationCap class="w-3.5 h-3.5" />
           Course Assignment
        </div>

        <h1 class="text-4xl font-bold text-white mb-2">{{ form.title }}</h1>
        <p v-if="form.description" class="text-slate-400">{{ form.description }}</p>

        <div v-if="form.cohort" class="mt-2 text-sm text-slate-500">
           Cohort: <span class="text-slate-300 font-medium">{{ form.cohort }}</span>
        </div>

        <div v-if="!form.is_active" class="mt-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg flex items-center justify-center gap-2">
          <AlertCircle class="w-5 h-5 text-red-400" />
          <span class="text-red-400">This form is no longer accepting responses</span>
        </div>
      </div>

      <!-- Form Fields -->
      <form @submit.prevent="handleSubmit" v-if="form.is_active">
        <Card class="mb-6">
          <div class="space-y-6">
            <div
              v-for="(field, index) in fields"
              :key="index"
              class="pb-4 last:pb-0 border-b border-slate-800 last:border-0"
            >
              <div class="mb-2">
                <label class="block text-white font-medium mb-1">
                  {{ field.label }}
                  <span v-if="field.required" class="text-red-400 ml-1">*</span>
                </label>
                <p v-if="field.description" class="text-sm text-slate-400 mb-3">
                  {{ field.description }}
                </p>

                <!-- Textarea -->
                <textarea
                  v-if="field.type === 'textarea'"
                  v-model="formData[field.name]"
                  :placeholder="field.placeholder"
                  :required="field.required"
                  rows="4"
                  class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-blue-500 resize-none"
                />

                <!-- Checkbox with options -->
                <div v-else-if="field.type === 'checkbox' && field.options && field.options.length > 0" class="space-y-2">
                  <label v-for="(option, optIdx) in field.options" :key="optIdx" class="flex items-center gap-2">
                    <input
                      type="checkbox"
                      :value="option"
                      v-model="formData[field.name]"
                      class="rounded bg-slate-900 border-slate-700 text-blue-500 focus:ring-blue-500"
                    />
                    <span class="text-slate-300">{{ option }}</span>
                  </label>
                </div>

                <!-- Single Checkbox -->
                <label v-else-if="field.type === 'checkbox'" class="flex items-center gap-2">
                  <input
                    type="checkbox"
                    v-model="formData[field.name]"
                    class="rounded bg-slate-900 border-slate-700 text-blue-500 focus:ring-blue-500"
                  />
                  <span class="text-slate-300">Yes</span>
                </label>

                <!-- Radio buttons -->
                <div v-else-if="field.type === 'radio'" class="space-y-2">
                  <label v-for="(option, optIdx) in field.options" :key="optIdx" class="flex items-center gap-2">
                    <input
                      type="radio"
                      :value="option"
                      v-model="formData[field.name]"
                      :name="field.name"
                      class="bg-slate-900 border-slate-700 text-blue-500 focus:ring-blue-500"
                    />
                    <span class="text-slate-300">{{ option }}</span>
                  </label>
                </div>

                <!-- Select dropdown -->
                <select
                  v-else-if="field.type === 'select'"
                  v-model="formData[field.name]"
                  :required="field.required"
                  class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
                >
                  <option value="">Select an option</option>
                  <option v-for="(option, optIdx) in field.options" :key="optIdx" :value="option">
                    {{ option }}
                  </option>
                </select>

                <!-- File/Image upload -->
                <div v-else-if="field.type === 'file' || field.type === 'image'" class="relative">
                  <input
                    type="file"
                    :accept="field.type === 'image' ? 'image/*' : '*/*'"
                    @change="(e) => handleFileChange(field.name, e)"
                    :required="field.required"
                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                  />
                </div>

                <!-- Standard input fields -->
                <div v-else class="relative">
                  <component
                    :is="getFieldIcon(field.type)"
                    v-if="getFieldIcon(field.type)"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"
                  />
                  <input
                    :type="getInputType(field.type)"
                    v-model="formData[field.name]"
                    :placeholder="field.placeholder"
                    :required="field.required"
                    :step="field.type === 'number' ? 'any' : undefined"
                    :class="`w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-blue-500 ${getFieldIcon(field.type) ? 'pl-10' : ''}`"
                  />
                </div>
              </div>
            </div>
          </div>
        </Card>

        <!-- Email Notification -->
        <Card class="mb-6">
          <div>
            <label class="block text-white font-medium mb-2">
              Email Confirmation (Optional)
            </label>
            <p class="text-sm text-slate-400 mb-3">
              Receive a confirmation email after submitting
            </p>
            <div class="relative">
              <Mail class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
              <input
                v-model="submitForm.email_to_notify"
                type="email"
                placeholder="your@email.com"
                class="w-full px-4 py-2 pl-10 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
              />
            </div>
          </div>
        </Card>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button
            type="submit"
            :disabled="submitForm.processing"
            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium transition disabled:opacity-50"
          >
            <Send class="w-4 h-4" />
            {{ submitForm.processing ? 'Submitting...' : 'Submit Response' }}
          </button>
        </div>
      </form>

      <!-- Inactive Form Message -->
      <div v-else class="text-center py-12">
        <CheckCircle class="w-16 h-16 text-slate-600 mx-auto mb-4" />
        <h2 class="text-2xl font-bold text-white mb-2">Form Closed</h2>
        <p class="text-slate-400">This form is no longer accepting submissions.</p>
      </div>
    </div>
  </AppLayout>
</template>
