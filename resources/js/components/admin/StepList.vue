<script setup lang="ts">
import { Plus, Trash2 } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

export interface Step {
    title: string;
    description: string;
}

/** Editable, ordered list of steps, each with a title and a short description. */
const props = defineProps<{ modelValue: Step[] }>();
const emit = defineEmits<{ (e: 'update:modelValue', value: Step[]): void }>();

const { t } = useI18n();

const update = (index: number, key: keyof Step, value: string) => {
    emit('update:modelValue', props.modelValue.map((step, i) => (i === index ? { ...step, [key]: value } : step)));
};
</script>

<template>
    <div class="space-y-3">
        <div v-for="(step, index) in modelValue" :key="index" class="flex items-start gap-3 rounded-md border border-border p-3">
            <span class="mt-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-muted text-sm font-semibold tabular-nums">{{ index + 1 }}</span>
            <div class="grid flex-1 gap-2">
                <input
                    :value="step.title"
                    type="text"
                    class="w-full rounded-md border border-input bg-background px-3 py-2 font-medium"
                    :placeholder="t('steps.title_placeholder')"
                    :aria-label="`${t('steps.title_placeholder')} ${index + 1}`"
                    @input="update(index, 'title', ($event.target as HTMLInputElement).value)"
                />
                <input
                    :value="step.description"
                    type="text"
                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                    :placeholder="t('steps.description_placeholder')"
                    :aria-label="`${t('steps.description_placeholder')} ${index + 1}`"
                    @input="update(index, 'description', ($event.target as HTMLInputElement).value)"
                />
            </div>
            <button
                type="button"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md text-destructive hover:bg-destructive/10"
                :aria-label="`${t('steps.remove')} ${index + 1}`"
                @click="emit('update:modelValue', modelValue.filter((_, i) => i !== index))"
            >
                <Trash2 class="size-4" />
            </button>
        </div>
        <button
            type="button"
            class="inline-flex min-h-10 items-center gap-2 rounded-md border border-dashed border-input px-3 py-2 text-sm font-medium hover:bg-muted"
            @click="emit('update:modelValue', [...modelValue, { title: '', description: '' }])"
        >
            <Plus class="size-4" />
            {{ t('steps.add') }}
        </button>
    </div>
</template>
