<template>
    <AppLayout>
        <Head :title="t('site_settings.title')" />

        <div class="space-y-6 p-4">
            <div>
                <h1 class="text-3xl font-bold">{{ t('site_settings.title') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('site_settings.subtitle') }}</p>
            </div>

            <form class="space-y-6" @submit.prevent="submit">
                <div v-for="group in groups" :key="group.key" class="rounded-lg border border-border bg-card p-6">
                    <h2 class="text-xl font-semibold">{{ t(`site_settings.groups.${group.key}.title`) }}</h2>
                    <p class="mt-1 text-sm text-muted-foreground">{{ t(`site_settings.groups.${group.key}.help`) }}</p>

                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div v-for="field in group.fields" :key="field.key" :class="field.wide && 'md:col-span-2'">
                            <label :for="`setting-${field.key}`" class="mb-2 block text-sm font-medium">
                                {{ t(`site_settings.fields.${field.key}.label`) }}
                            </label>
                            <textarea
                                v-if="field.type === 'textarea'"
                                :id="`setting-${field.key}`"
                                v-model="form[field.key]"
                                rows="2"
                                class="w-full rounded-md border border-input bg-background px-3 py-2"
                                :disabled="!canUpdate"
                            />
                            <input
                                v-else
                                :id="`setting-${field.key}`"
                                v-model="form[field.key]"
                                :type="field.type"
                                class="w-full rounded-md border border-input bg-background px-3 py-2"
                                :placeholder="field.placeholder"
                                :disabled="!canUpdate"
                            />
                            <p class="mt-1.5 text-xs text-muted-foreground">{{ t(`site_settings.fields.${field.key}.help`) }}</p>
                            <InputError :message="form.errors[field.key]" />
                        </div>
                    </div>
                </div>

                <div v-if="canUpdate" class="flex items-center justify-end">
                    <Button type="submit" :loading="form.processing">{{ t('site_settings.save') }}</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

type SettingKey =
    | 'phone'
    | 'whatsapp'
    | 'email'
    | 'enquiry_email'
    | 'address'
    | 'maps_url'
    | 'office_hours'
    | 'response_time'
    | 'facebook_url'
    | 'linkedin_url'
    | 'x_url'
    | 'youtube_url'
    | 'instagram_url';

interface Field {
    key: SettingKey;
    type: 'text' | 'tel' | 'email' | 'url' | 'textarea';
    placeholder?: string;
    wide?: boolean;
}

const props = defineProps<{
    settings: Record<SettingKey, string | null>;
}>();

const { t } = useI18n();
const { can } = usePermissions();
const canUpdate = computed(() => can('settings.update'));

const groups: Array<{ key: string; fields: Field[] }> = [
    {
        key: 'contact',
        fields: [
            { key: 'phone', type: 'tel', placeholder: '+88 01XXX-XXXXXX' },
            { key: 'whatsapp', type: 'tel', placeholder: '+88 01XXX-XXXXXX' },
            { key: 'email', type: 'email' },
            { key: 'enquiry_email', type: 'email' },
        ],
    },
    {
        key: 'office',
        fields: [
            { key: 'address', type: 'textarea', wide: true },
            { key: 'maps_url', type: 'url', placeholder: 'https://maps.app.goo.gl/...', wide: true },
            { key: 'office_hours', type: 'text' },
            { key: 'response_time', type: 'text' },
        ],
    },
    {
        key: 'social',
        fields: [
            { key: 'facebook_url', type: 'url', placeholder: 'https://' },
            { key: 'linkedin_url', type: 'url', placeholder: 'https://' },
            { key: 'x_url', type: 'url', placeholder: 'https://' },
            { key: 'youtube_url', type: 'url', placeholder: 'https://' },
            { key: 'instagram_url', type: 'url', placeholder: 'https://' },
        ],
    },
];

const initial = Object.fromEntries(
    groups.flatMap((g) => g.fields).map((f) => [f.key, props.settings[f.key] ?? '']),
) as Record<SettingKey, string>;

const form = useForm(initial);

const submit = () => {
    form.put('/admin/site-settings', { preserveScroll: true });
};
</script>
