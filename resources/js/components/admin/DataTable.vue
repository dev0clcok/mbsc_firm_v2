<template>
    <div class="overflow-hidden rounded-lg border border-border bg-card shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-border bg-gradient-to-r from-muted/80 to-muted/60">
                        <th
                            v-for="column in columns"
                            :key="column.key"
                            :class="[
                                'px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground',
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
                            class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-muted-foreground"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-background">
                    <tr
                        v-for="(row, index) in data"
                        :key="getRowKey(row, index)"
                        class="transition-all duration-150 hover:bg-muted/40 hover:shadow-sm"
                    >
                        <td
                            v-for="column in columns"
                            :key="column.key"
                            :class="[
                                'px-6 py-4 text-sm',
                                column.align === 'center' && 'text-center',
                                column.align === 'right' && 'text-right',
                            ]"
                        >
                            <slot
                                :name="`cell-${column.key}`"
                                :row="row"
                                :value="getNestedValue(row, column.key)"
                            >
                                <span
                                    v-if="column.format === 'html'"
                                    v-html="getNestedValue(row, column.key)"
                                    class="text-foreground"
                                />
                                <span
                                    v-else
                                    class="text-foreground"
                                >
                                    {{ getNestedValue(row, column.key) || '—' }}
                                </span>
                            </slot>
                        </td>
                        <td
                            v-if="actions && actions.length > 0"
                            class="px-6 py-4"
                        >
                            <div class="flex items-center justify-end gap-2">
                                <template v-for="(action, actionIndex) in actions" :key="actionIndex">
                                    <Link
                                        v-if="action.type === 'link'"
                                        :href="typeof action.href === 'function' ? action.href(row).url : action.href(row)"
                                        :title="action.label"
                                        :aria-label="action.label"
                                        :class="[
                                            'inline-flex h-9 w-9 items-center justify-center rounded-md transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
                                            action.variant === 'destructive'
                                                ? 'text-destructive hover:bg-destructive/10 hover:text-destructive/90'
                                                : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                                        ]"
                                    >
                                        <Icon :name="action.icon" class="h-4 w-4" />
                                        <span class="sr-only">{{ action.label }}</span>
                                    </Link>
                                    <button
                                        v-else-if="action.type === 'button'"
                                        @click="action.onClick(row)"
                                        type="button"
                                        :title="action.label"
                                        :aria-label="action.label"
                                        :class="[
                                            'inline-flex h-9 w-9 items-center justify-center rounded-md transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
                                            action.variant === 'destructive'
                                                ? 'text-destructive hover:bg-destructive/10 hover:text-destructive/90'
                                                : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                                        ]"
                                    >
                                        <Icon :name="action.icon" class="h-4 w-4" />
                                        <span class="sr-only">{{ action.label }}</span>
                                    </button>
                                </template>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="data.length === 0">
                        <td
                            :colspan="columns.length + (actions && actions.length > 0 ? 1 : 0)"
                            class="px-6 py-16 text-center"
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
                                    <p class="text-sm font-medium">No data found</p>
                                    <p class="mt-1 text-xs">Try adjusting your filters or create a new entry.</p>
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
            class="border-t border-border bg-muted/20 px-6 py-4"
        >
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="text-sm text-muted-foreground">
                    <span v-if="pagination.from && pagination.to" class="font-medium">
                        Showing
                        <span class="font-semibold text-foreground">{{ pagination.from }}</span>
                        to
                        <span class="font-semibold text-foreground">{{ pagination.to }}</span>
                        of
                        <span class="font-semibold text-foreground">{{ pagination.total }}</span>
                        results
                    </span>
                    <span v-else class="font-medium">
                        <span class="font-semibold text-foreground">{{ pagination.total }}</span>
                        total
                    </span>
                </div>
                <nav class="flex items-center gap-1" aria-label="Pagination">
                    <Link
                        v-for="link in pagination.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'relative inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-all duration-150',
                            link.active
                                ? 'z-10 bg-primary text-primary-foreground shadow-md'
                                : 'bg-background text-foreground hover:bg-muted hover:text-foreground',
                            !link.url && 'pointer-events-none opacity-40 cursor-not-allowed',
                            link.url && !link.active && 'hover:shadow-sm',
                        ]"
                        v-html="link.label"
                    />
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Icon from '@/components/Icon.vue';

interface Column {
    key: string;
    label: string;
    align?: 'left' | 'center' | 'right';
    format?: 'text' | 'html';
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
}

const props = withDefaults(defineProps<Props>(), {
    actions: () => [],
    rowKey: 'id',
});

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
