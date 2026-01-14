<template>
    <div class="overflow-hidden rounded-lg border border-border bg-card transition-all hover:shadow-lg">
        <div v-if="post.featured_image" class="aspect-video w-full bg-muted">
            <img
                :src="post.featured_image"
                :alt="post.title"
                class="h-full w-full object-cover"
            />
        </div>
        <div class="p-6">
            <div v-if="post.category" class="mb-2">
                <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary">
                    {{ post.category.name }}
                </span>
            </div>
            <div class="mb-2 text-sm text-muted-foreground">
                {{ formatDate(post.published_at) }}
            </div>
            <h3 class="mb-2 text-xl font-semibold">
                <Link
                    :href="blog.show({ post: post.slug }).url"
                    class="hover:text-primary"
                >
                    {{ post.title }}
                </Link>
            </h3>
            <p v-if="post.excerpt" class="mb-4 text-sm text-muted-foreground line-clamp-3">
                {{ post.excerpt }}
            </p>
            <Link
                :href="blog.show({ post: post.slug }).url"
                class="text-sm font-medium text-primary hover:underline"
            >
                Read More →
            </Link>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import * as blog from '@/routes/blog';

interface Props {
    post: {
        id: number;
        slug: string;
        title: string;
        excerpt: string | null;
        featured_image: string | null;
        published_at: string;
        category?: {
            name: string;
        } | null;
    };
}

defineProps<Props>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>
