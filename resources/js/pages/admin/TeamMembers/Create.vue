<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import TeamMemberForm, { type TeamMemberFormData } from '@/pages/admin/TeamMembers/TeamMemberForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm<TeamMemberFormData>({
    name: '',
    position: '',
    specialization: '',
    social_links: [],
    image: null,
    remove_image: false,
    sort_order: 0,
    is_active: true,
});

const submit = () => form.post('/admin/team-members', { forceFormData: true });
</script>

<template>
    <AppLayout>
        <Head :title="t('team_members.create.title')" />
        <TeamMemberForm mode="create" :form="form" @submit="submit" />
    </AppLayout>
</template>
