<script setup lang="ts">
import ContactDetails from '@/components/site/ContactDetails.vue';
import EnquiryForm from '@/components/site/EnquiryForm.vue';
import FaqList from '@/components/site/FaqList.vue';
import ServiceRegister from '@/components/site/ServiceRegister.vue';
import SiteIcon from '@/components/site/SiteIcon.vue';
import TeamList, { type TeamMember } from '@/components/site/TeamList.vue';
import { useSite } from '@/composables/useSite';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    services: Array<{ slug: string; title: string; summary: string | null }>;
    teamMembers: TeamMember[];
    testimonials: Array<{ name: string; position: string | null; company: string | null; text: string }>;
    faqs: Array<{ question: string; answer: string }>;
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
    <Head title="Company registration, tax and VAT services in Chattogram" />

    <PublicLayout current-page="home">
        <section class="py-section">
            <div class="site-container grid items-start gap-x-16 gap-y-12 lg:grid-cols-[minmax(0,7fr)_minmax(0,5fr)]">
                <div>
                    <h1 class="site-display">Company registration, tax and VAT compliance in Chattogram</h1>
                    <p class="site-lead mt-6">
                        {{ site.name }} handles RJSC filings, income tax, VAT and audit support for businesses and individuals in
                        Bangladesh, with clear documentation and confident communication with regulators.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <Link href="/contact#enquiry" class="site-btn site-btn-primary">Send an enquiry</Link>
                        <a v-if="site.phone && site.phone_href" :href="site.phone_href" class="site-btn site-btn-secondary">
                            <SiteIcon name="phone" />
                            Call {{ site.phone }}
                        </a>
                    </div>
                    <p class="mt-5 text-ink-soft">The first consultation is free.</p>
                </div>

                <aside aria-labelledby="hero-office" class="lg:pt-3">
                    <h2 id="hero-office" class="site-heading mb-4">Visit or call the office</h2>
                    <ContactDetails />
                </aside>
            </div>
        </section>

        <section v-if="services.length" class="site-section" aria-labelledby="home-services">
            <div class="site-container">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 id="home-services" class="site-title">What we handle</h2>
                        <p class="site-lead mt-3">Choose a service to see exactly what it covers.</p>
                    </div>
                    <Link href="/services" class="site-link inline-flex min-h-11 items-center">All services</Link>
                </div>
                <ServiceRegister :services="services" class="mt-8" />
            </div>
        </section>

        <section class="site-section bg-mist" aria-labelledby="home-process">
            <div class="site-container">
                <h2 id="home-process" class="site-title">How an engagement runs</h2>
                <ol class="mt-10 grid gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                    <li v-for="(step, index) in steps" :key="step.title" class="border-t-2 border-ink pt-4">
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
                    <TeamList v-if="teamMembers.length" :members="teamMembers.slice(0, 2)" class="mt-10" />
                    <p class="mt-6"><Link href="/about" class="site-link inline-flex min-h-11 items-center">About the firm</Link></p>
                </div>
            </div>
        </section>

        <section v-if="testimonials.length" class="site-section" aria-labelledby="home-testimonials">
            <div class="site-container site-split">
                <h2 id="home-testimonials" class="site-title">What clients say</h2>
                <ul class="space-y-10">
                    <li v-for="testimonial in testimonials" :key="testimonial.name + testimonial.text">
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
                <EnquiryForm />
            </div>
        </section>
    </PublicLayout>
</template>
