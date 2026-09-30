<script setup lang="ts">
import { ImageUp, Trash2 } from 'lucide-vue-next';
import { computed, onBeforeUnmount, ref, useId, watch } from 'vue';
import { useI18n } from 'vue-i18n';

/**
 * Picture upload with drag and drop, a preview at the shape the picture is
 * shown in on the site, and a description for screen readers.
 */
const props = withDefaults(
    defineProps<{
        label: string;
        /** Newly chosen file, or null to keep what is stored. */
        modelValue: File | null;
        /** True when the stored picture should be deleted on save. */
        remove?: boolean;
        alt?: string;
        existingUrl?: string | null;
        /** Width divided by height of the slot the picture fills on the site. */
        ratio?: number;
        /** Where the picture appears, shown as help text. */
        help?: string;
        error?: string;
        altError?: string;
        /** Hide the description field for pictures whose description is generated. */
        withoutAlt?: boolean;
        disabled?: boolean;
    }>(),
    { remove: false, alt: '', existingUrl: null, ratio: 3 / 2, withoutAlt: false },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: File | null): void;
    (e: 'update:remove', value: boolean): void;
    (e: 'update:alt', value: string): void;
}>();

const { t } = useI18n();
const id = useId();
const input = ref<HTMLInputElement | null>(null);
const dragging = ref(false);
const localError = ref('');
const objectUrl = ref<string | null>(null);

const ACCEPT = ['image/jpeg', 'image/png', 'image/webp'];
const MAX_BYTES = 8 * 1024 * 1024;

watch(
    () => props.modelValue,
    (file) => {
        if (objectUrl.value) URL.revokeObjectURL(objectUrl.value);
        objectUrl.value = file ? URL.createObjectURL(file) : null;
    },
);

onBeforeUnmount(() => objectUrl.value && URL.revokeObjectURL(objectUrl.value));

const previewUrl = computed(() => objectUrl.value ?? (props.remove ? null : props.existingUrl));

const choose = (file: File | undefined) => {
    localError.value = '';
    if (!file) return;

    if (!ACCEPT.includes(file.type)) {
        localError.value = t('image_field.wrong_type');
        return;
    }
    if (file.size > MAX_BYTES) {
        localError.value = t('image_field.too_large');
        return;
    }

    emit('update:remove', false);
    emit('update:modelValue', file);
};

const onDrop = (event: DragEvent) => {
    dragging.value = false;
    if (!props.disabled) choose(event.dataTransfer?.files?.[0]);
};

const clear = () => {
    if (input.value) input.value.value = '';
    localError.value = '';

    if (props.modelValue) {
        emit('update:modelValue', null);
    } else if (props.existingUrl) {
        emit('update:remove', true);
    }
};

defineExpose({ choose });
</script>

<template>
    <div>
        <p :id="`${id}-label`" class="mb-1.5 block text-sm font-medium">{{ label }}</p>

        <div class="grid gap-4 sm:grid-cols-[minmax(0,16rem)_minmax(0,1fr)]">
            <div
                :class="[
                    'relative flex items-center justify-center overflow-hidden rounded-md border bg-muted/40 text-center transition-colors',
                    dragging ? 'border-primary bg-primary/5' : 'border-dashed border-input',
                ]"
                :style="{ aspectRatio: String(ratio) }"
                @dragover.prevent="dragging = !disabled"
                @dragleave.prevent="dragging = false"
                @drop.prevent="onDrop"
            >
                <img v-if="previewUrl" :src="previewUrl" alt="" class="h-full w-full object-cover" />
                <div v-else class="p-4 text-sm text-muted-foreground">
                    <ImageUp class="mx-auto mb-2 size-6" />
                    {{ t('image_field.drop_here') }}
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <input
                        :id="id"
                        ref="input"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="sr-only"
                        :aria-labelledby="`${id}-label`"
                        :disabled="disabled"
                        @change="choose(($event.target as HTMLInputElement).files?.[0])"
                    />
                    <label
                        :for="id"
                        class="inline-flex min-h-10 cursor-pointer items-center gap-2 rounded-md border border-input bg-background px-3 py-2 text-sm font-medium hover:bg-muted has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-ring"
                    >
                        <ImageUp class="size-4" />
                        {{ previewUrl ? t('image_field.replace') : t('image_field.choose') }}
                    </label>
                    <slot name="actions" :has-file="Boolean(modelValue)" />
                    <button
                        v-if="previewUrl && !disabled"
                        type="button"
                        class="inline-flex min-h-10 items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-destructive hover:bg-destructive/10"
                        @click="clear"
                    >
                        <Trash2 class="size-4" />
                        {{ t('image_field.remove') }}
                    </button>
                </div>

                <p v-if="localError || error" class="text-sm font-medium text-red-600 dark:text-red-500" role="alert">{{ localError || error }}</p>
                <p class="text-xs text-muted-foreground">
                    <template v-if="help">{{ help }} </template>{{ t('image_field.guidance') }}
                </p>

                <div v-if="!withoutAlt && previewUrl">
                    <label :for="`${id}-alt`" class="mb-1.5 block text-sm font-medium">
                        {{ t('image_field.alt') }} <span class="text-destructive" aria-hidden="true">*</span>
                    </label>
                    <input
                        :id="`${id}-alt`"
                        :value="alt"
                        type="text"
                        maxlength="200"
                        class="w-full rounded-md border border-input bg-background px-3 py-2"
                        :aria-invalid="altError ? 'true' : undefined"
                        :aria-describedby="`${id}-alt-help`"
                        :disabled="disabled"
                        @input="emit('update:alt', ($event.target as HTMLInputElement).value)"
                    />
                    <p v-if="altError" class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-500">{{ altError }}</p>
                    <p :id="`${id}-alt-help`" class="mt-1.5 text-xs text-muted-foreground">{{ t('image_field.alt_help') }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
