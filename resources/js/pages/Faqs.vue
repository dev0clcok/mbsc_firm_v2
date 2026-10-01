<script setup lang="ts">
import CtaBand from '@/components/site/CtaBand.vue';
import FaqList, { type Faq } from '@/components/site/FaqList.vue';
import SiteBreadcrumb from '@/components/site/SiteBreadcrumb.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    groups: Array<{ title: string; slug: string | null; faqs: Faq[] }>;
}>();

const query = ref('');

const plain = (html: string) => html.replace(/<[^>]+>/g, ' ');

const filtered = computed(() => {
    const words = query.value.toLowerCase().split(/\s+/).filter(Boolean);
    if (!words.length) return props.groups;

    return props.groups
        .map((group) => ({
            ...group,
            faqs: group.faqs.filter((faq) => {
                const text = `${faq.question} ${plain(faq.answer)}`.toLowerCase();
                return words.every((word) => text.includes(word));
            }),
        }))
        .filter((group) => group.faqs.length);
});

const count = computed(() => filtered.value.reduce((total, group) => total + group.faqs.length, 0));
</script>

<template>
    <Head title="Frequently asked questions" />

    <PublicLayout current-page="faqs">
        <section class="pt-8 pb-section">
            <div class="site-container">
                <SiteBreadcrumb :items="[{ label: 'FAQs' }]" />
                <h1 class="site-display mt-6">Frequently asked questions</h1>
                <p class="site-lead mt-6"><template v-if="groups.length > 1">Answers grouped by service. </template>If yours is not here, <Link href="/contact#enquiry" class="site-link">ask us directly</Link>.</p>

                <div v-if="groups.length" class="mt-10 max-w-xl">
                    <label for="faq-search" class="site-label">Search the questions</label>
                    <input id="faq-search" v-model="query" type="search" class="site-input" autocomplete="off" aria-describedby="faq-search-count" />
                    <p id="faq-search-count" class="mt-2 text-sm text-ink-soft" role="status">
                        <template v-if="query">{{ count }} {{ count === 1 ? 'question matches' : 'questions match' }}</template>
                    </p>
                </div>

                <div v-if="filtered.length" class="mt-10 space-y-14">
                    <section v-for="group in filtered" :key="group.title" class="site-split" :aria-labelledby="`faq-group-${group.slug ?? 'general'}`">
                        <div>
                            <h2 :id="`faq-group-${group.slug ?? 'general'}`" class="site-title">{{ group.title }}</h2>
                            <p v-if="group.slug" class="mt-2">
                                <Link :href="`/services/${group.slug}`" class="site-link inline-flex min-h-11 items-center">About this service</Link>
                            </p>
                        </div>
                        <FaqList :faqs="group.faqs" />
                    </section>
                </div>
                <p v-else-if="groups.length" class="site-prose mt-10">
                    No question matches "{{ query }}". Try fewer words, or <Link href="/contact#enquiry" class="site-link">send us your question</Link>.
                </p>
                <p v-else class="site-prose mt-10">Questions and answers will be added here soon.</p>
            </div>
        </section>

        <CtaBand title="Still have a question?" text="Send it to us and we will answer directly. The first consultation is free." />
    </PublicLayout>
</template>
