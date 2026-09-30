<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

/** Switch that shows or hides an item on the website straight from a list. */
const props = defineProps<{
    active: boolean;
    /** PATCH endpoint that flips the item. */
    url: string;
    /** Name of the item, for screen readers. */
    label: string;
    disabled?: boolean;
}>();

const { t } = useI18n();
const busy = ref(false);

const toggle = () => {
    if (busy.value || props.disabled) return;
    busy.value = true;
    router.patch(props.url, {}, { preserveScroll: true, preserveState: true, onFinish: () => (busy.value = false) });
};
</script>

<template>
    <span class="inline-flex items-center gap-2.5">
        <button
            type="button"
            role="switch"
            :aria-checked="active"
            :aria-label="t('common.shown_on_site_for', { name: label })"
            :disabled="disabled || busy"
            :class="[
                'relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors disabled:cursor-not-allowed disabled:opacity-60',
                active ? 'bg-emerald-600' : 'bg-gray-300 dark:bg-gray-600',
            ]"
            @click="toggle"
        >
            <span :class="['inline-block h-5 w-5 rounded-full bg-white shadow transition-transform', active ? 'translate-x-[1.375rem]' : 'translate-x-0.5']" />
        </button>
        <span class="text-xs font-semibold text-muted-foreground">{{ active ? t('common.shown') : t('common.hidden') }}</span>
    </span>
</template>
