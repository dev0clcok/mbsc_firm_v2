<template>
    <AppLayout>
        <Head title="Users" />

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">Users</h1>

            <Link
                v-if="canCreate"
                href="/admin/users/create"
                class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
            >
                Create User
            </Link>
        </div>

        <DataTable :columns="columns" :data="users.data" :actions="actions" :pagination="users" />
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable from '@/components/admin/DataTable.vue';
import { usePermissions } from '@/composables/usePermissions';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    users: any;
}>();

const { can } = usePermissions();
const canEdit = computed(() => can('users.update'));
const canCreate = computed(() => can('users.create'));

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'email_verified_at', label: 'Verified' },
    { key: 'created_at', label: 'Created' },
];

const actions = computed(() => {
    if (!canEdit.value) return [];
    return [
        {
            type: 'button',
            label: 'Edit',
            icon: 'pencil',
            onClick: (row: any) => router.visit(`/admin/users/${row.id}/edit`),
        },
    ];
});
</script>

