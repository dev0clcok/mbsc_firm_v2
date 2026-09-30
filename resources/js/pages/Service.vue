<script setup lang="ts">
import ContactDetails from '@/components/site/ContactDetails.vue';
import EnquiryForm from '@/components/site/EnquiryForm.vue';
import SiteIcon from '@/components/site/SiteIcon.vue';
import SiteImage from '@/components/site/SiteImage.vue';
import { useSite } from '@/composables/useSite';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { SiteImageData } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    service: {
        slug: string;
        title: string;
        summary: string | null;
        description: string | null;
        features: string[];
        process_steps: Array<{ title: string; description: string }>;
        documents: string[];
        timeline: string | null;
        fees: string | null;
        /** Trusted SVG markup entered in the admin panel. */
        icon: string | null;
        image: SiteImageData | null;
    };
}>();

const site = useSite();

const otherServices = computed(() => site.value.services.filter((s) => s.slug !== props.service.slug));
</script>

<template>
    <Head :title="service.title" />

    <PublicLayout current-page="services">
        <article>
            <header class="pt-8 pb-section">
                <div class="site-container">
                    <nav aria-label="Breadcrumb">
                        <ol class="flex flex-wrap items-center gap-2 text-sm text-ink-soft">
                            <li><Link href="/services" class="site-link inline-flex min-h-11 items-center">Services</Link></li>
                            <li aria-hidden="true">/</li>
                            <li aria-current="page">{{ service.title }}</li>
                        </ol>
                    </nav>
                    <div :class="['mt-8 grid items-center gap-x-14 gap-y-10', service.image && 'lg:grid-cols-2']">
                        <div>
                            <!-- eslint-disable-next-line vue/no-v-html -->
                            <span v-if="service.icon" class="site-icon-tile mb-5" v-html="service.icon"></span>
                            <h1 class="site-display">{{ service.title }}</h1>
                            <p v-if="service.summary" class="site-lead mt-6">{{ service.summary }}</p>
                            <p class="mt-8"><a href="#enquiry" class="site-btn site-btn-primary">Ask about this service</a></p>
                        </div>
                        <div v-if="service.image" class="site-figure aspect-[3/2]">
                            <SiteImage :image="service.image" eager sizes="(min-width: 1024px) 50vw, 72vw" />
                        </div>
                    </div>
                </div>
            </header>

            <div class="site-section">
                <div class="site-container grid gap-x-16 gap-y-12 lg:grid-cols-[minmax(0,7fr)_minmax(0,4fr)]">
                    <div>
                        <p v-if="service.description" class="site-prose text-lead">{{ service.description }}</p>
                        <p class="site-prose mt-5">
                            Each engagement is handled with clear documentation, compliance-first execution, and proactive updates, so
                            you stay audit-ready and confident with regulators.
                        </p>

                        <template v-if="service.features.length">
                            <h2 class="site-title mt-12">What this covers</h2>
                            <ul class="mt-6 divide-y divide-rule border-y border-rule">
                                <li v-for="feature in service.features" :key="feature" class="flex gap-3 py-3.5">
                                    <span class="mt-0.5 text-rose"><SiteIcon name="check" /></span>
                                    {{ feature }}
                                </li>
                            </ul>
                        </template>

                        <template v-if="service.process_steps.length">
                            <h2 class="site-title mt-14">How it works</h2>
                            <ol class="mt-6 space-y-6">
                                <li v-for="(step, index) in service.process_steps" :key="step.title" class="flex gap-5">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-ink font-display text-lg font-medium text-white" aria-hidden="true">{{ index + 1 }}</span>
                                    <div>
                                        <h3 class="site-heading">{{ step.title }}</h3>
                                        <p v-if="step.description" class="mt-1 text-ink-soft">{{ step.description }}</p>
                                    </div>
                                </li>
                            </ol>
                        </template>

                        <template v-if="service.documents.length">
                            <h2 class="site-title mt-14">Documents you'll need</h2>
                            <ul class="mt-6 grid gap-x-8 sm:grid-cols-2">
                                <li v-for="document in service.documents" :key="document" class="flex gap-3 border-b border-rule py-3.5">
                                    <span class="mt-0.5 text-rose"><SiteIcon name="check" /></span>
                                    {{ document }}
                                </li>
                            </ul>
                        </template>

                        <div v-if="service.timeline || service.fees" class="mt-14 grid gap-6 sm:grid-cols-2">
                            <section v-if="service.timeline" class="site-card p-6" aria-labelledby="service-timeline">
                                <h2 id="service-timeline" class="site-heading">Typical timeline</h2>
                                <p class="mt-2 whitespace-pre-line text-ink-soft">{{ service.timeline }}</p>
                            </section>
                            <section v-if="service.fees" class="site-card p-6" aria-labelledby="service-fees">
                                <h2 id="service-fees" class="site-heading">Fees</h2>
                                <p class="mt-2 whitespace-pre-line text-ink-soft">{{ service.fees }}</p>
                            </section>
                        </div>
                    </div>

                    <aside v-if="otherServices.length" aria-labelledby="other-services" class="site-card self-start p-6">
                        <h2 id="other-services" class="site-heading">Other services</h2>
                        <ul class="mt-3 divide-y divide-rule border-t border-rule">
                            <li v-for="other in otherServices" :key="other.slug">
                                <Link :href="`/services/${other.slug}`" class="flex min-h-12 items-center py-2 font-medium text-ink transition-colors hover:text-rose">
                                    {{ other.title }}
                                </Link>
                            </li>
                        </ul>
                    </aside>
                </div>
            </div>
        </article>

        <section id="enquiry" class="site-section scroll-mt-20 bg-mist" aria-labelledby="service-enquiry">
            <div class="site-container site-split">
                <div>
                    <h2 id="service-enquiry" class="site-title">Ask about this service</h2>
                    <p class="site-lead mt-3 mb-8">The first consultation is free.</p>
                    <ContactDetails />
                </div>
                <div class="site-card p-6 sm:p-8">
                    <EnquiryForm :service="service.title" />
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
