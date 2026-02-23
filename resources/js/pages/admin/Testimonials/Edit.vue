<template>
    <AppLayout>
        <Head :title="t('testimonials.edit.title')" />

        <TestimonialForm
            mode="edit"
            v-model:name="form.name"
            v-model:position="form.position"
            v-model:company="form.company"
            v-model:text="form.text"
            v-model:rating="form.rating"
            v-model:avatar="form.avatar"
            v-model:removeAvatar="form.remove_avatar"
            v-model:sortOrder="form.sort_order"
            v-model:isActive="form.is_active"
            :processing="form.processing"
            :existingAvatarUrl="props.testimonial.avatar_url"
            @submit="submit"
        />
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '@/layouts/AppLayout.vue';
import TestimonialForm from '@/pages/admin/Testimonials/TestimonialForm.vue';

const { t } = useI18n();

interface Props {
    testimonial: {
        id: number;
        name: string;
        position: string | null;
        company: string | null;
        text: string;
        rating: number;
        avatar_url: string | null;
        sort_order: number;
        is_active: boolean;
    };
}

const props = defineProps<Props>();

const form = useForm({
    name: props.testimonial.name,
    position: props.testimonial.position || '',
    company: props.testimonial.company || '',
    text: props.testimonial.text,
    rating: props.testimonial.rating,
    avatar: null as File | null,
    remove_avatar: false,
    sort_order: props.testimonial.sort_order,
    is_active: props.testimonial.is_active,
});

const submit = () => {
    form.put(`/admin/testimonials/${props.testimonial.id}`);
};
</script>
