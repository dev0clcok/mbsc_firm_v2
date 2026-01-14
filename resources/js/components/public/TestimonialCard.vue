<template>
    <div class="rounded-lg border border-border bg-card p-6">
        <div class="mb-4 flex items-center gap-1">
            <Icon
                v-for="i in 5"
                :key="i"
                :name="i <= testimonial.rating ? 'star' : 'star'"
                :class="[
                    'h-4 w-4',
                    i <= testimonial.rating ? 'fill-yellow-400 text-yellow-400' : 'text-muted-foreground',
                ]"
            />
        </div>
        <p class="mb-4 text-sm text-muted-foreground italic">
            "{{ testimonial.testimonial }}"
        </p>
        <div class="text-sm font-medium">{{ testimonial.client_name }}</div>
        <div v-if="testimonial.client_company" class="text-xs text-muted-foreground">
            {{ testimonial.client_company }}
        </div>
    </div>
</template>

<script setup lang="ts">
import Icon from '@/components/Icon.vue';

interface Props {
    testimonial: {
        id: number;
        client_name: string;
        client_company?: string | null;
        testimonial: string;
        rating: number;
    };
}

defineProps<Props>();
</script>
