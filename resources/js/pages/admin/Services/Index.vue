<template>
    <AppLayout>
        <Head :title="t('services.title')" />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold tracking-tight">{{ t('services.title') }}</h1>
            <Link
                v-if="canCreate"
                href="/admin/services/create"
                class="inline-flex min-h-10 items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
            >
                {{ t('services.add_new') }}
            </Link>
        </div>

        <AppFilters
            v-model:search="search"
            :search-label="t('common.search')"
            :search-placeholder="t('services.filters.search_placeholder')"
            :reset-text="t('common.reset_filters')"
            @search="handleSearch"
            @reset="resetFilters"
        >
            <template #filters>
                <div>
                    <label class="mb-2 block text-sm font-medium">{{ t('services.filters.status') }}</label>
                    <select
                        v-model="selectedStatus"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="handleFilter"
                    >
                        <option value="">{{ t('services.filters.all_status') }}</option>
                        <option value="1">{{ t('services.status.active') }}</option>
                        <option value="0">{{ t('services.status.inactive') }}</option>
                    </select>
                </div>
            </template>
        </AppFilters>

        <p v-if="canEdit" class="text-sm text-muted-foreground">{{ t('datatable.reorder_hint') }}</p>


        <DataTable :key="reorderUrl ?? 'fixed'" :reorder-url="reorderUrl" :columns="columns" :data="services.data" :actions="actions" :pagination="pagination">
            <template #cell-title="{ row }">
                <div class="max-w-xl">
                    <div class="font-semibold text-foreground">
                        {{ row.title }}
                    </div>
                    <div class="mt-1 text-xs font-mono text-muted-foreground">
                        {{ row.slug }}
                    </div>
                    <div v-if="row.short_description" class="mt-1.5 text-sm leading-relaxed text-muted-foreground line-clamp-2">
                        {{ row.short_description }}
                    </div>
                </div>
            </template>

            <template #cell-is_active="{ row, value }">
                <StatusToggle :active="value" :url="`/admin/services/${row.id}/toggle`" :label="row.title" :disabled="!canEdit" />
            </template>

            <template #empty>
                <div class="text-muted-foreground">
                    <p class="text-sm">{{ t('services.empty.no_services') }}</p>
                    <Link v-if="canCreate" href="/admin/services/create" class="mt-2 inline-block text-sm text-primary hover:underline">
                        {{ t('services.empty.create_first') }}
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
import { edit as servicesEdit } from '@/routes/admin/services';

interface Props {
    services: {
        data: Array<{
            id: number;
            slug: string;
            title: string;
            short_description: string | null;
            sort_order: number;
            is_active: boolean;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
        from: number | null;
        to: number | null;
        total: number;
    };
    filters?: {
        search?: string;
        status?: string;
    };
}

const props = defineProps<Props>();
const { t } = useI18n();
const { confirm } = useConfirm();
const { can } = usePermissions();

const search = ref(props.filters?.search || '');
const selectedStatus = ref(props.filters?.status || '');

const canCreate = computed(() => can('services.create'));
const canEdit = computed(() => can('services.update'));

// Ordering only makes sense on the full, unfiltered list.
const reorderUrl = computed(() => (canEdit.value && !search.value && !selectedStatus.value ? '/admin/services/reorder' : undefined));
const canDelete = computed(() => can('services.delete'));

const columns = computed(() => [
    { key: 'title', label: t('services.columns.title') },
    { key: 'is_active', label: t('services.columns.status'), align: 'center' as const },
]);

const actions = computed(() => {
    const a: any[] = [];
    if (canEdit.value) {
        a.push({
            type: 'link' as const,
            label: t('common.edit'),
            icon: 'pencil',
            href: (row: any) => servicesEdit({ service: row.id }),
        });
    }
    if (canDelete.value) {
        a.push({
            type: 'button' as const,
            label: t('common.delete'),
            icon: 'trash2',
            variant: 'destructive' as const,
            onClick: (row: any) => confirmDelete({ id: row.id, title: row.title }),
        });
    }
    return a;
});

const pagination = computed(() => ({
    links: props.services.links,
    from: props.services.from,
    to: props.services.to,
    total: props.services.total,
}));

const confirmDelete = async (row: { id: number; title: string }) => {
    const ok = await confirm({
        title: t('services.delete.title'),
        description: t('services.delete.description'),
        details: row.title,
        confirmText: t('services.delete.confirm'),
        confirmVariant: 'destructive',
    });
    if (!ok) return;

    router.delete(`/admin/services/${row.id}`, { preserveScroll: true });
};

const handleSearch = () => {
    router.get(
        '/admin/services',
        {
            search: search.value || null,
            status: selectedStatus.value || null,
        },
        { preserveState: true, replace: true }
    );
};

const handleFilter = () => {
    router.get(
        '/admin/services',
        {
            search: search.value || null,
            status: selectedStatus.value || null,
        },
        { preserveState: true, replace: true }
    );
};

const resetFilters = () => {
    search.value = '';
    selectedStatus.value = '';
    router.get('/admin/services', {}, { preserveState: true, replace: true });
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

