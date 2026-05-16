<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps<{
    roomName: string;
}>();

const form = useForm({
    name: '',
});

const submit = () => {
    form.post((window as any).route('test.meeting.join', { room: props.roomName }));
};
</script>

<template>
    <Head title="Join Meeting (Test)" />

    <div class="min-h-screen bg-slate-950 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Join Meeting</h1>
                <p class="text-slate-400">Room: {{ roomName }}</p>
                <div class="mt-4 px-3 py-1 bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs rounded-full inline-block">
                    Test Mode (No Auth)
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Your Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Enter your name to join"
                        required
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 rounded-xl shadow-lg shadow-blue-600/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ form.processing ? 'Joining...' : 'Enter Room' }}
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-slate-500 text-xs">
                    Powered by LiveKit & Youth In Tech
                </p>
            </div>
        </div>
    </div>
</template>
