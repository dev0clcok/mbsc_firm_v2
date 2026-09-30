<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import FaqForm, { type FaqFormData } from '@/pages/admin/FAQs/FaqForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    faq: { id: number; service_id: number | null; question: string; answer: string; sort_order: number; is_active: boolean };
    services: Array<{ id: number; title: string }>;
}>();

const { t } = useI18n();

const form = useForm<FaqFormData>({
    service_id: props.faq.service_id,
    question: props.faq.question,
    answer: props.faq.answer,
    sort_order: props.faq.sort_order,
    is_active: props.faq.is_active,
});

const submit = () => form.put(`/admin/faqs/${props.faq.id}`);
</script>

<template>
    <AppLayout>
        <Head :title="t('faqs.edit.heading')" />
        <FaqForm
            mode="edit"
            :form="form"
            :services="services"
            :view-href="faq.is_active ? `/faqs#faq-${faq.id}` : undefined"
            @submit="submit"
        />
    </AppLayout>
</template>
