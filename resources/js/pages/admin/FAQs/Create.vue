<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import FaqForm, { type FaqFormData } from '@/pages/admin/FAQs/FaqForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

defineProps<{ services: Array<{ id: number; title: string }> }>();

const { t } = useI18n();

const form = useForm<FaqFormData>({ service_id: null, question: '', answer: '', sort_order: 0, is_active: true });

const submit = () => form.post('/admin/faqs');
</script>

<template>
    <AppLayout>
        <Head :title="t('faqs.create.heading')" />
        <FaqForm mode="create" :form="form" :services="services" @submit="submit" />
    </AppLayout>
</template>
