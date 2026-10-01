<template>
    <div class="overflow-hidden rounded-xl border border-border bg-card shadow-xs">
        <!-- Below md each row becomes a stacked block, so nothing needs sideways scrolling on a phone. -->
        <div class="md:overflow-x-auto">
            <table class="w-full max-md:block">
                <thead class="max-md:hidden">
                    <tr class="border-b border-border bg-muted/40">
                        <th v-if="reorderUrl" class="w-px py-3 pr-0 pl-3 text-left text-xs font-medium text-muted-foreground">
                            <span class="sr-only">{{ t('datatable.order') }}</span>
                        </th>
                        <th
                            v-for="column in columns"
                            :key="column.key"
                            :class="[
                                'px-5 py-3 text-left text-xs font-medium text-muted-foreground',
                                column.align === 'center' && 'text-center',
                                column.align === 'right' && 'text-right',
                                !column.align && 'text-left',
                            ]"
                        >
                            <div class="flex items-center gap-2">
                                <span>{{ column.label }}</span>
                            </div>
                        </th>
                        <th
                            v-if="actions && actions.length > 0"
                            class="px-5 py-3 text-right text-xs font-medium text-muted-foreground"
                        >
                            {{ t('datatable.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody ref="tbody" class="divide-y divide-border bg-background max-md:block">
                    <tr
                        v-for="(row, index) in rows"
                        :key="getRowKey(row, index)"
                        class="group/row transition-colors duration-150 hover:bg-muted/30 max-md:block max-md:px-4 max-md:py-3"
                    >
                        <td v-if="reorderUrl" class="w-px py-3 pr-0 pl-3 align-middle max-md:block max-md:px-0 max-md:py-0 max-md:pb-1">
                            <div class="flex items-center gap-0.5">
                                <span
                                    class="drag-handle flex h-9 w-7 cursor-grab items-center justify-center rounded text-muted-foreground hover:bg-muted active:cursor-grabbing max-md:hidden"
                                    :title="t('datatable.drag')"
                                    aria-hidden="true"
                                >
                                    <Icon name="gripVertical" class="h-4 w-4" />
                                </span>
                                <button
                                    type="button"
                                    class="flex h-9 w-8 items-center justify-center rounded text-muted-foreground hover:bg-muted disabled:opacity-30"
                                    :aria-label="t('datatable.move_up')"
                                    :disabled="index === 0"
                                    @click="move(index, -1)"
                                >
                                    <Icon name="chevronUp" class="h-4 w-4" />
                                </button>
                                <button
                                    type="button"
                                    class="flex h-9 w-8 items-center justify-center rounded text-muted-foreground hover:bg-muted disabled:opacity-30"
                                    :aria-label="t('datatable.move_down')"
                                    :disabled="index === rows.length - 1"
                                    @click="move(index, 1)"
                                >
                                    <Icon name="chevronDown" class="h-4 w-4" />
                                </button>
                            </div>
                        </td>
                        <td
                            v-for="(column, columnIndex) in columns"
                            :key="column.key"
                            :class="[
                                'px-5 py-3.5 align-middle text-sm max-md:px-0 max-md:py-1.5 max-md:text-left',
                                column.align === 'center' && 'md:text-center',
                                column.align === 'right' && 'md:text-right',
                                // On phones the first column is the row's heading; the rest are label and value pairs.
                                columnIndex === 0 ? 'max-md:block' : 'max-md:flex max-md:items-center max-md:justify-between max-md:gap-4',
                            ]"
                        >
                            <span v-if="columnIndex > 0" class="text-xs font-medium text-muted-foreground md:hidden">
                                {{ column.label }}
                            </span>
                            <slot
                                :name="`cell-${column.key}`"
                                :row="row"
                                :value="getNestedValue(row, column.key)"
                            >
                                <span class="text-foreground">
                                    {{ displayValue(getNestedValue(row, column.key)) }}
                                </span>
                            </slot>
                        </td>
                        <td
                            v-if="actions && actions.length > 0"
                            class="px-5 py-3.5 align-middle max-md:block max-md:px-0 max-md:pt-2 max-md:pb-0"
                        >
                            <div class="flex items-center justify-end gap-2 max-md:-ml-2 max-md:justify-start">
                                <template v-for="(action, actionIndex) in actions" :key="actionIndex">
                                    <Link
                                        v-if="action.type === 'link'"
                                        :href="action.href?.(row)?.url ?? action.href?.(row) ?? '#'"
                                        class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-input bg-background px-3 text-sm font-medium text-foreground shadow-xs transition-colors hover:bg-muted focus-visible:ring-3 focus-visible:ring-ring/20 focus-visible:outline-none"
                                    >
                                        <Icon :name="action.icon" class="h-4 w-4 text-muted-foreground" />
                                        {{ action.label }}
                                    </Link>
                                    <button
                                        v-else-if="action.type === 'button'"
                                        type="button"
                                        :title="action.variant === 'destructive' ? action.label : undefined"
                                        :aria-label="action.variant === 'destructive' ? action.label : undefined"
                                        :class="
                                            action.variant === 'destructive'
                                                ? 'inline-flex h-9 w-9 items-center justify-center rounded-lg text-destructive transition-colors duration-150 hover:bg-destructive/10 focus-visible:ring-3 focus-visible:ring-ring/20 focus-visible:outline-none'
                                                : 'inline-flex h-9 items-center gap-1.5 rounded-lg border border-input bg-background px-3 text-sm font-medium text-foreground shadow-xs transition-colors hover:bg-muted focus-visible:ring-3 focus-visible:ring-ring/20 focus-visible:outline-none'
                                        "
                                        @click="action.onClick?.(row)"
                                    >
                                        <Icon :name="action.icon" :class="action.variant === 'destructive' ? 'h-4 w-4' : 'h-4 w-4 text-muted-foreground'" />
                                        <template v-if="action.variant !== 'destructive'">{{ action.label }}</template>
                                    </button>
                                </template>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="rows.length === 0" class="max-md:block">
                        <td
                            :colspan="columns.length + (actions && actions.length > 0 ? 1 : 0) + (reorderUrl ? 1 : 0)"
                            class="px-6 py-16 text-center max-md:block"
                        >
                            <slot name="empty">
                                <div class="flex flex-col items-center justify-center text-muted-foreground">
                                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-muted">
                                        <svg
                                            class="h-6 w-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                            />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium">{{ t('datatable.no_data_found') }}</p>
                                    <p class="mt-1 text-xs">{{ t('datatable.try_adjusting') }}</p>
                                </div>
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="pagination && pagination.links && pagination.links.length > 3"
            class="border-t border-border px-5 py-3"
        >
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="text-sm text-muted-foreground">
                    <span v-if="pagination.from && pagination.to" class="font-medium">
                        {{ t('datatable.showing') }}
                        <span class="font-semibold text-foreground">{{ formatNumber(pagination.from) }}</span>
                        {{ t('datatable.to') }}
                        <span class="font-semibold text-foreground">{{ formatNumber(pagination.to) }}</span>
                        {{ t('datatable.of') }}
                        <span class="font-semibold text-foreground">{{ formatNumber(pagination.total) }}</span>
                        {{ t('datatable.results') }}
                    </span>
                    <span v-else class="font-medium">
                        <span class="font-semibold text-foreground">{{ formatNumber(pagination.total) }}</span>
                        {{ t('datatable.total') }}
                    </span>
                </div>
                <nav class="flex items-center gap-1" :aria-label="t('datatable.pagination')">
                    <Link
                        v-for="(link, linkIndex) in pagination.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'relative inline-flex min-h-9 items-center rounded-lg px-3 text-sm font-medium transition-colors duration-150',
                            link.active
                                ? 'z-10 bg-primary text-primary-foreground'
                                : 'bg-background text-foreground hover:bg-muted hover:text-foreground',
                            !link.url && 'pointer-events-none opacity-40 cursor-not-allowed',
                            link.url && !link.active && 'hover:shadow-sm',
                        ]"
                    >
                        {{ pageLabel(link.label, linkIndex, pagination.links.length) }}
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import Icon from '@/components/Icon.vue';
import Sortable from 'sortablejs';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useLocaleFormat } from '@/composables/useLocaleFormat';

