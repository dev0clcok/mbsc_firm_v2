<template>
    <AppLayout>
        <Head :title="t('team_members.edit.title')" />

        <TeamMemberForm
            mode="edit"
            v-model:name="form.name"
            v-model:position="form.position"
            v-model:specialization="form.specialization"
            v-model:socialLinks="form.social_links"
            v-model:image="form.image"
            v-model:removeImage="form.remove_image"
            v-model:sortOrder="form.sort_order"
            v-model:isActive="form.is_active"
            :processing="form.processing"
            :existingImageUrl="props.teamMember.image_url"
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

interface Props {
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
}

const props = defineProps<Props>();

const form = useForm({
    name: props.teamMember.name,
    position: props.teamMember.position || '',
    specialization: props.teamMember.specialization || '',
    social_links: (props.teamMember.social_links ?? []).map((s) => ({ platform: s.platform, url: s.url })),
    image: null as File | null,
    remove_image: false,
    sort_order: props.teamMember.sort_order,
    is_active: props.teamMember.is_active,
});

const submit = () => {
    form.put(`/admin/team-members/${props.teamMember.id}`);
};
</script>
