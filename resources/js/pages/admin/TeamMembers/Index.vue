<template>
    <AppLayout>
        <Head :title="t('team_members.title')" />

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">{{ t('team_members.title') }}</h1>
            <Link
                v-if="canCreate"
                href="/admin/team-members/create"
                class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
            >
                {{ t('team_members.add_new') }}
            </Link>
        </div>

        <AppFilters
            v-model:search="search"
            :search-label="t('common.search')"
            :search-placeholder="t('team_members.filters.search_placeholder')"
            :reset-text="t('common.reset_filters')"
            @search="handleSearch"
            @reset="resetFilters"
        >
            <template #filters>
                <div>
                    <label class="mb-2 block text-sm font-medium">{{ t('team_members.filters.status') }}</label>
                    <select
                        v-model="selectedStatus"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="handleFilter"
                    >
                        <option value="">{{ t('team_members.filters.all_status') }}</option>
                        <option value="1">{{ t('team_members.status.active') }}</option>
                        <option value="0">{{ t('team_members.status.inactive') }}</option>
                    </select>
                </div>
            </template>
        </AppFilters>

        <DataTable :columns="columns" :data="teamMembers.data" :actions="actions" :pagination="pagination">
            <template #cell-name="{ row }">
                <div class="flex items-center gap-4">
                    <img
                        v-if="row.image_url"
                        :src="row.image_url"
                        :alt="row.name"
                        class="h-12 w-12 rounded-full object-cover"
                    />
                    <div v-else class="flex h-12 w-12 items-center justify-center rounded-full bg-muted text-muted-foreground">
                        <span class="text-lg font-semibold">{{ row.name.charAt(0) }}</span>
                    </div>
                    <div>
                        <div class="font-semibold text-foreground">{{ row.name }}</div>
                        <div v-if="row.position" class="text-sm text-muted-foreground">{{ row.position }}</div>
                        <div v-if="row.specialization" class="text-xs text-muted-foreground">{{ row.specialization }}</div>
                    </div>
                </div>
            </template>

            <template #cell-is_active="{ value }">
                <span
                    :class="[
                        'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold shadow-sm transition-all',
                        value
                            ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-900/20 dark:text-emerald-400 dark:ring-emerald-400/30'
                            : 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/20 dark:bg-gray-800/50 dark:text-gray-400 dark:ring-gray-400/20',
                    ]"
                >
                    <span :class="['h-2 w-2 rounded-full', value ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400']" />
                    {{ value ? t('team_members.status.active') : t('team_members.status.inactive') }}
                </span>
            </template>

            <template #cell-sort_order="{ value }">
                <span class="inline-flex items-center justify-center rounded-md bg-muted px-2.5 py-1 text-sm font-mono font-semibold text-muted-foreground">
                    {{ value }}
                </span>
            </template>

            <template #empty>
                <div class="text-muted-foreground">
                    <p class="text-sm">{{ t('team_members.empty.no_team_members') }}</p>
                    <Link v-if="canCreate" href="/admin/team-members/create" class="mt-2 inline-block text-sm text-primary hover:underline">
                        {{ t('team_members.empty.create_first') }}
                    </Link>
                </div>
            </template>
        </DataTable>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable from '@/components/admin/DataTable.vue';
import AppFilters from '@/components/admin/AppFilters.vue';
import { useConfirm } from '@/composables/useConfirm';
import { usePermissions } from '@/composables/usePermissions';

interface Props {
    teamMembers: {
        data: Array<{
            id: number;
            name: string;
            position: string | null;
            specialization: string | null;
            image_url: string | null;
            sort_order: number;
            is_active: boolean;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
        from: number | null;
        to: number | null;
        total: number;
    };
    filters?: { search?: string; status?: string };
}

const props = defineProps<Props>();
const { t } = useI18n();
const { confirm } = useConfirm();
const { can } = usePermissions();

const search = ref(props.filters?.search || '');
const selectedStatus = ref(props.filters?.status || '');

const canCreate = computed(() => can('team_members.create'));
const canEdit = computed(() => can('team_members.update'));
const canDelete = computed(() => can('team_members.delete'));

const columns = computed(() => [
    { key: 'name', label: t('team_members.columns.name') },
    { key: 'is_active', label: t('team_members.columns.status'), align: 'center' as const },
    { key: 'sort_order', label: t('team_members.columns.sort'), align: 'center' as const },
]);

const actions = computed(() => {
    const a: any[] = [];
    if (canEdit.value) {
        a.push({
            type: 'link' as const,
            label: t('common.edit'),
            icon: 'pencil',
            href: (row: any) => ({ url: `/admin/team-members/${row.id}/edit` }),
        });
    }
    if (canDelete.value) {
        a.push({
            type: 'button' as const,
            label: t('common.delete'),
            icon: 'trash2',
            variant: 'destructive' as const,
            onClick: (row: any) => confirmDelete({ id: row.id, name: row.name }),
        });
    }
    return a;
});

const pagination = computed(() => ({
    links: props.teamMembers.links,
    from: props.teamMembers.from,
    to: props.teamMembers.to,
    total: props.teamMembers.total,
}));

const confirmDelete = async (row: { id: number; name: string }) => {
    const ok = await confirm({
        title: t('team_members.delete.title'),
        description: t('team_members.delete.description'),
        details: row.name,
        confirmText: t('team_members.delete.confirm'),
        confirmVariant: 'destructive',
    });
    if (!ok) return;
    router.delete(`/admin/team-members/${row.id}`, { preserveScroll: true });
};

const handleSearch = () => {
    router.get('/admin/team-members', { search: search.value || null, status: selectedStatus.value || null }, { preserveState: true, replace: true });
};

const handleFilter = () => {
    router.get('/admin/team-members', { search: search.value || null, status: selectedStatus.value || null }, { preserveState: true, replace: true });
};

const resetFilters = () => {
    search.value = '';
    selectedStatus.value = '';
    router.get('/admin/team-members', {}, { preserveState: true, replace: true });
};
</script>
