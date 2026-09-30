<script setup lang="ts">
import { useId } from 'vue';

/**
 * Label, help text and error for one form control. The default slot
 * receives the ids and error state to bind on the control.
 */
defineProps<{
    label: string;
    required?: boolean;
    help?: string;
    error?: string;
}>();

const id = useId();
</script>

<template>
    <div>
        <label :for="id" class="mb-1.5 block text-sm font-medium">
            {{ label }}
            <span v-if="required" class="text-destructive" aria-hidden="true">*</span>
        </label>
        <slot :id="id" :described-by="error ? `${id}-error` : help ? `${id}-help` : undefined" :invalid="error ? true : undefined" />
        <p v-if="error" :id="`${id}-error`" class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-500">{{ error }}</p>
        <p v-else-if="help" :id="`${id}-help`" class="mt-1.5 text-xs text-muted-foreground">{{ help }}</p>
    </div>
</template>
