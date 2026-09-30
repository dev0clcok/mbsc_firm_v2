<template>
    <AppLayout>
        <Head :title="t('team_members.title')" />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold tracking-tight">{{ t('team_members.title') }}</h1>
            <Link
                v-if="canCreate"
                href="/admin/team-members/create"
                class="inline-flex min-h-10 items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
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

        <p v-if="canEdit" class="text-sm text-muted-foreground">{{ t('datatable.reorder_hint') }}</p>


        <DataTable :key="reorderUrl ?? 'fixed'" :reorder-url="reorderUrl" :columns="columns" :data="teamMembers.data" :actions="actions" :pagination="pagination">
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

            <template #cell-is_active="{ row, value }">
                <StatusToggle :active="value" :url="`/admin/team-members/${row.id}/toggle`" :label="row.name" :disabled="!canEdit" />
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
import StatusToggle from '@/components/admin/StatusToggle.vue';
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

// Ordering only makes sense on the full, unfiltered list.
const reorderUrl = computed(() => (canEdit.value && !search.value && !selectedStatus.value ? '/admin/team-members/reorder' : undefined));
const canDelete = computed(() => can('team_members.delete'));

const columns = computed(() => [
    { key: 'name', label: t('team_members.columns.name') },
    { key: 'is_active', label: t('team_members.columns.status'), align: 'center' as const },
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
