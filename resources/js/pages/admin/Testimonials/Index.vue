<template>
    <AppLayout>
        <Head :title="t('testimonials.title')" />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold tracking-tight">{{ t('testimonials.title') }}</h1>
            <Link
                v-if="canCreate"
                href="/admin/testimonials/create"
                class="inline-flex min-h-10 items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
            >
                {{ t('testimonials.add_new') }}
            </Link>
        </div>

        <AppFilters
            v-model:search="search"
            :search-label="t('common.search')"
            :search-placeholder="t('testimonials.filters.search_placeholder')"
            :reset-text="t('common.reset_filters')"
            @search="handleSearch"
            @reset="resetFilters"
        >
            <template #filters>
                <div>
                    <label class="mb-2 block text-sm font-medium">{{ t('testimonials.filters.status') }}</label>
                    <select
                        v-model="selectedStatus"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="handleFilter"
                    >
                        <option value="">{{ t('testimonials.filters.all_status') }}</option>
                        <option value="1">{{ t('testimonials.status.active') }}</option>
                        <option value="0">{{ t('testimonials.status.inactive') }}</option>
                    </select>
                </div>
            </template>
        </AppFilters>

        <DataTable :columns="columns" :data="testimonials.data" :actions="actions" :pagination="pagination">
            <template #cell-name="{ row }">
                <div class="max-w-xl">
                    <div class="font-semibold text-foreground">{{ row.name }}</div>
                    <div v-if="row.position || row.company" class="mt-1 text-sm text-muted-foreground">
                        {{ [row.position, row.company].filter(Boolean).join(', ') }}
                    </div>
                    <div class="mt-1.5 text-sm leading-relaxed text-muted-foreground line-clamp-2">
                        "{{ row.text }}"
                    </div>
                </div>
            </template>

            <template #cell-is_active="{ row, value }">
                <StatusToggle :active="value" :url="`/admin/testimonials/${row.id}/toggle`" :label="row.name" :disabled="!canEdit" />
            </template>

            <template #cell-sort_order="{ value }">
                <span class="inline-flex items-center justify-center rounded-md bg-muted px-2.5 py-1 text-sm font-mono font-semibold text-muted-foreground">
                    {{ formatNumber(value) }}
                </span>
            </template>

            <template #empty>
                <div class="text-muted-foreground">
                    <p class="text-sm">{{ t('testimonials.empty.no_testimonials') }}</p>
                    <Link v-if="canCreate" href="/admin/testimonials/create" class="mt-2 inline-block text-sm text-primary hover:underline">
                        {{ t('testimonials.empty.create_first') }}
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
import { useLocaleFormat } from '@/composables/useLocaleFormat';
import { usePermissions } from '@/composables/usePermissions';

interface Props {
    testimonials: {
        data: Array<{
            id: number;
            name: string;
            position: string | null;
            company: string | null;
            text: string;
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
const { formatNumber } = useLocaleFormat();
const { confirm } = useConfirm();
const { can } = usePermissions();

const search = ref(props.filters?.search || '');
const selectedStatus = ref(props.filters?.status || '');

const canCreate = computed(() => can('testimonials.create'));
const canEdit = computed(() => can('testimonials.update'));
const canDelete = computed(() => can('testimonials.delete'));

const columns = computed(() => [
    { key: 'name', label: t('testimonials.columns.name') },
    { key: 'is_active', label: t('testimonials.columns.status'), align: 'center' as const },
    { key: 'sort_order', label: t('testimonials.columns.sort'), align: 'center' as const },
]);

const actions = computed(() => {
    const a: any[] = [];
    if (canEdit.value) {
        a.push({
            type: 'link' as const,
            label: t('common.edit'),
            icon: 'pencil',
            href: (row: any) => ({ url: `/admin/testimonials/${row.id}/edit` }),
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
    links: props.testimonials.links,
    from: props.testimonials.from,
    to: props.testimonials.to,
    total: props.testimonials.total,
}));

const confirmDelete = async (row: { id: number; name: string }) => {
    const ok = await confirm({
        title: t('testimonials.delete.title'),
        description: t('testimonials.delete.description'),
        details: row.name,
        confirmText: t('testimonials.delete.confirm'),
        confirmVariant: 'destructive',
    });
    if (!ok) return;
    router.delete(`/admin/testimonials/${row.id}`, { preserveScroll: true });
};

const handleSearch = () => {
    router.get('/admin/testimonials', { search: search.value || null, status: selectedStatus.value || null }, { preserveState: true, replace: true });
};

const handleFilter = () => {
    router.get('/admin/testimonials', { search: search.value || null, status: selectedStatus.value || null }, { preserveState: true, replace: true });
};

const resetFilters = () => {
    search.value = '';
    selectedStatus.value = '';
    router.get('/admin/testimonials', {}, { preserveState: true, replace: true });
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
