<script setup lang="ts">
import FormActions from '@/components/admin/FormActions.vue';
import FormField from '@/components/admin/FormField.vue';
import FormSection from '@/components/admin/FormSection.vue';
import ImageField from '@/components/admin/ImageField.vue';
import { useUnsavedWarning } from '@/composables/useUnsavedWarning';
import type { InertiaForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { Plus, Trash2 } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

export interface TeamMemberFormData {
    name: string;
    position: string;
    specialization: string;
    social_links: Array<{ platform: string; url: string }>;
    image: File | null;
    remove_image: boolean;
    sort_order: number;
    is_active: boolean;
}

const props = defineProps<{
    mode: 'create' | 'edit';
    form: InertiaForm<TeamMemberFormData>;
    existingImageUrl?: string | null;
    viewHref?: string;
}>();

const emit = defineEmits<{ (e: 'submit'): void }>();

const { t } = useI18n();

useUnsavedWarning(() => props.form.isDirty, t('common.leave_unsaved'));

const errors = () => props.form.errors as Record<string, string | undefined>;

const platforms: Record<string, string> = {
    linkedin: 'LinkedIn',
    facebook: 'Facebook',
    twitter: 'X (Twitter)',
    instagram: 'Instagram',
    youtube: 'YouTube',
    email: 'Email',
};
</script>

<template>
    <!-- The Inertia form object is shared with the page on purpose; fields bind straight to it. -->
    <!-- eslint-disable vue/no-mutating-props -->
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ mode === 'edit' ? t('team_members.edit.heading') : t('team_members.create.heading') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('common.required_note') }}</p>
            </div>
            <Link href="/admin/team-members" class="inline-flex min-h-10 items-center text-sm font-medium text-muted-foreground hover:text-foreground">
                {{ t('team_members.back_to_list') }}
            </Link>
        </div>

        <form class="space-y-6" novalidate @submit.prevent="emit('submit')">
            <FormSection :title="t('team_members.form.details')" :description="t('team_members.form.details_help')">
                <FormField v-slot="f" :label="t('team_members.form.name')" required :error="errors().name">
                    <input :id="f.id" v-model="form.name" type="text" required class="w-full rounded-md border border-input bg-background px-3 py-2" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" @input="form.clearErrors('name')" />
                </FormField>
                <div class="grid gap-5 md:grid-cols-2">
                    <FormField v-slot="f" :label="t('team_members.form.position')" :error="errors().position">
                        <input :id="f.id" v-model="form.position" type="text" class="w-full rounded-md border border-input bg-background px-3 py-2" :placeholder="t('team_members.form.position_placeholder')" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" />
                    </FormField>
                    <FormField v-slot="f" :label="t('team_members.form.specialization')" :error="errors().specialization">
                        <input :id="f.id" v-model="form.specialization" type="text" class="w-full rounded-md border border-input bg-background px-3 py-2" :placeholder="t('team_members.form.specialization_placeholder')" :aria-invalid="f.invalid" :aria-describedby="f.describedBy" />
                    </FormField>
                </div>
            </FormSection>

            <FormSection :title="t('team_members.form.image')" :description="t('team_members.form.image_section_help')">
                <ImageField
                    v-model="form.image"
                    v-model:remove="form.remove_image"
                    :label="t('team_members.form.image')"
                    :existing-url="existingImageUrl"
                    :ratio="1"
                    :error="errors().image"
                    without-alt
                />
            </FormSection>

            <FormSection :title="t('team_members.form.social_links')" :description="t('team_members.form.social_links_help')">
                <div class="space-y-3">
                    <div v-for="(link, index) in form.social_links" :key="index">
                        <div class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
                            <select v-model="link.platform" class="w-full rounded-md border border-input bg-background px-3 py-2 sm:w-44" :aria-label="`${t('team_members.form.select_platform')} ${index + 1}`">
                                <option value="">{{ t('team_members.form.select_platform') }}</option>
                                <option v-for="(label, key) in platforms" :key="key" :value="key">{{ label }}</option>
                            </select>
                            <input
                                v-model="link.url"
                                :type="link.platform === 'email' ? 'email' : 'url'"
                                class="w-full flex-1 rounded-md border border-input bg-background px-3 py-2"
                                :placeholder="link.platform === 'email' ? 'name@example.com' : 'https://'"
                                :aria-label="`${t('team_members.form.link_address')} ${index + 1}`"
                                :aria-invalid="errors()[`social_links.${index}.url`] ? true : undefined"
                            />
                            <button
                                type="button"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md text-destructive hover:bg-destructive/10"
                                :aria-label="`${t('team_members.form.remove')} ${index + 1}`"
                                @click="form.social_links.splice(index, 1)"
                            >
                                <Trash2 class="size-4" />
                            </button>
                        </div>
                        <p v-if="errors()[`social_links.${index}.url`] || errors()[`social_links.${index}.platform`]" class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-500">
                            {{ errors()[`social_links.${index}.url`] || errors()[`social_links.${index}.platform`] }}
                        </p>
                    </div>
                    <button
                        type="button"
                        class="inline-flex min-h-10 items-center gap-2 rounded-md border border-dashed border-input px-3 py-2 text-sm font-medium hover:bg-muted"
                        @click="form.social_links.push({ platform: '', url: '' })"
                    >
                        <Plus class="size-4" />
                        {{ t('team_members.form.add_social_link') }}
                    </button>
                </div>
            </FormSection>

            <FormSection :title="t('team_members.form.settings')">
                <div class="grid gap-5 md:grid-cols-2">
                    <label class="flex min-h-10 items-center gap-3 text-sm font-medium">
                        <input v-model="form.is_active" type="checkbox" class="size-4 rounded border-input" />
                        {{ t('team_members.form.active') }}
                    </label>
                    <FormField v-slot="f" :label="t('team_members.form.sort_order')" :error="errors().sort_order">
                        <input :id="f.id" v-model.number="form.sort_order" type="number" class="w-full rounded-md border border-input bg-background px-3 py-2" />
                    </FormField>
                </div>
            </FormSection>

            <FormActions
                :save-label="mode === 'edit' ? t('team_members.edit.save') : t('team_members.create.save')"
                cancel-href="/admin/team-members"
                :processing="form.processing"
                :dirty="form.isDirty"
                :view-href="viewHref"
            />
        </form>
    </div>
</template>
