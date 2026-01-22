<template>
    <AppLayout>
        <Head title="Roles" />

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">Roles</h1>
            <Link
                v-if="canCreate"
                href="/admin/roles/create"
                class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
            >
                Create Role
            </Link>
        </div>

        <!-- Filters -->
        <div class="rounded-lg border border-border bg-card p-4">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="mb-2 block text-sm font-medium">Users</label>
                    <select
                        v-model="hasUsers"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="applyFilters"
                    >
                        <option value="">All</option>
                        <option value="1">With users</option>
                        <option value="0">Without users</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium">Search</label>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search name, slug, description..."
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

        <DataTable :columns="columns" :data="roles.data" :actions="actions" :pagination="roles" />

        <ConfirmDialog
            v-model:open="confirmOpen"
            title="Delete role?"
            description="This action cannot be undone."
            confirm-text="Delete"
            confirm-variant="destructive"
            :loading="deleting"
            loading-text="Deleting..."
            @confirm="confirmDelete"
        />
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable from '@/components/admin/DataTable.vue';
import ConfirmDialog from '@/components/admin/ConfirmDialog.vue';
import { toast } from '@/composables/useToast';
import { usePermissions } from '@/composables/usePermissions';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    roles: any;
    filters: { search?: string; has_users?: string };
}>();

const { can } = usePermissions();

const canCreate = computed(() => can('roles.create'));
const canEdit = computed(() => can('roles.update'));
const canDelete = computed(() => can('roles.delete'));

const search = ref(props.filters?.search || '');
const hasUsers = ref(props.filters?.has_users || '');

const confirmOpen = ref(false);
const pendingDeleteId = ref<number | null>(null);
const deleting = ref(false);

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'slug', label: 'Slug' },
    { key: 'users_count', label: 'Users' },
];

let searchTimer: number | undefined;

const applyFilters = () => {
    router.get(
        '/admin/roles',
        {
            search: search.value || undefined,
            has_users: hasUsers.value || undefined,
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
    hasUsers.value = '';
    applyFilters();
};

const actions = computed(() => {
    const base: any[] = [];

    if (canEdit.value) {
        base.push({
            type: 'button',
            label: 'Edit',
            icon: 'pencil',
            onClick: (row: any) => router.visit(`/admin/roles/${row.id}/edit`),
        });
    }

    if (canDelete.value) {
        base.push({
            type: 'button',
            label: 'Delete',
            icon: 'trash',
            variant: 'destructive',
            onClick: (row: any) => {
                pendingDeleteId.value = row.id;
                confirmOpen.value = true;
            },
        });
    }

    return base;
});

const confirmDelete = () => {
    if (!pendingDeleteId.value) return;
    deleting.value = true;
    router.delete(`/admin/roles/${pendingDeleteId.value}`, {
        onSuccess: () => toast({ variant: 'success', message: 'Role deleted.' }),
        onError: () => toast({ variant: 'error', message: 'Failed to delete role.' }),
        onFinish: () => {
            pendingDeleteId.value = null;
            confirmOpen.value = false;
            deleting.value = false;
        },
    });
};
</script>

