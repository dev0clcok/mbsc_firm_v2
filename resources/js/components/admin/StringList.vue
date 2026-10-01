<script setup lang="ts">
import { Plus, Trash2 } from 'lucide-vue-next';
import { nextTick, ref } from 'vue';
import { useLocaleFormat } from '@/composables/useLocaleFormat';

/** Editable list of short text lines, such as what a service covers. */
const props = defineProps<{
    modelValue: string[];
    addLabel: string;
    removeLabel: string;
    placeholder?: string;
    disabled?: boolean;
}>();

const emit = defineEmits<{ (e: 'update:modelValue', value: string[]): void }>();

const { formatNumber } = useLocaleFormat();

const root = ref<HTMLElement | null>(null);

const update = (index: number, value: string) => {
    const next = [...props.modelValue];
    next[index] = value;
    emit('update:modelValue', next);
};

const remove = (index: number) => emit('update:modelValue', props.modelValue.filter((_, i) => i !== index));

const add = async () => {
    emit('update:modelValue', [...props.modelValue, '']);
    await nextTick();
    root.value?.querySelectorAll<HTMLInputElement>('input')[props.modelValue.length - 1]?.focus();
};
</script>

<template>
    <div ref="root" class="space-y-2">
        <div v-for="(item, index) in modelValue" :key="index" class="flex items-center gap-2">
            <input
                :value="item"
                type="text"
                class="w-full rounded-md border border-input bg-background px-3 py-2"
                :placeholder="placeholder"
                :aria-label="`${placeholder ?? ''} ${formatNumber(index + 1)}`.trim()"
                :disabled="disabled"
                @input="update(index, ($event.target as HTMLInputElement).value)"
                @keydown.enter.prevent="add"
            />
            <button
                type="button"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md text-destructive hover:bg-destructive/10"
                :aria-label="`${removeLabel} ${formatNumber(index + 1)}`"
                :disabled="disabled"
                @click="remove(index)"
            >
                <Trash2 class="size-4" />
            </button>
        </div>
        <button
            type="button"
            class="inline-flex min-h-10 items-center gap-2 rounded-md border border-dashed border-input px-3 py-2 text-sm font-medium hover:bg-muted"
            :disabled="disabled"
            @click="add"
        >
            <Plus class="size-4" />
            {{ addLabel }}
        </button>
    </div>
</template>
