<script setup lang="ts">
import FormActions from '@/components/admin/FormActions.vue';
import FormField from '@/components/admin/FormField.vue';
import FormSection from '@/components/admin/FormSection.vue';
import RichTextEditor from '@/components/admin/RichTextEditor.vue';
import { useUnsavedWarning } from '@/composables/useUnsavedWarning';
import type { InertiaForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

export interface FaqFormData {
    service_id: number | null;
    question: string;
    answer: string;
    sort_order: number;
    is_active: boolean;
}

const props = defineProps<{
    mode: 'create' | 'edit';
    form: InertiaForm<FaqFormData>;
    services: Array<{ id: number; title: string }>;
    viewHref?: string;
}>();

const emit = defineEmits<{ (e: 'submit'): void }>();

const { t } = useI18n();

useUnsavedWarning(() => props.form.isDirty, t('common.leave_unsaved'));

const errors = () => props.form.errors as Record<string, string | undefined>;
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ mode === 'edit' ? t('faqs.edit.heading') : t('faqs.create.heading') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('common.required_note') }}</p>
            </div>
            <Link href="/admin/faqs" class="inline-flex min-h-10 items-center text-sm font-medium text-muted-foreground hover:text-foreground">
                {{ t('faqs.back_to_list') }}
            </Link>
        </div>

        <form class="space-y-6" novalidate @submit.prevent="emit('submit')">
            <FormSection :title="t('faqs.form.details')" :description="t('faqs.form.details_help')">
                <FormField v-slot="f" :label="t('faqs.form.question')" required :error="errors().question">
                    <textarea :id="f.id" v-model="form.question" rows="2" required class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" @input="form.clearErrors('question')" />
                </FormField>
                <div>
                    <p class="mb-1.5 block text-sm font-medium">{{ t('faqs.form.answer') }} <span class="text-destructive" aria-hidden="true">*</span></p>
                    <RichTextEditor v-model="form.answer" :placeholder="t('faqs.form.answer_placeholder')" />
                    <p v-if="errors().answer" class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-500">{{ errors().answer }}</p>
                </div>
            </FormSection>

            <FormSection :title="t('faqs.form.placement')" :description="t('faqs.form.placement_help')">
                <div class="grid gap-5 md:grid-cols-3">
                    <FormField v-slot="f" :label="t('faqs.form.service')" :help="t('faqs.form.service_help')" :error="errors().service_id">
                        <select :id="f.id" v-model="form.service_id" class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-describedby="f.describedBy">
                            <option :value="null">{{ t('faqs.form.general') }}</option>
                            <option v-for="service in services" :key="service.id" :value="service.id">{{ service.title }}</option>
                        </select>
                    </FormField>
                    <FormField v-slot="f" :label="t('faqs.form.sort_order')" :error="errors().sort_order">
                        <input :id="f.id" v-model.number="form.sort_order" type="number" class="w-full rounded-md border border-input bg-background px-3 py-2" />
                    </FormField>
                    <label class="flex min-h-10 items-center gap-3 self-end text-sm font-medium">
                        <input v-model="form.is_active" type="checkbox" class="size-4 rounded border-input" />
                        {{ t('faqs.form.active') }}
                    </label>
                </div>
            </FormSection>

            <FormActions
                :save-label="mode === 'edit' ? t('faqs.edit.save') : t('faqs.create.save')"
                cancel-href="/admin/faqs"
                :processing="form.processing"
                :dirty="form.isDirty"
                :view-href="viewHref"
            />
        </form>
    </div>
</template>
