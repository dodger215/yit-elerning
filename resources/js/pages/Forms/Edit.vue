<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

interface FormField {
  id: string;
  type: string;
  name: string;
  label: string;
  description: string;
  placeholder: string;
  required: boolean;
  options?: string[];
}

const props = defineProps<{
  courses: Array<{ id: string, title: string }>,
  form: {
    id: string;
    title: string;
    description: string;
    fields: string | any[];
    is_active: boolean;
    form_type: string;
    cohort: string | null;
  }
}>();

const form = useForm({
  title: props.form.title,
  description: props.form.description || '',
  form_type: props.form.form_type,
  course_id: props.form.course_id || '',
  cohort: props.form.cohort || '',
  fields: (typeof props.form.fields === 'string' ? JSON.parse(props.form.fields) : props.form.fields) as FormField[]
});

const fieldTypes = [
  { value: 'text', label: 'Short Text', icon: 'fa-paragraph' },
  { value: 'textarea', label: 'Long Text', icon: 'fa-align-left' },
  { value: 'email', label: 'Email', icon: 'fa-envelope' },
  { value: 'number', label: 'Number', icon: 'fa-hashtag' },
  { value: 'tel', label: 'Phone', icon: 'fa-phone' },
  { value: 'date', label: 'Date', icon: 'fa-calendar' },
  { value: 'datetime-local', label: 'Date & Time', icon: 'fa-calendar-alt' },
  { value: 'time', label: 'Time', icon: 'fa-clock' },
  { value: 'password', label: 'Password', icon: 'fa-lock' },
  { value: 'checkbox', label: 'Checkbox', icon: 'fa-check-square' },
  { value: 'radio', label: 'Radio', icon: 'fa-dot-circle' },
  { value: 'select', label: 'Dropdown', icon: 'fa-chevron-down' },
  { value: 'file', label: 'File Upload', icon: 'fa-upload' },
  { value: 'image', label: 'Image Upload', icon: 'fa-image' },
];

const addField = () => {
  form.fields.push({
    id: Date.now().toString(),
    type: 'text',
    name: `field_${form.fields.length + 1}`,
    label: 'Untitled Question',
    description: '',
    placeholder: '',
    required: false,
    options: []
  });
};

const removeField = (index: number) => {
  if (confirm('Remove this question?')) {
    form.fields.splice(index, 1);
  }
};

const duplicateField = (index: number) => {
  const fieldToDuplicate = { ...form.fields[index], id: Date.now().toString() };
  form.fields.splice(index + 1, 0, fieldToDuplicate);
};

const moveFieldUp = (index: number) => {
  if (index > 0) {
    [form.fields[index], form.fields[index - 1]] = [form.fields[index - 1], form.fields[index]];
  }
};

const moveFieldDown = (index: number) => {
  if (index < form.fields.length - 1) {
    [form.fields[index], form.fields[index + 1]] = [form.fields[index + 1], form.fields[index]];
  }
};

const addOption = (fieldIndex: number) => {
  if (!form.fields[fieldIndex].options) {
    form.fields[fieldIndex].options = [];
  }
  form.fields[fieldIndex].options!.push(`Option ${form.fields[fieldIndex].options!.length + 1}`);
};

const removeOption = (fieldIndex: number, optionIndex: number) => {
  form.fields[fieldIndex].options!.splice(optionIndex, 1);
};

const updateOption = (fieldIndex: number, optionIndex: number, value: string) => {
  if (form.fields[fieldIndex].options) {
    form.fields[fieldIndex].options![optionIndex] = value;
  }
};

const submitForm = () => {
  form.put(`/forms/${props.form.id}`, {
    preserveScroll: true,
    onSuccess: (response) => {
      console.log('Form updated successfully', response);
    },
    onError: (errors) => {
      console.error('Error updating form:', errors);
    }
  });
};

const getFieldIcon = (type: string) => {
  const field = fieldTypes.find(ft => ft.value === type);
  return field?.icon || 'fa-question';
};
</script>

