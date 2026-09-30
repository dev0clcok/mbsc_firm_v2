<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import TeamMemberForm, { type TeamMemberFormData } from '@/pages/admin/TeamMembers/TeamMemberForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    teamMember: {
        id: number;
        name: string;
        position: string | null;
        specialization: string | null;
        image_url: string | null;
        sort_order: number;
        is_active: boolean;
        social_links?: Array<{ platform: string; url: string }>;
    };
}>();

const form = useForm<TeamMemberFormData>({
    name: props.teamMember.name,
    position: props.teamMember.position ?? '',
    specialization: props.teamMember.specialization ?? '',
    social_links: (props.teamMember.social_links ?? []).map((s) => ({ platform: s.platform, url: s.url })),
    image: null,
    remove_image: false,
    sort_order: props.teamMember.sort_order,
    is_active: props.teamMember.is_active,
});

const submit = () => {
    // Files cannot be sent with a real PUT request, so post and spoof the method.
    form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/team-members/${props.teamMember.id}`, { forceFormData: true });
};
</script>

<template>
    <AppLayout>
        <Head :title="t('team_members.edit.title')" />
        <TeamMemberForm
            mode="edit"
            :form="form"
            :existing-image-url="teamMember.image_url"
            :view-href="teamMember.is_active ? '/about#about-people' : undefined"
            @submit="submit"
        />
    </AppLayout>
</template>
