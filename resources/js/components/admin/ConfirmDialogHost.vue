<template>
    <ConfirmDialog
        v-model:open="state.open"
        :title="state.options.title"
        :description="state.options.description"
        :details="state.options.details"
        :confirm-text="state.options.confirmText"
        :cancel-text="state.options.cancelText"
        :confirm-variant="state.options.confirmVariant"
        :loading="state.loading"
        @confirm="onConfirm"
        @update:open="(v) => (v ? undefined : onClose())"
    />
</template>

<script setup lang="ts">
import ConfirmDialog from '@/components/admin/ConfirmDialog.vue';
import { useConfirm } from '@/composables/useConfirm';

const { state, onConfirm, onClose } = useConfirm();
</script>

