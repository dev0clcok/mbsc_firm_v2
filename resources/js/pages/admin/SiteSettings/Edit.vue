<script setup lang="ts">
import FormActions from '@/components/admin/FormActions.vue';
import FormField from '@/components/admin/FormField.vue';
import FormSection from '@/components/admin/FormSection.vue';
import ImageField from '@/components/admin/ImageField.vue';
import { usePermissions } from '@/composables/usePermissions';
import { useUnsavedWarning } from '@/composables/useUnsavedWarning';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

type SettingKey =
    | 'phone'
    | 'whatsapp'
    | 'whatsapp_message'
    | 'email'
    | 'enquiry_email'
    | 'address'
    | 'maps_url'
    | 'map_latitude'
    | 'map_longitude'
    | 'office_hours'
    | 'facebook_url'
    | 'linkedin_url'
    | 'x_url'
    | 'youtube_url'
    | 'instagram_url'
    | 'seo_home_title'
    | 'seo_home_description';

type ImageKey = 'hero_home' | 'hero_services' | 'hero_about';

interface Field {
    key: SettingKey;
    type: 'text' | 'tel' | 'email' | 'url' | 'textarea';
    placeholder?: string;
    wide?: boolean;
    maxlength?: number;
}

const props = defineProps<{
    settings: Record<SettingKey, string | null>;
    images: Record<ImageKey, { url: string; width: number | null; height: number | null; alt?: string | null } | null>;
}>();

const { t } = useI18n();
const { can } = usePermissions();
const canUpdate = computed(() => can('settings.update'));

const groups: Array<{ key: string; fields: Field[] }> = [
    {
        key: 'contact',
        fields: [
            { key: 'phone', type: 'tel', placeholder: '+880 1XXX-XXXXXX' },
            { key: 'whatsapp', type: 'tel', placeholder: '+880 1XXX-XXXXXX' },
            { key: 'email', type: 'email' },
            { key: 'enquiry_email', type: 'email' },
            { key: 'whatsapp_message', type: 'textarea', wide: true },
        ],
    },
    {
        key: 'office',
        fields: [
            { key: 'address', type: 'textarea', wide: true },
            { key: 'office_hours', type: 'text' },
            { key: 'maps_url', type: 'url', placeholder: 'https://maps.app.goo.gl/...', wide: true },
            { key: 'map_latitude', type: 'text', placeholder: '22.3384' },
            { key: 'map_longitude', type: 'text', placeholder: '91.8317' },
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
    {
        key: 'seo',
        fields: [
            { key: 'seo_home_title', type: 'text', wide: true, maxlength: 70 },
            { key: 'seo_home_description', type: 'textarea', wide: true, maxlength: 170 },
        ],
    },
];

// Width divided by height of the slot each picture fills on the site.
const imageSlots: Array<{ key: ImageKey; ratio: number }> = [
    { key: 'hero_home', ratio: 4 / 3 },
    { key: 'hero_services', ratio: 3 / 2 },
    { key: 'hero_about', ratio: 3 / 2 },
];

const initial = Object.fromEntries(groups.flatMap((g) => g.fields).map((f) => [f.key, props.settings[f.key] ?? ''])) as Record<SettingKey, string>;

const form = useForm({
    ...initial,
    hero_home: null as File | null,
    hero_services: null as File | null,
    hero_about: null as File | null,
    hero_home_alt: props.images.hero_home?.alt ?? '',
    hero_services_alt: props.images.hero_services?.alt ?? '',
    hero_about_alt: props.images.hero_about?.alt ?? '',
    remove_hero_home: false,
    remove_hero_services: false,
    remove_hero_about: false,
});

useUnsavedWarning(() => form.isDirty, t('common.leave_unsaved'));

const errors = () => form.errors as Record<string, string | undefined>;

// Re-mounts the image fields after a save so they show the stored pictures.
const saved = ref(0);

const submit = () => {
    // Files cannot be sent with a real PUT request, so post and spoof the method.
    form.transform((data) => ({ ...data, _method: 'put' })).post('/admin/site-settings', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.defaults({
                ...form.data(),
                hero_home: null,
                hero_services: null,
                hero_about: null,
                remove_hero_home: false,
                remove_hero_services: false,
                remove_hero_about: false,
            });
            form.reset();
            saved.value++;
        },
    });
};
</script>

<template>
    <AppLayout>
        <Head :title="t('site_settings.title')" />

        <div>
            <h1 class="text-2xl font-semibold tracking-tight">{{ t('site_settings.title') }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">{{ t('site_settings.subtitle') }}</p>
        </div>

        <form class="space-y-6" novalidate @submit.prevent="submit">
            <FormSection v-for="group in groups" :key="group.key" :title="t(`site_settings.groups.${group.key}.title`)" :description="t(`site_settings.groups.${group.key}.help`)">
                <div class="grid gap-5 md:grid-cols-2">
                    <FormField
                        v-for="field in group.fields"
                        :key="field.key"
                        v-slot="f"
                        :class="field.wide && 'md:col-span-2'"
                        :label="t(`site_settings.fields.${field.key}.label`)"
                        :help="t(`site_settings.fields.${field.key}.help`)"
                        :error="errors()[field.key]"
                    >
                        <textarea
                            v-if="field.type === 'textarea'"
                            :id="f.id"
                            v-model="form[field.key]"
                            rows="2"
                            :maxlength="field.maxlength"
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                            :aria-invalid="f.invalid"
                            :aria-describedby="f.describedBy"
                            :disabled="!canUpdate"
                            @input="form.clearErrors(field.key)"
                        />
                        <input
                            v-else
                            :id="f.id"
                            v-model="form[field.key]"
                            :type="field.type"
                            :maxlength="field.maxlength"
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                            :placeholder="field.placeholder"
                            :aria-invalid="f.invalid"
                            :aria-describedby="f.describedBy"
                            :disabled="!canUpdate"
                            @input="form.clearErrors(field.key)"
                        />
                    </FormField>
                </div>
            </FormSection>

            <FormSection :title="t('site_settings.groups.images.title')" :description="t('site_settings.groups.images.help')">
                <ImageField
                    v-for="slot in imageSlots"
                    :key="`${slot.key}-${saved}`"
                    v-model="form[slot.key]"
                    v-model:remove="form[`remove_${slot.key}`]"
                    v-model:alt="form[`${slot.key}_alt`]"
                    :label="t(`site_settings.images.${slot.key}`)"
                    :help="t(`site_settings.images.${slot.key}_help`)"
                    :existing-url="images[slot.key]?.url"
                    :ratio="slot.ratio"
                    :error="errors()[slot.key]"
                    :alt-error="errors()[`${slot.key}_alt`]"
                    :disabled="!canUpdate"
                />
            </FormSection>

            <FormActions v-if="canUpdate" :save-label="t('site_settings.save')" cancel-href="/admin" :processing="form.processing" :dirty="form.isDirty" view-href="/" />
        </form>
    </AppLayout>
</template>
