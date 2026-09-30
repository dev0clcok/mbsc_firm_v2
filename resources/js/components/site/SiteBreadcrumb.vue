<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

/** Trail from the home page to the current page. The last item is the page itself. */
defineProps<{ items: Array<{ label: string; href?: string }> }>();
</script>

<template>
    <nav aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-x-2 text-sm text-ink-soft">
            <li><Link href="/" class="site-link inline-flex min-h-11 items-center">Home</Link></li>
            <template v-for="(item, index) in items" :key="item.label">
                <li aria-hidden="true">/</li>
                <li v-if="item.href && index < items.length - 1">
                    <Link :href="item.href" class="site-link inline-flex min-h-11 items-center">{{ item.label }}</Link>
                </li>
                <li v-else aria-current="page">{{ item.label }}</li>
            </template>
        </ol>
    </nav>
</template>
