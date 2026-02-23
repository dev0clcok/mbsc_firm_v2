<template>
    <AppLayout>
        <Head :title="t('testimonials.title')" />

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">{{ t('testimonials.title') }}</h1>
            <Link
                v-if="canCreate"
                href="/admin/testimonials/create"
                class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
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

            <template #cell-rating="{ value }">
                <span class="inline-flex items-center gap-0.5 text-amber-500">
                    <span v-for="i in (value || 5)" :key="i">★</span>
                </span>
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
                    {{ value ? t('testimonials.status.active') : t('testimonials.status.inactive') }}
                </span>
            </template>

            <template #cell-sort_order="{ value }">
                <span class="inline-flex items-center justify-center rounded-md bg-muted px-2.5 py-1 text-sm font-mono font-semibold text-muted-foreground">
                    {{ value }}
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
import AppFilters from '@/components/admin/AppFilters.vue';
import { useConfirm } from '@/composables/useConfirm';
import { usePermissions } from '@/composables/usePermissions';

interface Props {
    testimonials: {
        data: Array<{
            id: number;
            name: string;
            position: string | null;
            company: string | null;
            text: string;
            rating: number;
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

const canCreate = computed(() => can('testimonials.create'));
const canEdit = computed(() => can('testimonials.update'));
const canDelete = computed(() => can('testimonials.delete'));

const columns = computed(() => [
    { key: 'name', label: t('testimonials.columns.name') },
    { key: 'rating', label: t('testimonials.columns.rating'), align: 'center' as const },
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
