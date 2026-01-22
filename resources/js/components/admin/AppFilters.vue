<template>
    <div class="rounded-lg border border-border bg-card p-4">
        <div :class="gridClass">
            <!-- Custom filters -->
            <slot name="filters" />

            <!-- Search -->
            <div v-if="showSearch">
                <label class="mb-2 block text-sm font-medium">{{ searchLabel }}</label>
                <input
                    :value="search"
                    type="text"
                    :placeholder="searchPlaceholder"
                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                    @input="onInput"
                />
            </div>

            <!-- Reset -->
            <div class="flex items-end">
                <button
                    type="button"
                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm font-medium text-foreground transition-colors hover:bg-muted"
                    @click="emit('reset')"
                >
                    {{ resetText }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        search?: string;
        searchLabel?: string;
        searchPlaceholder?: string;
        resetText?: string;
        showSearch?: boolean;
        cols?: number; // md:grid-cols-{cols}
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

const gridClass = computed(() => {
    const cols = Math.min(Math.max(props.cols, 1), 6);
    // Tailwind v4 still supports arbitrary values, but keep it simple with known variants.
    const mdCols =
        cols === 1
            ? 'md:grid-cols-1'
            : cols === 2
              ? 'md:grid-cols-2'
              : cols === 3
                ? 'md:grid-cols-3'
                : cols === 5
                  ? 'md:grid-cols-5'
                  : cols === 6
                    ? 'md:grid-cols-6'
                    : 'md:grid-cols-4';
    return `grid gap-4 ${mdCols}`;
});

const onInput = (e: Event) => {
    const value = (e.target as HTMLInputElement).value;
    emit('update:search', value);
    emit('search');
};
</script>

