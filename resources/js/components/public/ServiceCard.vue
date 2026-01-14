<template>
    <div class="group relative overflow-hidden rounded-lg border border-border bg-card p-6 transition-all hover:shadow-lg">
        <div class="mb-4 flex items-center gap-3">
            <div
                v-if="service.icon"
                class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary"
            >
                <Icon :name="service.icon" class="h-6 w-6" />
            </div>
            <h3 class="text-lg font-semibold">{{ service.title }}</h3>
        </div>

        <p class="mb-4 text-sm text-muted-foreground line-clamp-3">
            {{ service.description }}
        </p>

        <div v-if="service.children && service.children.length > 0" class="mb-4">
            <p class="mb-2 text-xs font-medium text-muted-foreground">Sub-services:</p>
            <ul class="space-y-1">
                <li
                    v-for="child in service.children.slice(0, 3)"
                    :key="child.id"
                    class="text-xs text-muted-foreground"
                >
                    • {{ child.title }}
                </li>
                <li
                    v-if="service.children.length > 3"
                    class="text-xs text-muted-foreground"
                >
                    +{{ service.children.length - 3 }} more
                </li>
            </ul>
        </div>

        <Link
            :href="serviceRoutes.show({ service: service.slug }).url"
            class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:underline"
        >
            Learn More
            <Icon name="arrow-right" class="h-4 w-4" />
        </Link>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Icon from '@/components/Icon.vue';
import * as serviceRoutes from '@/routes/services';

interface Props {
    service: {
        id: number;
        slug: string;
        title: string;
        description: string | null;
        icon: string | null;
        children?: Array<{
            id: number;
            title: string;
        }>;
    };
}

defineProps<Props>();
</script>
