<script setup lang="ts">
import FormActions from '@/components/admin/FormActions.vue';
import FormField from '@/components/admin/FormField.vue';
import FormSection from '@/components/admin/FormSection.vue';
import ImageField from '@/components/admin/ImageField.vue';
import StepList, { type Step } from '@/components/admin/StepList.vue';
import StringList from '@/components/admin/StringList.vue';
import { useUnsavedWarning } from '@/composables/useUnsavedWarning';
import type { InertiaForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

export interface ServiceFormData {
    slug: string;
    title: string;
    short_description: string;
    description: string;
    icon_svg: string;
    image: File | null;
    remove_image: boolean;
    image_alt: string;
    features: string[];
    process_steps: Step[];
    documents: string[];
    timeline: string;
    fees: string;
    sort_order: number;
    is_active: boolean;
}

const props = defineProps<{
    mode: 'create' | 'edit';
    form: InertiaForm<ServiceFormData>;
    existingImageUrl?: string | null;
    /** Public address of the service, for the "View on site" link. */
    viewHref?: string;
}>();

const emit = defineEmits<{ (e: 'submit'): void }>();

const { t } = useI18n();

useUnsavedWarning(() => props.form.isDirty, t('common.leave_unsaved'));

const errors = () => props.form.errors as Record<string, string | undefined>;

// On a new service the address follows the title until someone types
// their own. An existing service keeps its address: links may point at it.
const slugEdited = ref(props.mode === 'edit' || props.form.slug !== '');

const slugify = (title: string) =>
    title
        .toLowerCase()
        .replace(/&/g, ' and ')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');

watch(
    () => props.form.title,
    (title) => {
        // eslint-disable-next-line vue/no-mutating-props
        if (!slugEdited.value) props.form.slug = slugify(title);
    },
);

// A data address in an <img> draws the icon without running anything inside it.
const iconPreview = computed(() => {
    const svg = props.form.icon_svg?.trim();
    return svg && svg.startsWith('<svg') ? `data:image/svg+xml;charset=utf-8,${encodeURIComponent(svg)}` : null;
});
</script>

<template>
    <!-- The Inertia form object is shared with the page on purpose; fields bind straight to it. -->
    <!-- eslint-disable vue/no-mutating-props -->
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">
                    {{ mode === 'edit' ? t('services.edit.heading') : t('services.create.heading') }}
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('common.required_note') }}</p>
            </div>
            <Link href="/admin/services" class="inline-flex min-h-10 items-center text-sm font-medium text-muted-foreground hover:text-foreground">
                {{ t('services.back_to_list') }}
            </Link>
        </div>

        <form class="space-y-6" novalidate @submit.prevent="emit('submit')">
            <FormSection :title="t('services.form.details')" :description="t('services.form.details_help')">
                <div class="grid gap-5 md:grid-cols-2">
                    <FormField v-slot="f" :label="t('services.form.title')" required :error="errors().title">
                        <input :id="f.id" v-model="form.title" type="text" required class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" @input="form.clearErrors('title')" />
                    </FormField>
                    <FormField v-slot="f" :label="t('services.form.slug')" required :help="t('services.form.slug_help')" :error="errors().slug">
                        <input :id="f.id" v-model="form.slug" type="text" required class="w-full rounded-md border border-input bg-background px-3 py-2 font-mono text-sm" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" @input="((slugEdited = true), form.clearErrors('slug'))" />
                    </FormField>
                </div>
                <FormField v-slot="f" :label="t('services.form.short_description')" :help="t('services.form.short_description_help')" :error="errors().short_description">
                    <textarea :id="f.id" v-model="form.short_description" rows="2" class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" />
                </FormField>
                <FormField v-slot="f" :label="t('services.form.description')" :help="t('services.form.description_help')" :error="errors().description">
                    <textarea :id="f.id" v-model="form.description" rows="5" class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" />
                </FormField>
            </FormSection>

            <FormSection :title="t('services.form.features')" :description="t('services.form.features_help')">
                <StringList v-model="form.features" :add-label="t('services.form.add_feature')" :remove-label="t('services.form.remove')" :placeholder="t('services.form.feature_placeholder')" />
            </FormSection>

            <FormSection :title="t('services.form.steps')" :description="t('services.form.steps_help')">
                <StepList v-model="form.process_steps" />
            </FormSection>

            <FormSection :title="t('services.form.documents')" :description="t('services.form.documents_help')">
                <StringList v-model="form.documents" :add-label="t('services.form.add_document')" :remove-label="t('services.form.remove')" :placeholder="t('services.form.document_placeholder')" />
            </FormSection>

            <FormSection :title="t('services.form.timeline_fees')" :description="t('services.form.timeline_fees_help')">
                <div class="grid gap-5 md:grid-cols-2">
                    <FormField v-slot="f" :label="t('services.form.timeline')" :help="t('services.form.timeline_help')" :error="errors().timeline">
                        <textarea :id="f.id" v-model="form.timeline" rows="4" class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" />
                    </FormField>
                    <FormField v-slot="f" :label="t('services.form.fees')" :help="t('services.form.fees_help')" :error="errors().fees">
                        <textarea :id="f.id" v-model="form.fees" rows="4" class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" />
                    </FormField>
                </div>
            </FormSection>

            <FormSection :title="t('services.form.media')" :description="t('services.form.media_help')">
                <ImageField
                    v-model="form.image"
                    v-model:remove="form.remove_image"
                    v-model:alt="form.image_alt"
                    :label="t('services.form.image')"
                    :existing-url="existingImageUrl"
                    :ratio="3 / 2"
                    :error="errors().image"
                    :alt-error="errors().image_alt"
                />
                <FormField v-slot="f" :label="t('services.form.icon_svg')" :help="t('services.form.icon_svg_help')" :error="errors().icon_svg">
                    <div class="flex items-start gap-3">
                        <!-- Shown as an image so pasted markup can never run in the admin. -->
                        <span v-if="iconPreview" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md border border-border bg-muted" aria-hidden="true">
                            <img :src="iconPreview" alt="" class="h-6 w-6 dark:invert" />
                        </span>
                        <textarea :id="f.id" v-model="form.icon_svg" rows="4" class="w-full rounded-md border border-input bg-background px-3 py-2 font-mono text-xs" :placeholder="t('services.form.icon_svg_placeholder')" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" />
                    </div>
                </FormField>
            </FormSection>

            <FormSection :title="t('services.form.settings')" :description="t('services.form.settings_help')">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="flex min-h-10 items-center gap-3 text-sm font-medium">
                            <input v-model="form.is_active" type="checkbox" class="size-4 rounded border-input" />
                            {{ t('services.form.active') }}
                        </label>
                        <p class="mt-1 text-xs text-muted-foreground">{{ t('services.form.active_help') }}</p>
                    </div>
                    <FormField v-slot="f" :label="t('services.form.sort_order')" :error="errors().sort_order">
                        <input :id="f.id" v-model.number="form.sort_order" type="number" class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" />
                    </FormField>
                </div>
            </FormSection>

            <FormActions
                :save-label="mode === 'edit' ? t('services.edit.save') : t('services.create.save')"
                cancel-href="/admin/services"
                :processing="form.processing"
                :dirty="form.isDirty"
                :view-href="viewHref"
            />
        </form>
    </div>
</template>
