<template>
    <AppLayout>

        <Head :title="t('faqs.title')" />
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold tracking-tight">{{ t('faqs.title') }}</h1>
            <Link
                v-if="canCreate"
                :href="faqsCreate().url"
                class="inline-flex min-h-10 items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
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
                <div>
                    <label for="faq-service-filter" class="mb-2 block text-sm font-medium">{{ t('faqs.filters.service') }}</label>
                    <select
                        id="faq-service-filter"
                        v-model="selectedService"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        @change="handleFilter"
                    >
                        <option value="">{{ t('faqs.filters.all_services') }}</option>
                        <option value="general">{{ t('faqs.form.general') }}</option>
                        <option v-for="service in services" :key="service.id" :value="String(service.id)">{{ service.title }}</option>
                    </select>
                </div>
            </template>
        </AppFilters>

        <!-- DataTable -->
        <p v-if="canEdit" class="text-sm text-muted-foreground">{{ t('datatable.reorder_hint') }}</p>

        <DataTable :key="reorderUrl ?? 'fixed'" :reorder-url="reorderUrl" :columns="columns" :data="faqs.data" :actions="actions" :pagination="pagination">
            <template #cell-question="{ row }">
                <div class="max-w-lg">
                    <div class="font-semibold text-foreground">
                        {{ row.question }}
                    </div>
                    <div class="mt-1.5 text-sm leading-relaxed text-muted-foreground line-clamp-2"
                        v-html="truncateHtml(row.answer, 80)" />
                </div>
            </template>

            <template #cell-service="{ row }">
                <span class="text-sm text-muted-foreground">{{ row.service?.title ?? t('faqs.form.general') }}</span>
            </template>

            <template #cell-is_active="{ row, value }">
                <StatusToggle :active="value" :url="`/admin/faqs/${row.id}/toggle`" :label="row.question" :disabled="!canEdit" />
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
import StatusToggle from '@/components/admin/StatusToggle.vue';
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
            service: { id: number; title: string } | null;
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
    services: Array<{ id: number; title: string }>;
    filters?: {
        search?: string;
        status?: string;
        service?: string;
    };
}

const props = defineProps<Props>();
const { can } = usePermissions();
const { t } = useI18n();
const { confirm } = useConfirm();

const search = ref(props.filters?.search || '');
const selectedStatus = ref(props.filters?.status || '');
const selectedService = ref(props.filters?.service || '');

const canCreate = computed(() => can('faqs.create'));
const canEdit = computed(() => can('faqs.update'));

// Ordering only makes sense on the full, unfiltered list.
const reorderUrl = computed(() => (canEdit.value && !search.value && !selectedStatus.value && !selectedService.value ? '/admin/faqs/reorder' : undefined));
const canDelete = computed(() => can('faqs.delete'));

const columns = computed(() => [
    { key: 'question', label: t('faqs.columns.question') },
    { key: 'service', label: t('faqs.columns.service') },
    { key: 'is_active', label: t('faqs.columns.status'), align: 'center' as const },
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
            service: selectedService.value || null,
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
            service: selectedService.value || null,
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
    selectedService.value = '';

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
