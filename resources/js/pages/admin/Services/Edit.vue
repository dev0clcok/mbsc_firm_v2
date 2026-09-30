<script setup lang="ts">
import type { Step } from '@/components/admin/StepList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ServiceForm, { type ServiceFormData } from '@/pages/admin/Services/ServiceForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    service: {
        id: number;
        slug: string;
        title: string;
        short_description: string | null;
        description: string | null;
        icon_svg: string | null;
        image_url: string | null;
        image_alt: string | null;
        features: string[] | null;
        process_steps: Step[] | null;
        documents: string[] | null;
        timeline: string | null;
        fees: string | null;
        sort_order: number;
        is_active: boolean;
    };
}>();

const form = useForm<ServiceFormData>({
    slug: props.service.slug,
    title: props.service.title,
    short_description: props.service.short_description ?? '',
    description: props.service.description ?? '',
    icon_svg: props.service.icon_svg ?? '',
    image: null,
    remove_image: false,
    image_alt: props.service.image_alt ?? '',
    features: props.service.features ?? [],
    process_steps: props.service.process_steps ?? [],
    documents: props.service.documents ?? [],
    timeline: props.service.timeline ?? '',
    fees: props.service.fees ?? '',
    sort_order: props.service.sort_order,
    is_active: props.service.is_active,
});

const submit = () => {
    // Files cannot be sent with a real PUT request, so post and spoof the method.
    form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/services/${props.service.id}`, { forceFormData: true });
};
</script>

<template>
    <AppLayout>
        <Head :title="t('services.edit.title')" />
        <ServiceForm
            mode="edit"
            :form="form"
            :existing-image-url="service.image_url"
            :view-href="service.is_active ? `/services/${service.slug}` : undefined"
            @submit="submit"
        />
    </AppLayout>
</template>
