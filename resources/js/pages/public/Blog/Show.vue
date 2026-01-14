<template>
    <PublicLayout>
        <Head :title="`${post.title} - MBSC`" />

        <div class="bg-muted/50 py-12">
            <div class="container mx-auto px-4">
                <article class="mx-auto max-w-4xl">
                    <!-- Breadcrumb -->
                    <nav class="mb-8 text-sm text-muted-foreground">
                        <Link :href="home().url" class="hover:text-primary">Home</Link>
                        <span class="mx-2">/</span>
                        <Link :href="blog.index().url" class="hover:text-primary">Blog</Link>
                        <span class="mx-2">/</span>
                        <span class="text-foreground">{{ post.title }}</span>
                    </nav>

                    <!-- Featured Image -->
                    <div v-if="post.featured_image" class="mb-8 aspect-video w-full overflow-hidden rounded-lg bg-muted">
                        <img
                            :src="post.featured_image"
                            :alt="post.title"
                            class="h-full w-full object-cover"
                        />
                    </div>

                    <!-- Post Header -->
                    <header class="mb-8">
                        <div v-if="post.category" class="mb-4">
                            <span class="rounded-full bg-primary/10 px-3 py-1 text-sm font-medium text-primary">
                                {{ post.category.name }}
                            </span>
                        </div>
                        <h1 class="mb-4 text-4xl font-bold">{{ post.title }}</h1>
                        <div class="flex items-center gap-4 text-sm text-muted-foreground">
                            <span>{{ formatDate(post.published_at) }}</span>
                            <span v-if="post.author">By {{ post.author.name }}</span>
                        </div>
                    </header>

                    <!-- Post Content -->
                    <div
                        class="prose prose-lg max-w-none rounded-lg border border-border bg-card p-8"
                        v-html="post.content"
                    />

                    <!-- Related Posts -->
                    <div v-if="relatedPosts.length > 0" class="mt-12">
                        <h2 class="mb-6 text-2xl font-semibold">Related Posts</h2>
                        <div class="grid gap-6 md:grid-cols-3">
                            <BlogCard
                                v-for="relatedPost in relatedPosts"
                                :key="relatedPost.id"
                                :post="relatedPost"
                            />
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import BlogCard from '@/components/public/BlogCard.vue';
import { home } from '@/routes';
import * as blog from '@/routes/blog';

interface Props {
    post: {
        id: number;
        slug: string;
        title: string;
        excerpt: string | null;
        content: string;
        featured_image: string | null;
        published_at: string;
        category?: {
            name: string;
        } | null;
        author?: {
            name: string;
        } | null;
    };
    relatedPosts: Array<{
        id: number;
        slug: string;
        title: string;
        excerpt: string | null;
        featured_image: string | null;
        published_at: string;
        category?: {
            name: string;
        } | null;
    }>;
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
