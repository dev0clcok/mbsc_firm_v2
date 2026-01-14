<template>
    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">
                {{ mode === 'edit' ? 'Edit FAQ' : 'Create New FAQ' }}
            </h1>
            <Link :href="faqsIndex().url" class="text-muted-foreground hover:text-foreground">
                Back to FAQs
            </Link>
        </div>

        <form @submit.prevent="emit('submit')" class="space-y-6">
            <div class="grid gap-6">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="rounded-lg border border-border bg-card p-6">
                        <h2 class="mb-4 text-xl font-semibold">FAQ Details</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium">
                                    Question <span class="text-destructive">*</span>
                                </label>
                                <textarea v-model="question" rows="3" required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                                    placeholder="Enter the question..." />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium">
                                    Answer <span class="text-destructive">*</span>
                                </label>
                                <RichTextEditor v-model="answer" placeholder="Enter the answer..." />
                            </div>

                            <div class="grid gap-6 grid-cols-1 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Category</label>
                                    <input v-model="category" type="text" list="categories"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2"
                                        placeholder="e.g., General, Company Registration, Tax" />
                                    <datalist id="categories">
                                        <option v-for="category in categories" :key="category" :value="category" />
                                    </datalist>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Service (optional)</label>
                                    <select v-model="serviceId"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2">
                                        <option :value="null">None</option>
                                        <option v-for="service in services" :key="service.id" :value="service.id">
                                            {{ service.title }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Settings -->
                    <div class="rounded-lg border border-border bg-card p-6">
                        <h2 class="mb-4 text-xl font-semibold">Settings</h2>

                        <div class="space-y-4 grid gap-6 grid-cols-1 md:grid-cols-3">
                            <div class="flex items-center gap-2">
                                <input v-model="isActive" type="checkbox" id="is_active" class="rounded border-input" />
                                <label for="is_active" class="text-sm font-medium">
                                    Active
                                </label>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium">Sort Order</label>
                                <input v-model.number="sortOrder" type="number"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <Link :href="faqsIndex().url"
                    class="rounded-md border border-border bg-background px-4 py-2 hover:bg-muted">
                    Cancel
                </Link>
                <button type="submit" :disabled="processing"
                    class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90 disabled:opacity-50">
                    <span v-if="mode === 'edit'">
                        {{ processing ? 'Updating...' : 'Update FAQ' }}
                    </span>
                    <span v-else>
                        {{ processing ? 'Creating...' : 'Create FAQ' }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import RichTextEditor from '@/components/admin/RichTextEditor.vue';
import { index as faqsIndex } from '@/routes/admin/faqs';

const props = defineProps<{
    mode: 'create' | 'edit';
    question: string;
    answer: string;
    category: string;
    serviceId: number | null;
    sortOrder: number;
    isActive: boolean;
    processing: boolean;
    services: Array<{ id: number; title: string }>;
    categories: string[];
}>();

const emit = defineEmits<{
    (e: 'submit'): void;
    (e: 'update:question', value: string): void;
    (e: 'update:answer', value: string): void;
    (e: 'update:category', value: string): void;
    (e: 'update:serviceId', value: number | null): void;
    (e: 'update:sortOrder', value: number): void;
    (e: 'update:isActive', value: boolean): void;
}>();

const question = computed({
    get: () => props.question,
    set: (v: string) => emit('update:question', v),
});
const answer = computed({
    get: () => props.answer,
    set: (v: string) => emit('update:answer', v),
});
const category = computed({
    get: () => props.category,
    set: (v: string) => emit('update:category', v),
});
const serviceId = computed({
    get: () => props.serviceId,
    set: (v: number | null) => emit('update:serviceId', v),
});
const sortOrder = computed({
    get: () => props.sortOrder,
    set: (v: number) => emit('update:sortOrder', v),
});
const isActive = computed({
    get: () => props.isActive,
    set: (v: boolean) => emit('update:isActive', v),
});
</script>
