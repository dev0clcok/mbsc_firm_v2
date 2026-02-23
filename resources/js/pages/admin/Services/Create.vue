<template>
    <AppLayout>
        <Head :title="t('services.create.title')" />

        <ServiceForm
            mode="create"
            v-model:slug="form.slug"
            v-model:title="form.title"
            v-model:shortDescription="form.short_description"
            v-model:description="form.description"
            v-model:iconSvg="form.icon_svg"
            v-model:image="form.image"
            v-model:features="form.features"
            v-model:sortOrder="form.sort_order"
            v-model:isActive="form.is_active"
            :processing="form.processing"
            :existingImageUrl="null"
            :removeImage="false"
            @submit="submit"
        />
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '@/layouts/AppLayout.vue';
import ServiceForm from '@/pages/admin/Services/ServiceForm.vue';

const { t } = useI18n();

const form = useForm({
    slug: '',
    title: '',
    short_description: '',
    description: '',
    icon_svg: '',
    image: null as File | null,
    features: [] as string[],
    sort_order: 0,
    is_active: true,
});

const submit = () => {
    form.post('/admin/services');
};
</script>

