<template>
    <AppLayout>
        <Head :title="t('services.title')" />

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">{{ t('services.title') }}</h1>
            <Link
                v-if="canCreate"
                href="/admin/services/create"
                class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
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

        <DataTable :columns="columns" :data="services.data" :actions="actions" :pagination="pagination">
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
                    {{ value ? t('services.status.active') : t('services.status.inactive') }}
                </span>
            </template>

            <template #cell-sort_order="{ value }">
                <span class="inline-flex items-center justify-center rounded-md bg-muted px-2.5 py-1 text-sm font-mono font-semibold text-muted-foreground">
                    {{ value }}
                </span>
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
const canDelete = computed(() => can('services.delete'));

const columns = computed(() => [
    { key: 'title', label: t('services.columns.title') },
    { key: 'is_active', label: t('services.columns.status'), align: 'center' as const },
    { key: 'sort_order', label: t('services.columns.sort'), align: 'center' as const },
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

