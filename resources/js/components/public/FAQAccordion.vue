<template>
    <div class="space-y-4">
        <div
            v-for="faq in faqs"
            :key="faq.id"
            class="rounded-lg border border-border bg-card"
        >
            <button
                :class="[
                    'flex w-full items-center justify-between p-6 text-left',
                    isOpen(faq.id) && 'bg-muted/50',
                ]"
                @click="toggle(faq.id)"
            >
                <span class="font-medium">{{ faq.question }}</span>
                <Icon
                    :name="isOpen(faq.id) ? 'chevron-up' : 'chevron-down'"
                    class="h-5 w-5 text-muted-foreground"
                />
            </button>
            <div
                v-show="isOpen(faq.id)"
                class="border-t border-border p-6 text-sm text-muted-foreground"
            >
                <p class="whitespace-pre-line">{{ faq.answer }}</p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Icon from '@/components/Icon.vue';

interface Props {
    faqs: Array<{
        id: number;
        question: string;
        answer: string;
    }>;
}

defineProps<Props>();

const openItems = ref<Set<number>>(new Set());

const isOpen = (id: number) => openItems.value.has(id);

const toggle = (id: number) => {
    if (openItems.value.has(id)) {
        openItems.value.delete(id);
    } else {
        openItems.value.add(id);
    }
};
</script>
