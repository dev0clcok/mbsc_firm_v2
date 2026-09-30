<script setup lang="ts">
import FormActions from '@/components/admin/FormActions.vue';
import FormField from '@/components/admin/FormField.vue';
import FormSection from '@/components/admin/FormSection.vue';
import { useUnsavedWarning } from '@/composables/useUnsavedWarning';
import type { InertiaForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

export interface TestimonialFormData {
    name: string;
    position: string;
    company: string;
    text: string;
    rating: number;
    sort_order: number;
    is_active: boolean;
}

const props = defineProps<{
    mode: 'create' | 'edit';
    form: InertiaForm<TestimonialFormData>;
    viewHref?: string;
}>();

const emit = defineEmits<{ (e: 'submit'): void }>();

const { t } = useI18n();

useUnsavedWarning(() => props.form.isDirty, t('common.leave_unsaved'));

const errors = () => props.form.errors as Record<string, string | undefined>;
</script>

<template>
    <!-- The Inertia form object is shared with the page on purpose; fields bind straight to it. -->
    <!-- eslint-disable vue/no-mutating-props -->
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ mode === 'edit' ? t('testimonials.edit.heading') : t('testimonials.create.heading') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('common.required_note') }}</p>
            </div>
            <Link href="/admin/testimonials" class="inline-flex min-h-10 items-center text-sm font-medium text-muted-foreground hover:text-foreground">
                {{ t('testimonials.back_to_list') }}
            </Link>
        </div>

        <form class="space-y-6" novalidate @submit.prevent="emit('submit')">
            <FormSection :title="t('testimonials.form.details')" :description="t('testimonials.form.details_help')">
                <FormField v-slot="f" :label="t('testimonials.form.text')" required :error="errors().text">
                    <textarea :id="f.id" v-model="form.text" rows="4" required class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" @input="form.clearErrors('text')" />
                </FormField>
                <div class="grid gap-5 md:grid-cols-3">
                    <FormField v-slot="f" :label="t('testimonials.form.name')" required :error="errors().name">
                        <input :id="f.id" v-model="form.name" type="text" required class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" @input="form.clearErrors('name')" />
                    </FormField>
                    <FormField v-slot="f" :label="t('testimonials.form.position')" :error="errors().position">
                        <input :id="f.id" v-model="form.position" type="text" class="w-full rounded-md border border-input bg-background px-3 py-2" :placeholder="t('testimonials.form.position_placeholder')" />
                    </FormField>
                    <FormField v-slot="f" :label="t('testimonials.form.company')" :error="errors().company">
                        <input :id="f.id" v-model="form.company" type="text" class="w-full rounded-md border border-input bg-background px-3 py-2" />
                    </FormField>
                </div>
            </FormSection>

            <FormSection :title="t('testimonials.form.settings')">
                <div class="grid gap-5 md:grid-cols-2">
                    <label class="flex min-h-10 items-center gap-3 text-sm font-medium">
                        <input v-model="form.is_active" type="checkbox" class="size-4 rounded border-input" />
                        {{ t('testimonials.form.active') }}
                    </label>
                    <FormField v-slot="f" :label="t('testimonials.form.sort_order')" :error="errors().sort_order">
                        <input :id="f.id" v-model.number="form.sort_order" type="number" class="w-full rounded-md border border-input bg-background px-3 py-2" />
                    </FormField>
                </div>
            </FormSection>

            <FormActions
                :save-label="mode === 'edit' ? t('testimonials.edit.save') : t('testimonials.create.save')"
                cancel-href="/admin/testimonials"
                :processing="form.processing"
                :dirty="form.isDirty"
                :view-href="viewHref"
            />
        </form>
    </div>
</template>
