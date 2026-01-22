<template>
    <AppLayout>

        <Head :title="t('faqs.title')" />
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">{{ t('faqs.title') }}</h1>
            <Link
                v-if="canCreate"
                :href="faqsCreate().url"
                class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90">
                {{ t('faqs.add_new') }}
            </Link>
        </div>

        <!-- Filters -->
        <AppFilters
            v-model:search="search"
            :search-label="t('common.search')"
            :search-placeholder="t('faqs.filters.search_placeholder')"
            :reset-text="t('common.reset_filters')"
            @search="handleSearch"
            @reset="resetFilters"
        >
            <template #filters>
                <div>
                    <label class="mb-2 block text-sm font-medium">{{ t('faqs.filters.status') }}</label>
                    <select
                        v-model="selectedStatus"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="handleFilter"
                    >
                        <option value="">{{ t('faqs.filters.all_status') }}</option>
                        <option value="1">{{ t('faqs.status.active') }}</option>
                        <option value="0">{{ t('faqs.status.inactive') }}</option>
                    </select>
                </div>
            </template>
        </AppFilters>

        <!-- DataTable -->
        <DataTable :columns="columns" :data="faqs.data" :actions="actions" :pagination="pagination">
            <template #cell-question="{ row }">
                <div class="max-w-lg">
                    <div class="font-semibold text-foreground">
                        {{ row.question }}
                    </div>
                    <div class="mt-1.5 text-sm leading-relaxed text-muted-foreground line-clamp-2"
                        v-html="truncateHtml(row.answer, 80)" />
                </div>
            </template>

            <template #cell-category="{ value }">
                <span v-if="value"
                    class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ value }}
                </span>
                <span v-else class="text-sm font-medium text-muted-foreground">—</span>
            </template>

            <template #cell-is_active="{ value }">
                <span :class="[
                    'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold shadow-sm transition-all',
                    value
                        ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-900/20 dark:text-emerald-400 dark:ring-emerald-400/30'
                        : 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/20 dark:bg-gray-800/50 dark:text-gray-400 dark:ring-gray-400/20',
                ]">
                    <span :class="[
                        'h-2 w-2 rounded-full',
                        value ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400',
                    ]" />
                    {{ value ? t('faqs.status.active') : t('faqs.status.inactive') }}
                </span>
            </template>

            <template #cell-sort_order="{ value }">
                <span
                    class="inline-flex items-center justify-center rounded-md bg-muted px-2.5 py-1 text-sm font-mono font-semibold text-muted-foreground">
                    {{ value }}
                </span>
            </template>

            <template #empty>
                <div class="text-muted-foreground">
                    <p class="text-sm">{{ t('faqs.empty.no_faqs') }}</p>
                    <Link
                        v-if="canCreate"
                        :href="faqsCreate().url"
                        class="mt-2 inline-block text-sm text-primary hover:underline"
                    >
                        {{ t('faqs.empty.create_first') }}
                    </Link>
                </div>
            </template>
        </DataTable>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable from '@/components/admin/DataTable.vue';
import AppFilters from '@/components/admin/AppFilters.vue';
import { usePermissions } from '@/composables/usePermissions';
import { useConfirm } from '@/composables/useConfirm';
import { useI18n } from 'vue-i18n';
import {
    index as faqsIndex,
    destroy as faqsDestroy,
    edit as faqsEdit,
    create as faqsCreate,
} from '@/routes/admin/faqs';

interface Props {
    faqs: {
        data: Array<{
            id: number;
            question: string;
            answer: string;
            sort_order: number;
            is_active: boolean;
        }>;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
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
const { can } = usePermissions();
const { t } = useI18n();
const { confirm } = useConfirm();

const search = ref(props.filters?.search || '');
const selectedStatus = ref(props.filters?.status || '');

const canCreate = computed(() => can('faqs.create'));
const canEdit = computed(() => can('faqs.update'));
const canDelete = computed(() => can('faqs.delete'));

const columns = computed(() => [
    { key: 'question', label: t('faqs.columns.question') },
    { key: 'is_active', label: t('faqs.columns.status'), align: 'center' as const },
    { key: 'sort_order', label: t('faqs.columns.sort'), align: 'center' as const },
]);

const actions = computed(() => {
    const a: any[] = [];
    if (canEdit.value) {
        a.push({
            type: 'link' as const,
            label: t('common.edit'),
            icon: 'pencil',
            href: (row: any) => faqsEdit({ faq: row.id }),
        });
    }
    if (canDelete.value) {
        a.push({
            type: 'button' as const,
            label: t('common.delete'),
            icon: 'trash2',
            variant: 'destructive' as const,
            onClick: (row: any) => confirmDelete({ id: row.id, question: row.question }),
        });
    }
    return a;
});

const pagination = computed(() => ({
    links: props.faqs.links,
    from: props.faqs.from,
    to: props.faqs.to,
    total: props.faqs.total,
}));

const confirmDelete = async (row: { id: number; question: string }) => {
    const ok = await confirm({
        title: t('faqs.delete.title'),
        description: t('faqs.delete.description'),
        details: row.question,
        confirmText: t('faqs.delete.confirm'),
        confirmVariant: 'destructive',
    });
    if (!ok) return;

    router.delete(faqsDestroy({ faq: row.id }).url, { preserveScroll: true });
};

const handleSearch = () => {
    router.get(
        faqsIndex().url,
        {
            search: search.value || null,
            status: selectedStatus.value || null,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const handleFilter = () => {
    router.get(
        faqsIndex().url,
        {
            search: search.value || null,
            status: selectedStatus.value || null,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const resetFilters = () => {
    search.value = '';
    selectedStatus.value = '';

    router.get(
        faqsIndex().url,
        {},
        {
            preserveState: true,
            replace: true,
        }
    );
};

const truncateHtml = (html: string, length: number) => {
    // Remove HTML tags for length calculation
    const text = html.replace(/<[^>]*>/g, '');
    if (text.length <= length) return html;
    // Truncate and add ellipsis
    return text.substring(0, length) + '...';
};
</script>
