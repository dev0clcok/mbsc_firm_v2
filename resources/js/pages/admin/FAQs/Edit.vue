<template>
    <AppLayout>

        <Head title="Edit FAQ" />

        <FaqForm
            mode="edit"
            v-model:question="form.question"
            v-model:answer="form.answer"
            v-model:sortOrder="form.sort_order"
            v-model:isActive="form.is_active"
            :processing="form.processing"
            @submit="submit"
        />
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import FaqForm from '@/pages/admin/FAQs/FaqForm.vue';
import { update as faqsUpdate } from '@/routes/admin/faqs';

interface Props {
    faq: {
        id: number;
        question: string;
        answer: string;
        sort_order: number;
        is_active: boolean;
    };
}

const props = defineProps<Props>();

const form = useForm({
    question: props.faq.question,
    answer: props.faq.answer,
    sort_order: props.faq.sort_order,
    is_active: props.faq.is_active,
});

const submit = () => {
    form.put(faqsUpdate({ faq: props.faq.id }).url);
};
</script>
