<script setup lang="ts">
import FormActions from '@/components/admin/FormActions.vue';
import FormField from '@/components/admin/FormField.vue';
import FormSection from '@/components/admin/FormSection.vue';
import { usePermissions } from '@/composables/usePermissions';
import { useUnsavedWarning } from '@/composables/useUnsavedWarning';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ShieldAlert, ShieldCheck } from 'lucide-vue-next';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{ policy: string; published: boolean }>();

const { t } = useI18n();
const { can } = usePermissions();
const canUpdate = computed(() => can('settings.update'));

const form = useForm({ policy: props.policy, published: props.published });

useUnsavedWarning(() => form.isDirty, t('common.leave_unsaved'));

const submit = () => form.put('/admin/privacy-policy', { preserveScroll: true });
</script>

<template>
    <AppLayout>
        <Head :title="t('privacy.title')" />

        <div>
            <h1 class="text-2xl font-semibold tracking-tight">{{ t('privacy.title') }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">{{ t('privacy.subtitle') }}</p>
        </div>

        <div
            v-if="published"
            class="flex gap-3 rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-emerald-900 dark:border-emerald-400/30 dark:bg-emerald-900/20 dark:text-emerald-200"
        >
            <ShieldCheck class="mt-0.5 size-5 shrink-0" />
            <div>
                <p class="font-semibold">{{ t('privacy.published_title') }}</p>
                <p class="text-sm">{{ t('privacy.published_text') }}</p>
            </div>
        </div>
        <div
            v-else
            class="flex gap-3 rounded-lg border border-amber-300 bg-amber-50 p-4 text-amber-900 dark:border-amber-400/30 dark:bg-amber-900/20 dark:text-amber-200"
        >
            <ShieldAlert class="mt-0.5 size-5 shrink-0" />
            <div>
                <p class="font-semibold">{{ t('privacy.draft_title') }}</p>
                <p class="text-sm">{{ t('privacy.draft_text') }}</p>
            </div>
        </div>

        <form class="space-y-6" novalidate @submit.prevent="submit">
            <FormSection :title="t('privacy.policy')">
                <FormField v-slot="f" :label="t('privacy.policy')" :help="t('privacy.policy_help')" :error="form.errors.policy">
                    <textarea
                        :id="f.id"
                        v-model="form.policy"
                        rows="24"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 leading-relaxed"
                        :aria-invalid="f.invalid"
                        :aria-describedby="f.describedBy"
                        :disabled="!canUpdate"
                    />
                </FormField>
                <div>
                    <label class="flex min-h-10 items-start gap-3 text-sm font-medium">
                        <input v-model="form.published" type="checkbox" class="mt-0.5 size-4 rounded border-input" :disabled="!canUpdate" />
                        {{ t('privacy.publish') }}
                    </label>
                    <p class="mt-1 pl-7 text-xs text-muted-foreground">{{ t('privacy.publish_help') }}</p>
                </div>
            </FormSection>

            <FormActions
                v-if="canUpdate"
                :save-label="t('privacy.save')"
                cancel-href="/admin"
                :processing="form.processing"
                :dirty="form.isDirty"
                :view-href="published ? '/privacy' : undefined"
            />
        </form>
    </AppLayout>
</template>
