<template>
    <AppLayout>
        <Head :title="t('users.title')" />

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">{{ t('users.title') }}</h1>

            <Link
                v-if="canCreate"
                href="/admin/users/create"
                class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
            >
                {{ t('users.create_user') }}
            </Link>
        </div>

        <!-- Filters -->
        <div class="rounded-lg border border-border bg-card p-4">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="mb-2 block text-sm font-medium">{{ t('users.filters.role') }}</label>
                    <select
                        v-model="roleId"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="applyFilters"
                    >
                        <option value="">{{ t('common.all') }}</option>
                        <option v-for="r in roleOptions" :key="r.id" :value="String(r.id)">
                            {{ r.name }} ({{ r.slug }})
                        </option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">{{ t('users.filters.verified') }}</label>
                    <select
                        v-model="verified"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="applyFilters"
                    >
                        <option value="">{{ t('users.verified.all') }}</option>
                        <option value="1">{{ t('users.verified.verified') }}</option>
                        <option value="0">{{ t('users.verified.unverified') }}</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">{{ t('common.search') }}</label>
                    <input
                        v-model="search"
                        type="text"
                        :placeholder="t('common.search')"
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
                        {{ t('common.reset_filters') }}
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
import { useConfirm } from '@/composables/useConfirm';
import { toast } from '@/composables/useToast';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    users: any;
    filters: { search?: string; role_id?: string | number | null; verified?: string };
    roleOptions: Array<{ id: number; name: string; slug: string }>;
}>();

const { t } = useI18n();

const { can } = usePermissions();
const canEdit = computed(() => can('users.update'));
const canCreate = computed(() => can('users.create'));
const canDelete = computed(() => can('users.delete'));

const { confirm } = useConfirm();

const search = ref(props.filters?.search || '');
const roleId = ref(props.filters?.role_id ? String(props.filters.role_id) : '');
const verified = ref(props.filters?.verified || '');

const columns = computed(() => [
    { key: 'name', label: t('users.columns.name') },
    { key: 'email', label: t('users.columns.email') },
    { key: 'email_verified_at', label: t('users.columns.verified') },
    { key: 'created_at', label: t('users.columns.created') },
]);

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
    const a: any[] = [];

    if (canEdit.value) {
        a.push({
            type: 'button',
            label: t('common.edit'),
            icon: 'pencil',
            onClick: (row: any) => router.visit(`/admin/users/${row.id}/edit`),
        });
    }

    if (canDelete.value) {
        a.push({
            type: 'button',
            label: t('common.delete'),
            icon: 'trash',
            variant: 'destructive',
            onClick: async (row: any) => {
                const ok = await confirm({
                    title: t('users.delete.title'),
                    description: t('users.delete.description'),
                    details: row.email ? `${row.name} <${row.email}>` : row.name,
                    confirmText: t('users.delete.confirm'),
                    confirmVariant: 'destructive',
                });
                if (!ok) return;

                router.delete(`/admin/users/${row.id}`, {
                    preserveScroll: true,
                    onSuccess: () => toast({ variant: 'success', message: 'User deleted.' }),
                    onError: () => toast({ variant: 'error', message: 'Failed to delete user.' }),
                });
            },
        });
    }

    return a;
});
</script>