interface Column {
    key: string;
    label: string;
    align?: 'left' | 'center' | 'right';
}

interface Action {
    type: 'link' | 'button';
    label: string;
    icon: string;
    href?: (row: any) => string | { url: string } | any;
    onClick?: (row: any) => void;
    variant?: 'default' | 'destructive';
}

interface Pagination {
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    from?: number | null;
    to?: number | null;
    total?: number;
}

interface Props {
    columns: Column[];
    data: any[];
    actions?: Action[];
    pagination?: Pagination;
    rowKey?: string | ((row: any, index: number) => string | number);
    /**
     * When set, rows can be dragged (or moved with the arrow buttons) and
     * the new order of ids is posted here. Leave unset while the list is
     * filtered, since a partial list cannot be ordered meaningfully.
     */
    reorderUrl?: string;
}

const props = withDefaults(defineProps<Props>(), {
    actions: () => [],
    rowKey: 'id',
});

const { t } = useI18n();
const { formatNumber } = useLocaleFormat();

// Laravel sends the first and last links as "&laquo; Previous" and "Next &raquo;"; the rest are page numbers or "...".
const pageLabel = (label: string, index: number, count: number) => {
    if (index === 0) return `« ${t('datatable.previous')}`;
    if (index === count - 1) return `${t('datatable.next')} »`;
    return /^\d+$/.test(label) ? formatNumber(Number(label)) : label;
};

