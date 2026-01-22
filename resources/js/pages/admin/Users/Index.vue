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

        <!-- Filters -->
        <div class="rounded-lg border border-border bg-card p-4">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="mb-2 block text-sm font-medium">Role</label>
                    <select
                        v-model="roleId"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="applyFilters"
                    >
                        <option value="">All roles</option>
                        <option v-for="r in roleOptions" :key="r.id" :value="String(r.id)">
                            {{ r.name }} ({{ r.slug }})
                        </option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">Verified</label>
                    <select
                        v-model="verified"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="applyFilters"
                    >
                        <option value="">All</option>
                        <option value="1">Verified</option>
                        <option value="0">Unverified</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">Search</label>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search name or email..."
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @input="applySearch"
                    />
                </div>

                <div class="flex items-end">
                    <button
                        type="button"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm font-medium text-foreground transition-colors hover:bg-muted"
                        @click="resetFilters"
                    >
                        Reset filters
                    </button>
                </div>
            </div>
        </div>

        <DataTable :columns="columns" :data="users.data" :actions="actions" :pagination="users" />
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable from '@/components/admin/DataTable.vue';
import { usePermissions } from '@/composables/usePermissions';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    users: any;
    filters: { search?: string; role_id?: string | number | null; verified?: string };
    roleOptions: Array<{ id: number; name: string; slug: string }>;
}>();

const { can } = usePermissions();
const canEdit = computed(() => can('users.update'));
const canCreate = computed(() => can('users.create'));

const search = ref(props.filters?.search || '');
const roleId = ref(props.filters?.role_id ? String(props.filters.role_id) : '');
const verified = ref(props.filters?.verified || '');

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'email_verified_at', label: 'Verified' },
    { key: 'created_at', label: 'Created' },
];

let searchTimer: number | undefined;

const applyFilters = () => {
    router.get(
        '/admin/users',
        {
            search: search.value || undefined,
            role_id: roleId.value || undefined,
            verified: verified.value || undefined,
        },
        { preserveState: true, replace: true },
    );
};

const applySearch = () => {
    if (searchTimer) window.clearTimeout(searchTimer);
    searchTimer = window.setTimeout(() => applyFilters(), 300);
};

const resetFilters = () => {
    search.value = '';
    roleId.value = '';
    verified.value = '';
    applyFilters();
};

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

