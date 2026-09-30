<script setup lang="ts">
import SiteBreadcrumb from '@/components/site/SiteBreadcrumb.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{ policy: string; updatedAt: string | null }>();

type Block = { type: 'heading' | 'paragraph'; text: string } | { type: 'list'; items: string[] };

// The policy is written as plain text: "## " starts a heading, "- " a list item, a blank line a new paragraph.
const blocks = computed<Block[]>(() =>
    props.policy
        .replace(/\r\n/g, '\n')
        .split(/\n{2,}/)
        .map((chunk) => chunk.trim())
        .filter(Boolean)
        .map((chunk) => {
            const lines = chunk.split('\n').map((line) => line.trim());

            if (chunk.startsWith('## ')) return { type: 'heading', text: chunk.slice(3).trim() };
            if (lines.every((line) => line.startsWith('- '))) return { type: 'list', items: lines.map((line) => line.slice(2)) };

            return { type: 'paragraph', text: lines.join(' ') };
        }),
);

const updated = computed(() =>
    props.updatedAt ? new Date(props.updatedAt).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }) : null,
);
</script>

<template>
    <Head title="Privacy policy" />

    <PublicLayout>
        <article class="pt-8 pb-section">
            <div class="site-container">
                <SiteBreadcrumb :items="[{ label: 'Privacy policy' }]" />
                <h1 class="site-display mt-6">Privacy policy</h1>
                <p v-if="updated" class="mt-4 text-ink-soft">Last updated {{ updated }}</p>

                <div class="site-prose mt-10 space-y-4">
                    <template v-for="(block, index) in blocks" :key="index">
                        <h2 v-if="block.type === 'heading'" class="site-heading pt-6 text-2xl">{{ block.text }}</h2>
                        <ul v-else-if="block.type === 'list'" class="list-disc space-y-1 pl-5">
                            <li v-for="item in block.items" :key="item">{{ item }}</li>
                        </ul>
                        <p v-else>{{ block.text }}</p>
                    </template>
                </div>
            </div>
        </article>
    </PublicLayout>
</template>
