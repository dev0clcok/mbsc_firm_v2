<template>
    <PublicLayout>
        <Head title="Blog - MBSC" />

        <div class="bg-muted/50 py-12">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <h1 class="mb-4 text-4xl font-bold">Latest Blog</h1>
                    <p class="text-muted-foreground">Stay updated with our latest news and insights</p>
                </div>

                <!-- Filters -->
                <div class="mb-8 flex flex-wrap gap-4">
                    <div class="flex-1">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search blog posts..."
                            class="w-full rounded-md border border-input bg-background px-4 py-2"
                            @input="handleSearch"
                        />
                    </div>
                    <select
                        v-model="selectedCategory"
                        class="rounded-md border border-input bg-background px-4 py-2"
                        @change="handleCategoryChange"
                    >
                        <option value="">All Categories</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }} ({{ category.posts_count }})
                        </option>
                    </select>
                </div>

                <!-- Blog Posts Grid -->
                <div v-if="posts.data.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <BlogCard
                        v-for="post in posts.data"
                        :key="post.id"
                        :post="post"
                    />
                </div>
                <div v-else class="rounded-lg border border-border bg-card p-12 text-center">
                    <p class="text-muted-foreground">No blog posts found.</p>
                </div>

                <!-- Pagination -->
                <div v-if="posts.links && posts.links.length > 3" class="mt-12 flex justify-center">
                    <div class="flex gap-2">
                        <Link
                            v-for="link in posts.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            :class="[
                                'rounded-md px-4 py-2',
                                link.active
                                    ? 'bg-primary text-primary-foreground'
                                    : 'bg-background text-foreground hover:bg-muted',
                                !link.url && 'pointer-events-none opacity-50',
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import BlogCard from '@/components/public/BlogCard.vue';
import * as blog from '@/routes/blog';

interface Props {
    posts: {
        data: Array<{
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
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    categories: Array<{
        id: number;
        name: string;
        posts_count: number;
    }>;
    filters: {
        category?: number;
        search?: string;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category?.toString() || '');

const handleSearch = () => {
    router.get(blog.index().url, { search: search.value, category: selectedCategory.value || null }, {
        preserveState: true,
        replace: true,
    });
};

const handleCategoryChange = () => {
    router.get(blog.index().url, { search: search.value, category: selectedCategory.value || null }, {
        preserveState: true,
        replace: true,
    });
};
</script>
