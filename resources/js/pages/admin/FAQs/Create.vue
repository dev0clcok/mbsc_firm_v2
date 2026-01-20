<template>
    <AppLayout>

        <Head title="Create FAQ" />

        <FaqForm
            mode="create"
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
import { store as faqsStore } from '@/routes/admin/faqs';

const form = useForm({
    question: '',
    answer: '',
    sort_order: 0,
    is_active: true,
});

const submit = () => {
    form.post(faqsStore().url);
};
</script>
