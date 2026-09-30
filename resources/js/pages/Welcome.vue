<script setup lang="ts">
import EnquiryForm from '@/components/site/EnquiryForm.vue';
import FaqList, { type Faq } from '@/components/site/FaqList.vue';
import ServiceGrid, { type ServiceSummary } from '@/components/site/ServiceGrid.vue';
import SiteIcon from '@/components/site/SiteIcon.vue';
import SiteImage from '@/components/site/SiteImage.vue';
import TeamList, { type TeamMember } from '@/components/site/TeamList.vue';
import WhoWeHelp from '@/components/site/WhoWeHelp.vue';
import { useSite } from '@/composables/useSite';
import { vReveal } from '@/directives/reveal';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { SiteImageData } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    /** Browser-tab title, editable in site settings. */
    pageTitle: string;
    hero: SiteImageData | null;
    services: ServiceSummary[];
    teamMembers: TeamMember[];
    testimonials: Array<{ name: string; position: string | null; company: string | null; text: string }>;
    faqs: Faq[];
}>();

const site = useSite();

const steps = [
    { title: 'Consultation', description: 'A free first consultation to understand what you need.' },
    { title: 'Documentation', description: 'We collect and prepare all required documents.' },
    { title: 'Processing', description: 'We file and follow up your application with the authority.' },
    { title: 'Delivery', description: 'You receive the result on time, with ongoing support.' },
];
</script>

<template>
    <Head :title="pageTitle" />

    <PublicLayout current-page="home">
        <section class="site-on-ink bg-ink text-white">
            <div :class="['site-container grid items-center gap-x-14 gap-y-10 pt-section pb-12', hero && 'lg:grid-cols-[minmax(0,6fr)_minmax(0,5fr)]']">
                <div>
                    <h1 class="font-display text-display font-medium tracking-tight text-balance">Expert legal and tax solutions for your business</h1>
                    <p class="mt-6 max-w-[58ch] text-lead text-white/80">
                        RJSC, income tax, VAT and audit support in Bangladesh, built for compliance-first execution, clear documentation
                        and confident communication with regulators.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <Link href="/contact#enquiry" class="site-btn site-btn-primary">Send an enquiry</Link>
                        <a v-if="site.phone && site.phone_href" :href="site.phone_href" class="site-btn site-btn-on-ink">
                            <SiteIcon name="phone" />
                            Call {{ site.phone }}
                        </a>
                    </div>
                </div>

                <div v-if="hero" class="aspect-[4/3] overflow-hidden rounded-card ring-1 ring-white/15">
                    <SiteImage :image="hero" eager sizes="(min-width: 1024px) 45vw, 72vw" />
                </div>
            </div>

            <div class="site-container pb-12">
                <div class="flex flex-wrap justify-between gap-x-10 gap-y-2 border-t border-white/15 pt-5 font-semibold text-white/85">
                    <p>RJSC · Income tax · VAT · Audit</p>
                    <p>Free first consultation</p>
                </div>
            </div>
        </section>

        <WhoWeHelp />

        <section v-if="services.length" class="site-section" aria-labelledby="home-services">
            <div class="site-container">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 id="home-services" class="site-title">What we handle</h2>
                        <p class="site-lead mt-3">Choose a service to see exactly what it covers.</p>
                    </div>
                    <Link v-if="services.length > 6" href="/services" class="site-link inline-flex min-h-11 items-center">All {{ services.length }} services</Link>
                </div>
                <ServiceGrid :services="services.slice(0, 6)" class="mt-10" />
            </div>
        </section>

        <section class="site-section bg-mist" aria-labelledby="home-process">
            <div class="site-container">
                <h2 id="home-process" class="site-title">How an engagement runs</h2>
                <ol class="mt-10 grid gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                    <li v-for="(step, index) in steps" :key="step.title" v-reveal class="border-t-2 border-ink pt-4">
                        <span class="font-display text-4xl font-medium text-rose" aria-hidden="true">{{ index + 1 }}</span>
                        <h3 class="site-heading mt-2">{{ step.title }}</h3>
                        <p class="mt-1 text-ink-soft">{{ step.description }}</p>
                    </li>
                </ol>
            </div>
        </section>

        <section class="site-section" aria-labelledby="home-about">
            <div class="site-container site-split">
                <div>
                    <h2 id="home-about" class="site-title">A compliance-first practice</h2>
                </div>
                <div>
                    <p class="site-prose text-lead">
                        {{ site.name }} provides professional services in Bangladesh across RJSC matters, income tax consultancy and
                        litigation, VAT advisory and compliance, and audit support. We combine precision, documentation discipline and
                        practical execution so clients stay confident with regulators and stakeholders.
                    </p>
                    <TeamList v-if="teamMembers.length" v-reveal :members="teamMembers.slice(0, 1)" class="mt-10" />
                    <p class="mt-6"><Link href="/about" class="site-link inline-flex min-h-11 items-center">About the firm</Link></p>
                </div>
            </div>
        </section>

        <section v-if="testimonials.length" class="site-section" aria-labelledby="home-testimonials">
            <div class="site-container site-split">
                <h2 id="home-testimonials" class="site-title">What clients say</h2>
                <ul class="space-y-6">
                    <li v-for="testimonial in testimonials" :key="testimonial.name + testimonial.text" v-reveal class="site-card p-6">
                        <figure>
                            <blockquote class="font-display text-xl leading-relaxed text-ink">{{ testimonial.text }}</blockquote>
                            <figcaption class="mt-3 text-ink-soft">
                                <span class="font-semibold text-ink">{{ testimonial.name }}</span>
                                <template v-if="testimonial.position || testimonial.company">
                                    , {{ [testimonial.position, testimonial.company].filter(Boolean).join(', ') }}
                                </template>
                            </figcaption>
                        </figure>
                    </li>
                </ul>
            </div>
        </section>

        <section v-if="faqs.length" class="site-section" aria-labelledby="home-faq">
            <div class="site-container site-split">
                <div>
                    <h2 id="home-faq" class="site-title">Common questions</h2>
                    <p class="site-lead mt-3">
                        If yours is not here, <Link href="/contact#enquiry" class="site-link">ask us directly</Link>.
                    </p>
                    <p class="mt-4"><Link href="/faqs" class="site-link inline-flex min-h-11 items-center">All questions</Link></p>
                </div>
                <FaqList :faqs="faqs" />
            </div>
        </section>

        <section id="enquiry" class="site-section scroll-mt-20 bg-mist" aria-labelledby="home-enquiry">
            <div class="site-container site-split">
                <div>
                    <h2 id="home-enquiry" class="site-title">Tell us what you need</h2>
                    <p class="site-lead mt-3">
                        Describe your situation in a few lines.
                        <template v-if="site.response_time">We reply {{ site.response_time }}.</template>
                    </p>
                </div>
                <div class="site-card p-6 sm:p-8">
                    <EnquiryForm />
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
