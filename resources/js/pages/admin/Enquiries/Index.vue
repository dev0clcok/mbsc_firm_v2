<template>
    <AppLayout>
        <Head :title="t('enquiries.title')" />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ t('enquiries.title') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('enquiries.new_count', { count: newCount }) }}</p>
            </div>
        </div>

        <AppFilters
            v-model:search="search"
            :search-label="t('common.search')"
            :search-placeholder="t('enquiries.filters.search_placeholder')"
            :reset-text="t('common.reset_filters')"
            @search="applyFilters"
            @reset="resetFilters"
        >
            <template #filters>
                <div>
                    <label for="enquiry-status-filter" class="mb-2 block text-sm font-medium">{{ t('enquiries.filters.status') }}</label>
                    <select
                        id="enquiry-status-filter"
                        v-model="selectedStatus"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="applyFilters"
                    >
                        <option value="">{{ t('enquiries.filters.all_status') }}</option>
                        <option v-for="status in statuses" :key="status" :value="status">{{ t(`enquiries.status.${status}`) }}</option>
                    </select>
                </div>
            </template>
        </AppFilters>

        <DataTable :columns="columns" :data="enquiries.data" :actions="actions" :pagination="pagination">
            <template #cell-name="{ row }">
                <div class="max-w-xl">
                    <div class="font-semibold text-foreground">{{ row.name }}</div>
                    <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm">
                        <a v-if="row.phone" :href="`tel:${row.phone}`" class="text-primary hover:underline">{{ row.phone }}</a>
                        <a v-if="row.email" :href="`mailto:${row.email}`" class="text-primary hover:underline">{{ row.email }}</a>
                    </div>
                    <div v-if="row.service" class="mt-1.5 text-sm font-medium text-muted-foreground">{{ row.service }}</div>
                    <p class="mt-1.5 whitespace-pre-line text-sm leading-relaxed text-muted-foreground">{{ row.message }}</p>
                </div>
            </template>

            <template #cell-created_at="{ value }">
                <span class="whitespace-nowrap text-sm text-muted-foreground">{{ formatDate(value) }}</span>
            </template>

            <template #cell-status="{ row }">
                <select
                    v-if="canUpdate"
                    :value="row.status"
                    :aria-label="t('enquiries.columns.status')"
                    :class="['rounded-md border px-2.5 py-1.5 text-sm font-medium', statusClass(row.status)]"
                    @change="updateStatus(row.id, ($event.target as HTMLSelectElement).value)"
                >
                    <option v-for="status in statuses" :key="status" :value="status">{{ t(`enquiries.status.${status}`) }}</option>
                </select>
                <span v-else :class="['inline-flex rounded-md border px-2.5 py-1 text-sm font-medium', statusClass(row.status)]">
                    {{ t(`enquiries.status.${row.status}`) }}
                </span>
            </template>

            <template #empty>
                <p class="text-sm text-muted-foreground">{{ t('enquiries.empty') }}</p>
            </template>
        </DataTable>
    </AppLayout>
</template>

<script setup lang="ts">
import AppFilters from '@/components/admin/AppFilters.vue';
import DataTable from '@/components/admin/DataTable.vue';
import { useConfirm } from '@/composables/useConfirm';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

type Status = 'new' | 'contacted' | 'closed';

interface Enquiry {
    id: number;
    name: string;
    phone: string | null;
    email: string | null;
    service: string | null;
    message: string;
    status: Status;
    created_at: string;
}

const props = defineProps<{
    enquiries: {
        data: Enquiry[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
        from: number | null;
        to: number | null;
        total: number;
    };
    statuses: Status[];
    newCount: number;
    filters?: { search?: string; status?: string };
}>();

const { t, locale } = useI18n();
const { confirm } = useConfirm();
const { can } = usePermissions();

const search = ref(props.filters?.search || '');
const selectedStatus = ref(props.filters?.status || '');

const canUpdate = computed(() => can('enquiries.update'));
const canDelete = computed(() => can('enquiries.delete'));

const columns = computed(() => [
    { key: 'name', label: t('enquiries.columns.enquiry') },
    { key: 'created_at', label: t('enquiries.columns.received') },
    { key: 'status', label: t('enquiries.columns.status'), align: 'center' as const },
]);

const actions = computed(() => {
    const a: any[] = [];
    if (canDelete.value) {
        a.push({
            type: 'button' as const,
            label: t('common.delete'),
            icon: 'trash2',
            variant: 'destructive' as const,
            onClick: (row: Enquiry) => confirmDelete(row),
        });
    }
    return a;
});

const pagination = computed(() => ({
    links: props.enquiries.links,
    from: props.enquiries.from,
    to: props.enquiries.to,
    total: props.enquiries.total,
}));

const statusClass = (status: Status) =>
    ({
        new: 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-400/30 dark:bg-amber-900/20 dark:text-amber-300',
        contacted: 'border-blue-300 bg-blue-50 text-blue-800 dark:border-blue-400/30 dark:bg-blue-900/20 dark:text-blue-300',
        closed: 'border-border bg-muted text-muted-foreground',
    })[status];

const formatDate = (value: string) =>
    new Date(value).toLocaleString(locale.value === 'bn' ? 'bn-BD' : 'en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });

const updateStatus = (id: number, status: string) => {
    router.put(`/admin/enquiries/${id}`, { status }, { preserveScroll: true, preserveState: true });
};

const confirmDelete = async (row: Enquiry) => {
    const ok = await confirm({
        title: t('enquiries.delete.title'),
        description: t('enquiries.delete.description'),
        details: row.name,
        confirmText: t('enquiries.delete.confirm'),
        confirmVariant: 'destructive',
    });
    if (!ok) return;
    router.delete(`/admin/enquiries/${row.id}`, { preserveScroll: true });
};

const applyFilters = () => {
    router.get(
        '/admin/enquiries',
        { search: search.value || null, status: selectedStatus.value || null },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    search.value = '';
    selectedStatus.value = '';
    router.get('/admin/enquiries', {}, { preserveState: true, replace: true });
};
</script>
