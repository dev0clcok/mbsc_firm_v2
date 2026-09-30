<script setup lang="ts">
import { Search, X } from 'lucide-vue-next';
import { useId } from 'vue';

/**
 * Toolbar above a list: a search box, any extra filters passed in the
 * `filters` slot, and a reset button.
 */
withDefaults(
    defineProps<{
        search?: string;
        searchLabel?: string;
        searchPlaceholder?: string;
        resetText?: string;
        showSearch?: boolean;
        /** Kept for older callers; the toolbar now lays itself out. */
        cols?: number;
    }>(),
    {
        search: '',
        searchLabel: 'Search',
        searchPlaceholder: 'Search...',
        resetText: 'Reset filters',
        showSearch: true,
        cols: 4,
    },
);

const emit = defineEmits<{
    (e: 'update:search', value: string): void;
    (e: 'search'): void;
    (e: 'reset'): void;
}>();

const id = useId();

const onInput = (e: Event) => {
    emit('update:search', (e.target as HTMLInputElement).value);
    emit('search');
};
</script>

<template>
    <div class="admin-toolbar flex flex-wrap items-end gap-3 rounded-xl border border-border bg-card p-3 shadow-xs sm:p-4">
        <div v-if="showSearch" class="min-w-0 flex-[2_1_16rem]">
            <label :for="id" class="sr-only">{{ searchLabel }}</label>
            <div class="relative">
                <Search class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                <input :id="id" :value="search" type="search" :placeholder="searchPlaceholder" class="h-10 w-full border border-input py-2 pr-3 pl-9" @input="onInput" />
            </div>
        </div>

        <slot name="filters" />

        <button
            type="button"
            class="inline-flex min-h-10 items-center gap-1.5 rounded-lg px-3 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
            @click="emit('reset')"
        >
            <X class="size-4" />
            {{ resetText }}
        </button>
    </div>
</template>

<style scoped>
/* Filters passed into the slot sit on the same row as the search box. */
.admin-toolbar :deep(> div:not(:first-child)) {
    flex: 1 1 11rem;
    min-width: 0;
}

.admin-toolbar :deep(label:not(.sr-only)) {
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
    color: var(--muted-foreground);
}
</style>
