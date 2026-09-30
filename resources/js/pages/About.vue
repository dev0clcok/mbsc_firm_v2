<script setup lang="ts">
import CtaBand from '@/components/site/CtaBand.vue';
import SiteIcon from '@/components/site/SiteIcon.vue';
import SiteImage from '@/components/site/SiteImage.vue';
import TeamList, { type TeamMember } from '@/components/site/TeamList.vue';
import { useSite } from '@/composables/useSite';
import { vReveal } from '@/directives/reveal';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { SiteImageData } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps<{ hero: SiteImageData | null; teamMembers: TeamMember[] }>();

const site = useSite();

const commitments = [
    'Provide expert guidance in RJSC, income tax and VAT compliance',
    'Maintain audit-ready documentation and transparent communication',
    'Resolve disputes efficiently through appeals, tribunal and ADR support',
];

const values = [
    { title: 'Integrity', description: 'We uphold the highest ethical standards in all our dealings, ensuring transparency and trust.' },
    { title: 'Excellence', description: 'We strive for excellence in every service, delivering quality that exceeds expectations.' },
    { title: 'Client focus', description: 'Your success is our priority. We tailor solutions to meet your unique business needs.' },
    { title: 'Innovation', description: 'We embrace technology and innovative approaches to deliver efficient solutions.' },
];
</script>

<template>
    <Head title="About the firm" />

    <PublicLayout current-page="about">
        <section class="py-section">
            <div :class="['site-container grid items-center gap-x-14 gap-y-10', hero && 'lg:grid-cols-2']">
                <div>
                    <h1 class="site-display">A compliance-first practice in Chattogram</h1>
                    <p class="site-lead mt-6">
                        {{ site.name }} provides professional services in Bangladesh across RJSC matters, income tax consultancy and
                        litigation, VAT advisory and compliance, and audit support. We combine precision, documentation discipline and
                        practical execution so clients stay confident with regulators and stakeholders.
                    </p>
                </div>
                <div v-if="hero" class="site-figure aspect-[3/2]">
                    <SiteImage :image="hero" eager sizes="(min-width: 1024px) 50vw, 72vw" />
                </div>
            </div>
        </section>

        <section class="site-section" aria-labelledby="about-mission">
            <div class="site-container site-split">
                <h2 id="about-mission" class="site-title">Our mission</h2>
                <div>
                    <p class="site-prose text-lead">
                        To deliver reliable RJSC, tax, VAT and audit support services with strong documentation and compliance
                        discipline, minimising regulatory risk and enabling clients to focus on growth.
                    </p>
                    <ul class="mt-6 divide-y divide-rule border-y border-rule">
                        <li v-for="commitment in commitments" :key="commitment" class="flex gap-3 py-3.5">
                            <span class="mt-0.5 text-rose"><SiteIcon name="check" /></span>
                            {{ commitment }}
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="site-section" aria-labelledby="about-vision">
            <div class="site-container site-split">
                <h2 id="about-vision" class="site-title">Our vision</h2>
                <p class="site-prose text-lead">
                    To be a trusted standard for compliance and dispute support in Bangladesh, known for strong technical execution in
                    RJSC, income tax, VAT and audit support, and for making complex regulatory processes easier for clients.
                </p>
            </div>
        </section>

        <section class="site-section bg-mist" aria-labelledby="about-values">
            <div class="site-container site-split">
                <h2 id="about-values" class="site-title">What we hold to</h2>
                <dl class="grid gap-x-10 gap-y-8 sm:grid-cols-2">
                    <div v-for="value in values" :key="value.title" v-reveal class="border-t-2 border-ink pt-4">
                        <dt class="site-heading">{{ value.title }}</dt>
                        <dd class="mt-1 text-ink-soft">{{ value.description }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        <section v-if="teamMembers.length" class="site-section" aria-labelledby="about-people">
            <div class="site-container site-split">
                <h2 id="about-people" class="site-title">People</h2>
                <TeamList :members="teamMembers" />
            </div>
        </section>

        <CtaBand title="Talk to us about your matter" text="The first consultation is free." />
    </PublicLayout>
</template>
