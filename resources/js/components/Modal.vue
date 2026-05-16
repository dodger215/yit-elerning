<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import { X } from 'lucide-vue-next';

const props = defineProps<{
    show: boolean;
    title?: string;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
}>();

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

const handleEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', handleEscape));
onUnmounted(() => document.removeEventListener('keydown', handleEscape));

const maxWidthClass = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-xl',
    '2xl': 'max-w-2xl',
}[props.maxWidth || 'md'];
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#0a0c10]/80 backdrop-blur-md">
                <Transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4"
                >
                    <div v-if="show" 
                         :class="[maxWidthClass]"
                         class="w-full bg-[#0d1117] border border-white/10 rounded-3xl shadow-2xl shadow-black overflow-hidden relative">
                        
                        <!-- Header -->
                        <div v-if="title" class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                            <h3 class="text-lg font-black text-white tracking-tight">{{ title }}</h3>
                            <button @click="close" class="p-2 hover:bg-white/5 rounded-xl text-slate-500 hover:text-white transition-all">
                                <X class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <slot />
                        </div>

                        <!-- Footer Slot -->
                        <div v-if="$slots.footer" class="px-6 py-4 bg-white/[0.02] border-t border-white/5 flex justify-end gap-3">
                            <slot name="footer" />
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
