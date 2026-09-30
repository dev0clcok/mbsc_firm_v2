<template>
    <AppLayout>
        <Head :title="t('site_settings.title')" />

        <div class="space-y-6 p-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ t('site_settings.title') }}</h1>
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

                <div class="rounded-lg border border-border bg-card p-6">
                    <h2 class="text-xl font-semibold">{{ t('site_settings.groups.images.title') }}</h2>
                    <p class="mt-1 text-sm text-muted-foreground">{{ t('site_settings.groups.images.help') }}</p>

                    <div class="mt-4 grid gap-6 md:grid-cols-3">
                        <div v-for="key in imageKeys" :key="key">
                            <label :for="`setting-${key}`" class="mb-2 block text-sm font-medium">{{ t(`site_settings.images.${key}`) }}</label>
                            <img
                                v-if="images[key] && !form[`remove_${key}`]"
                                :src="images[key]!.url"
                                alt=""
                                class="mb-3 aspect-[3/2] w-full rounded-md border border-border object-cover"
                            />
                            <div v-else class="mb-3 flex aspect-[3/2] w-full items-center justify-center rounded-md border border-dashed border-border text-sm text-muted-foreground">
                                {{ t('site_settings.images.none') }}
                            </div>
                            <input
                                :id="`setting-${key}`"
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                class="block w-full text-sm text-muted-foreground file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-foreground hover:file:bg-primary/90"
                                :disabled="!canUpdate"
                                @change="form[key] = ($event.target as HTMLInputElement).files?.[0] ?? null"
                            />
                            <div v-if="images[key] && canUpdate" class="mt-3 flex items-center gap-2">
                                <input :id="`remove-${key}`" v-model="form[`remove_${key}`]" type="checkbox" class="rounded border-input" />
                                <label :for="`remove-${key}`" class="text-sm font-medium">{{ t('site_settings.images.remove') }}</label>
                            </div>
                            <InputError :message="form.errors[key]" />
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
    | 'whatsapp_message'
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

type ImageKey = 'hero_home' | 'hero_services' | 'hero_about';

const imageKeys: ImageKey[] = ['hero_home', 'hero_services', 'hero_about'];

const props = defineProps<{
    settings: Record<SettingKey, string | null>;
    images: Record<ImageKey, { url: string; width: number | null; height: number | null } | null>;
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
            { key: 'whatsapp_message', type: 'textarea', wide: true },
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

const form = useForm({
    ...initial,
    hero_home: null as File | null,
    hero_services: null as File | null,
    hero_about: null as File | null,
    remove_hero_home: false,
    remove_hero_services: false,
    remove_hero_about: false,
});

const submit = () => {
    // Files cannot be sent with a real PUT request, so post and spoof the method.
    form.transform((data) => ({ ...data, _method: 'put' })).post('/admin/site-settings', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset('hero_home', 'hero_services', 'hero_about', 'remove_hero_home', 'remove_hero_services', 'remove_hero_about');
            document.querySelectorAll<HTMLInputElement>('input[type="file"]').forEach((input) => (input.value = ''));
        },
    });
};
</script>
