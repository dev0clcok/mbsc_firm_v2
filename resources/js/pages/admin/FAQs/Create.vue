<template>
    <AppLayout>
        <Head title="Create FAQ" />

        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Create New FAQ</h1>
                <Link
                    :href="faqsIndex().url"
                    class="text-muted-foreground hover:text-foreground"
                >
                    Back to FAQs
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="rounded-lg border border-border bg-card p-6">
                            <h2 class="mb-4 text-xl font-semibold">FAQ Details</h2>

                            <div class="space-y-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium">
                                        Question <span class="text-destructive">*</span>
                                    </label>
                                    <textarea
                                        v-model="form.question"
                                        rows="3"
                                        required
                                        class="w-full rounded-md border border-input bg-background px-3 py-2"
                                        placeholder="Enter the question..."
                                    />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">
                                        Answer <span class="text-destructive">*</span>
                                    </label>
                                    <RichTextEditor
                                        v-model="form.answer"
                                        placeholder="Enter the answer..."
                                    />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Category</label>
                                    <input
                                        v-model="form.category"
                                        type="text"
                                        list="categories"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2"
                                        placeholder="e.g., General, Company Registration, Tax"
                                    />
                                    <datalist id="categories">
                                        <option
                                            v-for="category in categories"
                                            :key="category"
                                            :value="category"
                                        />
                                    </datalist>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Service (optional)</label>
                                    <select
                                        v-model="form.service_id"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2"
                                    >
                                        <option :value="null">None</option>
                                        <option
                                            v-for="service in services"
                                            :key="service.id"
                                            :value="service.id"
                                        >
                                            {{ service.title }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Settings -->
                        <div class="rounded-lg border border-border bg-card p-6">
                            <h2 class="mb-4 text-xl font-semibold">Settings</h2>

                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <input
                                        v-model="form.is_active"
                                        type="checkbox"
                                        id="is_active"
                                        class="rounded border-input"
                                    />
                                    <label for="is_active" class="text-sm font-medium">
                                        Active
                                    </label>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Sort Order</label>
                                    <input
                                        v-model.number="form.sort_order"
                                        type="number"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <Link
                        :href="faqsIndex().url"
                        class="rounded-md border border-border bg-background px-4 py-2 hover:bg-muted"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Creating...' : 'Create FAQ' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import RichTextEditor from '@/components/admin/RichTextEditor.vue';
import { index as faqsIndex, create as faqsCreate, store as faqsStore } from '@/routes/admin/faqs';

interface Props {
    services: Array<{
        id: number;
        title: string;
    }>;
    categories: string[];
}

defineProps<Props>();

const form = useForm({
    question: '',
    answer: '',
    category: '',
    service_id: null as number | null,
    sort_order: 0,
    is_active: true,
});

const submit = () => {
    form.post(faqsStore().url);
};
</script>
