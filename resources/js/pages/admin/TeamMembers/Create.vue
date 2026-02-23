<template>
    <AppLayout>
        <Head :title="t('team_members.create.title')" />

        <TeamMemberForm
            mode="create"
            v-model:name="form.name"
            v-model:position="form.position"
            v-model:specialization="form.specialization"
            v-model:socialLinks="form.social_links"
            v-model:image="form.image"
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
import TeamMemberForm from '@/pages/admin/TeamMembers/TeamMemberForm.vue';

const { t } = useI18n();

const form = useForm({
    name: '',
    position: '',
    specialization: '',
    social_links: [] as Array<{ platform: string; url: string }>,
    image: null as File | null,
    sort_order: 0,
    is_active: true,
});

const submit = () => {
    form.post('/admin/team-members');
};
</script>
