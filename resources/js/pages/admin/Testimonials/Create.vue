<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import TestimonialForm, { type TestimonialFormData } from '@/pages/admin/Testimonials/TestimonialForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm<TestimonialFormData>({ name: '', position: '', company: '', text: '', rating: 5, sort_order: 0, is_active: true });

const submit = () => form.post('/admin/testimonials');
</script>

<template>
    <AppLayout>
        <Head :title="t('testimonials.create.title')" />
        <TestimonialForm mode="create" :form="form" @submit="submit" />
    </AppLayout>
</template>
