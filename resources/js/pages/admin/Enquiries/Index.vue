<script setup lang="ts">
import DataTable from '@/components/admin/DataTable.vue';
import { useConfirm } from '@/composables/useConfirm';
import { useLocaleFormat } from '@/composables/useLocaleFormat';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Download, MessageSquareText } from 'lucide-vue-next';
import { computed, reactive } from 'vue';
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
    notes_count: number;
    assignee: { id: number; name: string } | null;
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
    counts: Record<'all' | Status, number>;
    filters: { search?: string; status?: string; from?: string; to?: string; sort?: string };
}>();

const { t, locale } = useI18n();
const { formatNumber } = useLocaleFormat();
const { confirm } = useConfirm();
const { can } = usePermissions();

const filters = reactive({
    search: props.filters.search ?? '',
    status: props.filters.status ?? '',
    from: props.filters.from ?? '',
    to: props.filters.to ?? '',
    sort: props.filters.sort ?? 'newest',
});

const canUpdate = computed(() => can('enquiries.update'));
const canDelete = computed(() => can('enquiries.delete'));

const tabs = computed(() => [{ key: '', label: t('enquiries.tabs.all'), count: props.counts.all }, ...props.statuses.map((s) => ({ key: s, label: t(`enquiries.status.${s}`), count: props.counts[s] }))]);

const params = () => ({
    search: filters.search || undefined,
    status: filters.status || undefined,
    from: filters.from || undefined,
    to: filters.to || undefined,
    sort: filters.sort === 'newest' ? undefined : filters.sort,
});

const apply = () => router.get('/admin/enquiries', params(), { preserveState: true, preserveScroll: true, replace: true });

const hasFilters = computed(() => Boolean(filters.search || filters.from || filters.to || filters.sort !== 'newest'));

const reset = () => {
    Object.assign(filters, { search: '', from: '', to: '', sort: 'newest' });
    apply();
};

const exportHref = computed(() => {
    const query = new URLSearchParams(Object.entries(params()).filter(([, v]) => v !== undefined) as string[][]).toString();
    return `/admin/enquiries/export${query ? `?${query}` : ''}`;
});

const columns = computed(() => [
    { key: 'name', label: t('enquiries.columns.enquiry') },
    { key: 'created_at', label: t('enquiries.columns.received') },
    { key: 'assignee', label: t('enquiries.columns.assigned') },
    { key: 'status', label: t('enquiries.columns.status'), align: 'center' as const },
]);

const actions = computed(() => {
    const a: any[] = [{ type: 'link' as const, label: t('enquiries.open'), icon: 'eye', href: (row: Enquiry) => ({ url: `/admin/enquiries/${row.id}` }) }];
    if (canDelete.value) {
        a.push({ type: 'button' as const, label: t('common.delete'), icon: 'trash2', variant: 'destructive' as const, onClick: (row: Enquiry) => confirmDelete(row) });
    }
    return a;
});

const pagination = computed(() => ({ links: props.enquiries.links, from: props.enquiries.from, to: props.enquiries.to, total: props.enquiries.total }));

const statusClass = (status: Status) =>
    ({
        new: 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-400/30 dark:bg-amber-900/20 dark:text-amber-300',
        contacted: 'border-blue-300 bg-blue-50 text-blue-800 dark:border-blue-400/30 dark:bg-blue-900/20 dark:text-blue-300',
        closed: 'border-border bg-muted text-muted-foreground',
    })[status];

const formatDate = (value: string) =>
    new Date(value).toLocaleString(locale.value === 'bn' ? 'bn-BD' : 'en-GB', { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: '2-digit' });

const updateStatus = (id: number, status: string) => router.put(`/admin/enquiries/${id}`, { status }, { preserveScroll: true, preserveState: true });

const confirmDelete = async (row: Enquiry) => {
    const ok = await confirm({
        title: t('enquiries.delete.title'),
        description: t('enquiries.delete.description'),
        details: row.name,
        confirmText: t('enquiries.delete.confirm'),
        confirmVariant: 'destructive',
    });
    if (ok) router.delete(`/admin/enquiries/${row.id}`, { preserveScroll: true });
};
</script>

