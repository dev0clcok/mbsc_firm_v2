<template>
    <AppLayout>
        <Head :title="t('services.edit.title')" />

        <ServiceForm
            mode="edit"
            v-model:slug="form.slug"
            v-model:title="form.title"
            v-model:shortDescription="form.short_description"
            v-model:description="form.description"
            v-model:iconSvg="form.icon_svg"
            v-model:image="form.image"
            v-model:removeImage="form.remove_image"
            v-model:features="form.features"
            v-model:sortOrder="form.sort_order"
            v-model:isActive="form.is_active"
            :processing="form.processing"
            :existingImageUrl="props.service.image_url"
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

interface Props {
    service: {
        id: number;
        slug: string;
        title: string;
        short_description: string | null;
        description: string | null;
        icon_svg: string | null;
        image_url: string | null;
        features: string[] | null;
        sort_order: number;
        is_active: boolean;
    };
}

const props = defineProps<Props>();

const form = useForm({
    slug: props.service.slug,
    title: props.service.title,
    short_description: props.service.short_description || '',
    description: props.service.description || '',
    icon_svg: props.service.icon_svg || '',
    image: null as File | null,
    remove_image: false,
    features: (props.service.features || []) as string[],
    sort_order: props.service.sort_order,
    is_active: props.service.is_active,
});

const submit = () => {
    form.put(`/admin/services/${props.service.id}`);
};
</script>

