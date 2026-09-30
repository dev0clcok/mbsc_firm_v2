<script setup lang="ts">
export interface Faq {
    id: number;
    question: string;
    /** Sanitised HTML from the server. */
    answer: string;
}

/** Questions as native disclosure widgets: keyboard and screen-reader support come from the browser. */
defineProps<{ faqs: Faq[] }>();
</script>

<template>
    <div class="divide-y divide-rule border-y border-rule">
        <details v-for="faq in faqs" :id="`faq-${faq.id}`" :key="faq.id" class="group scroll-mt-24">
            <summary class="flex min-h-14 cursor-pointer list-none items-center justify-between gap-6 py-4 font-semibold text-ink transition-colors marker:hidden hover:text-rose [&::-webkit-details-marker]:hidden">
                {{ faq.question }}
                <span class="relative h-4 w-4 shrink-0 text-rose" aria-hidden="true">
                    <span class="absolute top-1/2 left-0 h-0.5 w-4 -translate-y-1/2 bg-current"></span>
                    <span class="absolute top-0 left-1/2 h-4 w-0.5 -translate-x-1/2 bg-current transition-transform group-open:rotate-90"></span>
                </span>
            </summary>
            <!-- eslint-disable-next-line vue/no-v-html -->
            <div class="site-prose site-rich pb-5" v-html="faq.answer"></div>
        </details>
    </div>
</template>
