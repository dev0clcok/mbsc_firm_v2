<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import ServiceForm, { type ServiceFormData } from '@/pages/admin/Services/ServiceForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm<ServiceFormData>({
    slug: '',
    title: '',
    short_description: '',
    description: '',
    icon_svg: '',
    image: null,
    remove_image: false,
    image_alt: '',
    features: [],
    process_steps: [],
    documents: [],
    timeline: '',
    fees: '',
    sort_order: 0,
    is_active: true,
});

const submit = () => {
    form.post('/admin/services', { forceFormData: true });
};
</script>

<template>
    <AppLayout>
        <Head :title="t('services.create.title')" />
        <ServiceForm mode="create" :form="form" @submit="submit" />
    </AppLayout>
</template>
