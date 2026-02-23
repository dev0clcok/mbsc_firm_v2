<template>
    <AppLayout>
        <Head :title="t('testimonials.create.title')" />

        <TestimonialForm
            mode="create"
            v-model:name="form.name"
            v-model:position="form.position"
            v-model:company="form.company"
            v-model:text="form.text"
            v-model:rating="form.rating"
            v-model:avatar="form.avatar"
            v-model:sortOrder="form.sort_order"
            v-model:isActive="form.is_active"
            :processing="form.processing"
            :existingAvatarUrl="null"
            :removeAvatar="false"
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

const form = useForm({
    name: '',
    position: '',
    company: '',
    text: '',
    rating: 5,
    avatar: null as File | null,
    sort_order: 0,
    is_active: true,
});

const submit = () => {
    form.post('/admin/testimonials');
};
</script>