<template>
    <AppLayout>
        <Head :title="t('enquiries.title')" />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ t('enquiries.title') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('enquiries.subtitle') }}</p>
            </div>
            <a :href="exportHref" class="inline-flex min-h-10 items-center gap-2 rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-muted">
                <Download class="size-4" />
                {{ t('enquiries.export') }}
            </a>
        </div>

        <div class="flex gap-1 overflow-x-auto border-b border-border" role="tablist" :aria-label="t('enquiries.filters.status')">
            <button
                v-for="tab in tabs"
                :key="tab.key"
                type="button"
                role="tab"
                :aria-selected="filters.status === tab.key"
                :class="[
                    '-mb-px inline-flex min-h-11 shrink-0 items-center gap-2 border-b-2 px-3 text-sm font-medium transition-colors',
                    filters.status === tab.key ? 'border-primary text-foreground' : 'border-transparent text-muted-foreground hover:text-foreground',
                ]"
                @click="((filters.status = tab.key), apply())"
            >
                {{ tab.label }}
                <span class="rounded-full bg-muted px-2 py-0.5 text-xs font-semibold tabular-nums">{{ formatNumber(tab.count) }}</span>
            </button>
        </div>

        <form class="grid gap-4 rounded-lg border border-border bg-card p-4 sm:grid-cols-2 lg:grid-cols-[minmax(0,2fr)_repeat(3,minmax(0,1fr))_auto]" @submit.prevent="apply">
            <div>
                <label for="enquiry-search" class="mb-1.5 block text-sm font-medium">{{ t('common.search') }}</label>
                <input id="enquiry-search" v-model="filters.search" type="search" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" :placeholder="t('enquiries.filters.search_placeholder')" />
            </div>
            <div>
                <label for="enquiry-from" class="mb-1.5 block text-sm font-medium">{{ t('enquiries.filters.from') }}</label>
                <input id="enquiry-from" v-model="filters.from" type="date" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" @change="apply" />
            </div>
            <div>
                <label for="enquiry-to" class="mb-1.5 block text-sm font-medium">{{ t('enquiries.filters.to') }}</label>
                <input id="enquiry-to" v-model="filters.to" type="date" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" @change="apply" />
            </div>
            <div>
                <label for="enquiry-sort" class="mb-1.5 block text-sm font-medium">{{ t('enquiries.filters.sort') }}</label>
                <select id="enquiry-sort" v-model="filters.sort" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" @change="apply">
                    <option value="newest">{{ t('enquiries.filters.newest') }}</option>
                    <option value="oldest">{{ t('enquiries.filters.oldest') }}</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="inline-flex min-h-10 items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">{{ t('common.search') }}</button>
                <button v-if="hasFilters" type="button" class="inline-flex min-h-10 items-center rounded-md border border-input px-3 py-2 text-sm font-medium hover:bg-muted" @click="reset">{{ t('common.reset_filters') }}</button>
            </div>
        </form>

        <DataTable :columns="columns" :data="enquiries.data" :actions="actions" :pagination="pagination">
            <template #cell-name="{ row }">
                <Link :href="`/admin/enquiries/${row.id}`" class="block max-w-xl rounded-sm hover:underline">
                    <span class="font-semibold text-foreground">{{ row.name }}</span>
                    <span v-if="row.service" class="block text-sm font-medium text-muted-foreground">{{ row.service }}</span>
                    <span class="mt-1 line-clamp-2 block text-sm leading-relaxed text-muted-foreground">{{ row.message }}</span>
                </Link>
                <span v-if="row.notes_count" class="mt-1.5 inline-flex items-center gap-1 text-xs text-muted-foreground">
                    <MessageSquareText class="size-3.5" />
                    {{ t('enquiries.notes_count', { count: formatNumber(row.notes_count) }, row.notes_count) }}
                </span>
            </template>

            <template #cell-created_at="{ value }">
                <span class="text-sm whitespace-nowrap text-muted-foreground">{{ formatDate(value) }}</span>
            </template>

            <template #cell-assignee="{ row }">
                <span class="text-sm">{{ row.assignee?.name ?? t('enquiries.unassigned') }}</span>
            </template>

            <template #cell-status="{ row }">
                <select
                    v-if="canUpdate"
                    :value="row.status"
                    :aria-label="`${t('enquiries.columns.status')}: ${row.name}`"
                    :class="['min-h-9 rounded-md border px-2.5 py-1.5 text-sm font-medium', statusClass(row.status)]"
                    @change="updateStatus(row.id, ($event.target as HTMLSelectElement).value)"
                >
                    <option v-for="status in statuses" :key="status" :value="status">{{ t(`enquiries.status.${status}`) }}</option>
                </select>
                <span v-else :class="['inline-flex rounded-md border px-2.5 py-1 text-sm font-medium', statusClass(row.status)]">
                    {{ t(`enquiries.status.${row.status}`) }}
                </span>
            </template>

            <template #empty>
                <div class="text-muted-foreground">
                    <p class="text-sm font-medium">{{ counts.all || hasFilters ? t('enquiries.empty_filtered') : t('enquiries.empty') }}</p>
                    <button v-if="hasFilters || filters.status" type="button" class="mt-2 text-sm font-medium text-primary hover:underline" @click="((filters.status = ''), reset())">
                        {{ t('common.reset_filters') }}
                    </button>
                </div>
            </template>
        </DataTable>
    </AppLayout>
</template>
