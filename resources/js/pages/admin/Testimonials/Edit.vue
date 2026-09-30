<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import TestimonialForm, { type TestimonialFormData } from '@/pages/admin/Testimonials/TestimonialForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    testimonial: { id: number; name: string; position: string | null; company: string | null; text: string; rating: number; sort_order: number; is_active: boolean };
}>();

const form = useForm<TestimonialFormData>({
    name: props.testimonial.name,
    position: props.testimonial.position ?? '',
    company: props.testimonial.company ?? '',
    text: props.testimonial.text,
    rating: props.testimonial.rating,
    sort_order: props.testimonial.sort_order,
    is_active: props.testimonial.is_active,
});

const submit = () => form.put(`/admin/testimonials/${props.testimonial.id}`);
</script>

<template>
    <AppLayout>
        <Head :title="t('testimonials.edit.title')" />
        <TestimonialForm mode="edit" :form="form" :view-href="testimonial.is_active ? '/#home-testimonials' : undefined" @submit="submit" />
    </AppLayout>
</template>
