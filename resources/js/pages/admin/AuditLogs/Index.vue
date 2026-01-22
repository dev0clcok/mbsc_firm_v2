<template>
    <AppLayout>
        <Head :title="t('audit.title')" />

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">{{ t('audit.title') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ t('audit.subtitle') }}
                </p>
            </div>
        </div>

        <!-- Filters -->
        <AppFilters
            v-model:search="search"
            :search-label="t('common.search')"
            :search-placeholder="t('common.search')"
            :reset-text="t('common.reset_filters')"
            @search="applySearch"
            @reset="reset"
        >
            <template #filters>
                <div>
                    <label class="mb-2 block text-sm font-medium">{{ t('audit.filters.event') }}</label>
                    <select
                        v-model="event"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="applyFilters"
                    >
                        <option value="">{{ t('audit.filters.all_events') }}</option>
                        <option v-for="e in eventOptions" :key="e" :value="e">
                            {{ e }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">{{ t('audit.filters.method') }}</label>
                    <select
                        v-model="method"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="applyFilters"
                    >
                        <option value="">{{ t('audit.filters.all_methods') }}</option>
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="PATCH">PATCH</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                </div>
            </template>
        </AppFilters>

        <DataTable
            :columns="columns"
            :data="auditLogs.data"
            :pagination="auditLogs"
            :row-key="(row: any) => `${row.id}`"
        >
            <template #cell-created_at="{ value }">
                <span class="whitespace-nowrap text-sm text-muted-foreground">
                    {{ formatDate(value) }}
                </span>
            </template>

            <template #cell-user="{ row }">
                <div class="min-w-0">
                    <div class="truncate font-medium text-foreground">
                        {{ row.user?.name || '—' }}
                    </div>
                    <div class="truncate text-xs text-muted-foreground">
                        {{ row.user?.email || (row.user_id ? `User #${row.user_id}` : 'Guest') }}
                    </div>
                </div>
            </template>

            <template #cell-event="{ value }">
                <span
                    class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20"
                >
                    {{ value }}
                </span>
            </template>

            <template #cell-method="{ value }">
                <span
                    v-if="value"
                    :class="[
                        'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset',
                        value === 'GET' && 'bg-sky-50 text-sky-700 ring-sky-600/20 dark:bg-sky-900/20 dark:text-sky-300 dark:ring-sky-400/30',
                        value === 'POST' && 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/20 dark:text-emerald-300 dark:ring-emerald-400/30',
                        (value === 'PUT' || value === 'PATCH') &&
                            'bg-amber-50 text-amber-800 ring-amber-600/20 dark:bg-amber-900/20 dark:text-amber-300 dark:ring-amber-400/30',
                        value === 'DELETE' &&
                            'bg-rose-50 text-rose-700 ring-rose-600/20 dark:bg-rose-900/20 dark:text-rose-300 dark:ring-rose-400/30',
                    ]"
                >
                    {{ value }}
                </span>
                <span v-else class="text-sm text-muted-foreground">—</span>
            </template>

            <template #cell-model="{ row }">
                <div class="min-w-0">
                    <div class="truncate text-sm font-medium text-foreground">
                        {{ row.model_type || '—' }}
                    </div>
                    <div class="truncate text-xs text-muted-foreground">
                        {{ row.model_id ? `#${row.model_id}` : '—' }}
                    </div>
                </div>
            </template>

            <template #cell-status_code="{ value }">
                <span
                    v-if="value"
                    :class="[
                        'inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold',
                        value >= 200 && value < 300 && 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300',
                        value >= 300 && value < 400 && 'bg-sky-50 text-sky-700 dark:bg-sky-900/20 dark:text-sky-300',
                        value >= 400 && value < 500 && 'bg-amber-50 text-amber-800 dark:bg-amber-900/20 dark:text-amber-300',
                        value >= 500 && 'bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300',
                    ]"
                >
                    {{ value }}
                </span>
                <span v-else class="text-sm text-muted-foreground">—</span>
            </template>

            <template #cell-route="{ row }">
                <div class="min-w-0">
                    <div class="truncate font-medium text-foreground">
                        {{ row.route || '—' }}
                    </div>
                    <div class="truncate text-xs text-muted-foreground">
                        {{ row.url || '—' }}
                    </div>
                </div>
            </template>

            <template #cell-ip="{ value }">
                <span class="font-mono text-xs text-muted-foreground">
                    {{ value || '—' }}
                </span>
            </template>
        </DataTable>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable from '@/components/admin/DataTable.vue';
import AppFilters from '@/components/admin/AppFilters.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    auditLogs: any;
    filters: { search?: string; event?: string; method?: string };
    eventOptions: string[];
}>();

const { t } = useI18n();

const search = ref(props.filters?.search || '');
const event = ref(props.filters?.event || '');
const method = ref(props.filters?.method || '');

const columns = computed(() => [
    { key: 'created_at', label: t('audit.columns.time') },
    { key: 'user', label: t('audit.columns.user') },
    { key: 'event', label: t('audit.columns.event') },
    { key: 'method', label: t('audit.columns.method'), align: 'center' },
    { key: 'route', label: t('audit.columns.route_url') },
    { key: 'model', label: t('audit.columns.model') },
    { key: 'status_code', label: t('audit.columns.status'), align: 'center' },
    { key: 'ip', label: t('audit.columns.ip'), align: 'right' },
]);

let searchTimer: number | undefined;

const applyFilters = () => {
    router.get(
        '/admin/audit-logs',
        {
            search: search.value || undefined,
            event: event.value || undefined,
            method: method.value || undefined,
        },
        { preserveState: true, replace: true },
    );
};

const applySearch = () => {
    if (searchTimer) window.clearTimeout(searchTimer);
    searchTimer = window.setTimeout(() => applyFilters(), 300);
};

const reset = () => {
    search.value = '';
    event.value = '';
    method.value = '';
    applyFilters();
};

const formatDate = (value: string | null) => {
    if (!value) return '—';
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return value;
    return d.toLocaleString();
};
</script>