<template>
  <AppLayout>
    <Head title="Edit Form" />

    <div class="max-w-5xl mx-auto py-8 px-4">
      <!-- Header -->
      <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg mb-4">
          <i class="fas fa-edit text-2xl text-white"></i>
        </div>
        <h1 class="text-4xl font-bold bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent mb-2">
          Edit Form
        </h1>
        <p class="text-slate-400">Update your form questions and settings</p>
      </div>

      <!-- Form Title Card -->
      <div class="bg-slate-900/50 backdrop-blur-sm border border-slate-800 rounded-2xl shadow-xl mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600/20 to-indigo-600/20 px-6 py-4 border-b border-slate-800">
          <div class="flex items-center gap-3">
            <i class="fas fa-info-circle text-blue-400"></i>
            <h2 class="font-semibold text-white">Form Information</h2>
          </div>
        </div>
        <div class="p-6 space-y-5">
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">
              <i class="fas fa-heading mr-2 text-blue-400"></i>
              Form Title <span class="text-red-400">*</span>
            </label>
            <input
              v-model="form.title"
              type="text"
              placeholder="e.g., Customer Feedback Survey"
              class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
            />
            <div v-if="form.errors.title" class="text-red-400 text-xs mt-2 flex items-center gap-1">
              <i class="fas fa-exclamation-circle"></i>
              {{ form.errors.title }}
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">
              <i class="fas fa-align-left mr-2 text-blue-400"></i>
              Form Description
            </label>
            <textarea
              v-model="form.description"
              rows="3"
              placeholder="Describe what this form is about and how to fill it..."
              class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition resize-none"
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">
                <i class="fas fa-tasks mr-2 text-blue-400"></i>
                Form Type
              </label>
              <select
                v-model="form.form_type"
                class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
              >
                <option value="general">General (e.g. Google Forms)</option>
                <option value="course_assignment">Course Assignment</option>
              </select>
            </div>

            <div v-if="form.form_type === 'course_assignment'">
              <label class="block text-sm font-medium text-slate-300 mb-2">
                <i class="fas fa-graduation-cap mr-2 text-blue-400"></i>
                Assign to Course
              </label>
              <select
                v-model="form.course_id"
                class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
              >
                <option value="" disabled>Select a course</option>
                <option v-for="course in courses" :key="course.id" :value="course.id">
                  {{ course.title }}
                </option>
              </select>
              <div v-if="form.errors.course_id" class="text-red-400 text-xs mt-2">
                {{ form.errors.course_id }}
              </div>
            </div>

            <div v-if="form.form_type === 'course_assignment'">
              <label class="block text-sm font-medium text-slate-300 mb-2">
                <i class="fas fa-users mr-2 text-blue-400"></i>
                Cohort Name (Optional)
              </label>
              <input
                v-model="form.cohort"
                type="text"
                placeholder="e.g., Cohort A"
                class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Form Fields -->
      <div class="space-y-4 mb-8">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <i class="fas fa-layer-group text-blue-400"></i>
            <h2 class="text-lg font-semibold text-white">Form Questions</h2>
            <span class="text-xs text-slate-500 bg-slate-800 px-2 py-1 rounded-full">{{ form.fields.length }} questions</span>
          </div>
        </div>

        <div
          v-for="(field, index) in form.fields"
          :key="field.id"
          class="group relative"
        >
          <div class="bg-slate-900/50 backdrop-blur-sm border border-slate-800 rounded-2xl shadow-xl overflow-hidden hover:border-slate-700 transition-all duration-200">
            <!-- Field Header -->
            <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 px-5 py-3 border-b border-slate-800 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="cursor-move text-slate-500 hover:text-slate-300 transition">
                  <i class="fas fa-grip-vertical"></i>
                </div>
                <div class="flex items-center gap-2">
                  <i :class="`fas ${getFieldIcon(field.type)} text-blue-400 text-sm`"></i>
                  <select
                    v-model="field.type"
                    class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-1.5 text-sm text-white focus:outline-none focus:border-blue-500 cursor-pointer"
                  >
                    <option v-for="type in fieldTypes" :key="type.value" :value="type.value">
                      {{ type.label }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="flex items-center gap-1">
                <button
                  @click="moveFieldUp(index)"
                  v-if="index > 0"
                  class="p-1.5 text-slate-500 hover:text-slate-300 hover:bg-slate-800 rounded-lg transition"
                  title="Move Up"
                >
                  <i class="fas fa-chevron-up text-xs"></i>
                </button>
                <button
                  @click="moveFieldDown(index)"
                  v-if="index < form.fields.length - 1"
                  class="p-1.5 text-slate-500 hover:text-slate-300 hover:bg-slate-800 rounded-lg transition"
                  title="Move Down"
                >
                  <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <button
                  @click="duplicateField(index)"
                  class="p-1.5 text-slate-500 hover:text-emerald-400 hover:bg-slate-800 rounded-lg transition"
                  title="Duplicate"
                >
                  <i class="far fa-copy"></i>
                </button>
                <button
                  @click="removeField(index)"
                  class="p-1.5 text-slate-500 hover:text-red-400 hover:bg-slate-800 rounded-lg transition"
                  title="Remove"
                >
                  <i class="far fa-trash-alt"></i>
                </button>
              </div>
            </div>

            <!-- Field Body -->
            <div class="p-5 space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                  Question Label
                  <span class="text-red-400 text-xs ml-1">*</span>
                </label>
                <input
                  v-model="field.label"
                  type="text"
                  placeholder="Enter your question"
                  class="w-full px-4 py-2.5 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                  <i class="fas fa-info-circle mr-1 text-slate-500 text-xs"></i>
                  Help Text (Optional)
                </label>
                <input
                  v-model="field.description"
                  type="text"
                  placeholder="Additional instructions for respondents"
                  class="w-full px-4 py-2.5 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
                />
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">
                    <i class="fas fa-tag mr-1 text-slate-500 text-xs"></i>
                    Field Name
                  </label>
                  <input
                    v-model="field.name"
                    type="text"
                    placeholder="unique_field_name"
                    class="w-full px-4 py-2.5 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition font-mono text-sm"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">
                    <i class="fas fa-font mr-1 text-slate-500 text-xs"></i>
                    Placeholder
                  </label>
                  <input
                    v-model="field.placeholder"
                    type="text"
                    placeholder="e.g., Enter your answer"
                    class="w-full px-4 py-2.5 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
                  />
                </div>
              </div>

              <!-- Options Builder -->
              <div v-if="['select', 'radio', 'checkbox'].includes(field.type)">
                <label class="block text-sm font-medium text-slate-300 mb-3">
                  <i class="fas fa-list-ul mr-1 text-slate-500 text-xs"></i>
                  Answer Options
                </label>
                <div class="space-y-2">
                  <div
                    v-for="(option, optIndex) in field.options"
                    :key="optIndex"
                    class="flex items-center gap-2"
                  >
                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-500 text-sm font-mono">
                      {{ optIndex + 1 }}
                    </div>
                    <input
                      :value="option"
                      @input="updateOption(index, optIndex, ($event.target as HTMLInputElement).value)"
                      type="text"
                      placeholder="Option text"
                      class="flex-1 px-4 py-2 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-blue-500 transition"
                    />
                    <button
                      @click="removeOption(index, optIndex)"
                      class="p-2 text-slate-500 hover:text-red-400 hover:bg-slate-800 rounded-lg transition"
                    >
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                  <button
                    @click="addOption(index)"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm text-blue-400 hover:text-blue-300 hover:bg-blue-400/10 rounded-xl transition"
                  >
                    <i class="fas fa-plus-circle"></i>
                    Add Option
                  </button>
                </div>
              </div>

              <!-- Required Toggle -->
              <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                  <div class="relative">
                    <input
                      v-model="field.required"
                      type="checkbox"
                      class="sr-only peer"
                    />
                    <div class="w-10 h-5 bg-slate-700 rounded-full peer peer-checked:bg-blue-600 transition-all duration-300"></div>
                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full transition-all duration-300 peer-checked:translate-x-5"></div>
                  </div>
                  <span class="text-sm text-slate-300">
                    <i class="fas fa-asterisk text-xs text-red-400 mr-1" v-if="field.required"></i>
                    Required question
                  </span>
                </label>

                <div class="text-xs text-slate-500">
                  <i class="far fa-question-circle mr-1"></i>
                  Respondents must answer this question
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Question Button -->
      <div class="text-center mb-8">
        <button
          @click="addField"
          class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-white font-medium transition-all hover:scale-105"
        >
          <i class="fas fa-plus-circle"></i>
          Add New Question
        </button>
      </div>

      <!-- Edit Form Button -->
      <div class="flex justify-end">
        <button
          @click="submitForm"
          :disabled="form.processing"
          class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl text-white font-semibold transition-all disabled:opacity-50 shadow-lg"
        >
          <i class="fas fa-save"></i>
          {{ form.processing ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #1e293b;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #475569;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #64748b;
}
</style>