const displayValue = (value: unknown) => {
    if (!value) return '—';
    return typeof value === 'number' ? formatNumber(value) : value;
};

// A local copy so a drag shows its result at once, before the server confirms.
const rows = ref<any[]>([...props.data]);
watch(
    () => props.data,
    (data) => (rows.value = [...data]),
);

const tbody = ref<HTMLElement | null>(null);
let sortable: Sortable | null = null;

const saveOrder = () => {
    if (!props.reorderUrl) return;

    router.post(
        props.reorderUrl,
        { ids: rows.value.map((row) => row.id), offset: (props.pagination?.from ?? 1) - 1 },
        { preserveScroll: true, preserveState: true },
    );
};

const move = (index: number, by: number) => {
    const target = index + by;
    if (target < 0 || target >= rows.value.length) return;

    const next = [...rows.value];
    [next[index], next[target]] = [next[target], next[index]];
    rows.value = next;
    saveOrder();
};

onMounted(() => {
    if (!props.reorderUrl || !tbody.value) return;

    sortable = Sortable.create(tbody.value, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: ({ item, from, oldIndex, newIndex }) => {
            if (oldIndex === undefined || newIndex === undefined || oldIndex === newIndex) return;

            // Put the row back where it was and let Vue do the move, so the
            // page and Vue's own record of the row order cannot drift apart.
            from.removeChild(item);
            from.insertBefore(item, from.children[oldIndex] ?? null);

            const next = [...rows.value];
            next.splice(newIndex, 0, next.splice(oldIndex, 1)[0]);
            rows.value = next;
            saveOrder();
        },
    });
});

onBeforeUnmount(() => sortable?.destroy());

const getRowKey = (row: any, index: number) => {
    if (typeof props.rowKey === 'function') {
        return props.rowKey(row, index);
    }
    return row[props.rowKey] || index;
};

const getNestedValue = (obj: any, path: string) => {
    return path.split('.').reduce((current, prop) => current?.[prop], obj);
};
</script>
