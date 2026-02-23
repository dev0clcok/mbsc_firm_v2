<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const activeTab = ref('mission');

const milestones = [
    { year: '2010', title: 'Foundation', description: 'MBSC Firm was established with a vision to provide comprehensive legal and tax services.' },
    { year: '2013', title: 'First 500 Clients', description: 'Reached our first milestone of serving 500 satisfied clients across Bangladesh.' },
    { year: '2016', title: 'Team Expansion', description: 'Expanded our team to include specialized tax consultants and legal experts.' },
    { year: '2019', title: 'Digital Transformation', description: 'Launched our digital platform for seamless client service delivery.' },
    { year: '2022', title: 'National Recognition', description: 'Awarded Best Tax Consultancy Firm by the Bangladesh Business Awards.' },
    { year: '2025', title: '5000+ Clients', description: 'Proudly serving over 5000 businesses and individuals nationwide.' },
];

interface Props {
    teamMembers: Array<{
        name: string;
        position: string | null;
        specialization: string | null;
        image: string | null;
        social_links: Array<{ platform: string; url: string }>;
    }>;
}

const props = defineProps<Props>();

const team = computed(() => props.teamMembers ?? []);

const values = [
    {
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
        title: 'Integrity',
        description: 'We uphold the highest ethical standards in all our dealings, ensuring transparency and trust.',
    },
    {
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
        title: 'Excellence',
        description: 'We strive for excellence in every service, delivering quality that exceeds expectations.',
    },
    {
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        title: 'Client Focus',
        description: 'Your success is our priority. We tailor solutions to meet your unique business needs.',
    },
    {
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
        title: 'Innovation',
        description: 'We embrace technology and innovative approaches to deliver efficient solutions.',
    },
];

const certifications = [
    'Registered Tax Practitioners',
    'Bangladesh Bar Council Members',
    'ICAB Certified Accountants',
    'RJSC Authorized Representatives',
];
</script>

<template>
    <Head title="About Us - MBSC Firm" />

    <PublicLayout current-page="about">
        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-24">
            <!-- Background -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-slate-900 to-zinc-950"></div>
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                <div class="absolute -left-40 top-0 h-[500px] w-[500px] rounded-full bg-rose-500/10 blur-3xl"></div>
                <div class="absolute -right-40 bottom-0 h-[400px] w-[400px] rounded-full bg-violet-500/10 blur-3xl"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-28 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div>
                        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-rose-500/30 bg-rose-500/10 px-4 py-1.5">
                            <svg class="h-4 w-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm font-medium text-rose-300">Since 2010</span>
                        </div>
                        <h1 class="text-4xl font-bold text-white sm:text-5xl lg:text-6xl">
                            Your Trusted Partner in
                            <span class="bg-gradient-to-r from-rose-400 to-red-400 bg-clip-text text-transparent"> Legal & Tax </span>
                            Excellence
                        </h1>
                        <p class="mt-6 text-lg leading-relaxed text-slate-300">
                            MBSC Firm provides compliance-first professional services in Bangladesh across RJSC matters, Income Tax consultancy & litigation, VAT advisory & compliance, and audit support. We combine precision, documentation discipline, and practical execution so clients stay confident with regulators and stakeholders.
                        </p>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 p-6 transition-all hover:-translate-y-1">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-white">15<span class="text-rose-100">+</span></div>
                                <div class="text-sm text-white/80">Years of Excellence</div>
                            </div>
                            <div class="absolute -bottom-4 -right-4 h-20 w-20 rounded-full bg-white/20 blur-2xl"></div>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all hover:-translate-y-1 hover:bg-white/10">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-white">5000<span class="text-rose-400">+</span></div>
                                <div class="text-sm text-slate-400">Happy Clients</div>
                            </div>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all hover:-translate-y-1 hover:bg-white/10">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-white">98<span class="text-rose-400">%</span></div>
                                <div class="text-sm text-slate-400">Success Rate</div>
                            </div>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all hover:-translate-y-1 hover:bg-white/10">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-white">50<span class="text-rose-400">+</span></div>
                                <div class="text-sm text-slate-400">Expert Professionals</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wave Divider -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg class="h-12 w-full text-white sm:h-16" preserveAspectRatio="none" viewBox="0 0 1440 54" fill="currentColor">
                    <path d="M0 22L60 16.7C120 11 240 1.00001 360 0.700012C480 1.00001 600 11 720 22C840 33 960 44 1080 44C1200 44 1320 33 1380 27.7L1440 22V54H1380C1320 54 1200 54 1080 54C960 54 840 54 720 54C600 54 480 54 360 54C240 54 120 54 60 54H0V22Z"/>
                </svg>
            </div>
        </section>

        <!-- Mission/Vision/Values Tabs -->
        <section id="story" class="py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl">What Drives Us</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-slate-600">Our mission, vision, and values guide everything we do</p>
                </div>

                <!-- Tabs -->
                <div class="mt-12 flex justify-center">
                    <div class="inline-flex rounded-2xl bg-slate-100 p-1.5">
                        <button
                            v-for="tab in ['mission', 'vision', 'values']"
                            :key="tab"
                            @click="activeTab = tab"
                            :class="[
                                'rounded-xl px-6 py-3 text-sm font-semibold transition-all',
                                activeTab === tab
                                    ? 'bg-white text-slate-900 shadow-lg'
                                    : 'text-slate-600 hover:text-slate-900'
                            ]"
                        >
                            {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="mt-12">
                    <Transition name="fade" mode="out-in">
                        <!-- Mission -->
                        <div v-if="activeTab === 'mission'" key="mission" class="mx-auto max-w-4xl">
                            <div class="grid items-center gap-12 lg:grid-cols-2">
                                <div class="relative">
                                    <div class="absolute -inset-4 rounded-3xl bg-gradient-to-r from-rose-100 to-red-100 blur-2xl"></div>
                                    <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=600&h=500&fit=crop" alt="Our Mission" class="relative rounded-3xl shadow-2xl" />
                                </div>
                                <div>
                                    <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-lg">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-slate-900">Our Mission</h3>
                                    <p class="mt-4 text-lg leading-relaxed text-slate-600">
                                        To deliver reliable RJSC, Tax, VAT, and audit support services with strong documentation and compliance discipline—minimizing regulatory risk and enabling clients to focus on growth.
                                    </p>
                                    <ul class="mt-6 space-y-3">
                                        <li class="flex items-center gap-3 text-slate-700">
                                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-100">
                                                <svg class="h-3.5 w-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            Provide expert guidance in RJSC, Income Tax and VAT compliance
                                        </li>
                                        <li class="flex items-center gap-3 text-slate-700">
                                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-100">
                                                <svg class="h-3.5 w-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            Maintain audit-ready documentation and transparent communication
                                        </li>
                                        <li class="flex items-center gap-3 text-slate-700">
                                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-100">
                                                <svg class="h-3.5 w-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            Resolve disputes efficiently through appeals, tribunal and ADR support
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Vision -->
                        <div v-else-if="activeTab === 'vision'" key="vision" class="mx-auto max-w-4xl">
                            <div class="grid items-center gap-12 lg:grid-cols-2">
                                <div class="order-2 lg:order-1">
                                    <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 text-white shadow-lg">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-slate-900">Our Vision</h3>
                                    <p class="mt-4 text-lg leading-relaxed text-slate-600">
                                        To be a trusted standard for compliance and dispute support in Bangladesh—known for strong technical execution in RJSC, Income Tax, VAT, and audit support, and for making complex regulatory processes easier for clients.
                                    </p>
                                    <div class="mt-8 rounded-2xl border border-violet-100 bg-violet-50 p-6">
                                        <p class="text-sm font-medium text-violet-900">
                                            "By 2030, we aim to be the leading consultancy firm in South Asia, known for our innovative solutions and unwavering commitment to client success."
                                        </p>
                                        <p class="mt-2 text-sm text-violet-600">— Mohammad Rahman, Founder</p>
                                    </div>
                                </div>
                                <div class="relative order-1 lg:order-2">
                                    <div class="absolute -inset-4 rounded-3xl bg-gradient-to-r from-violet-100 to-purple-100 blur-2xl"></div>
                                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=500&fit=crop" alt="Our Vision" class="relative rounded-3xl shadow-2xl" />
                                </div>
                            </div>
                        </div>

                        <!-- Values -->
                        <div v-else key="values">
                            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                <div
                                    v-for="(value, index) in values"
                                    :key="value.title"
                                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl"
                                >
                                    <div :class="[
                                        'mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl text-white shadow-lg',
                                        index === 0 ? 'bg-gradient-to-br from-rose-500 to-red-600' :
                                        index === 1 ? 'bg-gradient-to-br from-amber-500 to-orange-600' :
                                        index === 2 ? 'bg-gradient-to-br from-emerald-500 to-teal-600' :
                                        'bg-gradient-to-br from-violet-500 to-purple-600'
                                    ]" v-html="value.icon"></div>
                                    <h4 class="text-lg font-bold text-slate-900">{{ value.title }}</h4>
                                    <p class="mt-2 text-sm text-slate-600">{{ value.description }}</p>
                                    <div class="pointer-events-none absolute -bottom-10 -right-10 h-32 w-32 rounded-full bg-slate-50 transition-all group-hover:bg-slate-100"></div>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </section>

        <!-- Timeline Section -->
        <section class="relative overflow-hidden bg-slate-50 py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl">Our Journey</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-slate-600">Key milestones that shaped who we are today</p>
                </div>

                <div class="relative mt-16">
                    <!-- Timeline Line -->
                    <div class="absolute left-1/2 top-0 hidden h-full w-0.5 -translate-x-1/2 bg-gradient-to-b from-rose-500 via-violet-500 to-slate-300 lg:block"></div>

                    <div class="space-y-12 lg:space-y-0">
                        <div
                            v-for="(milestone, index) in milestones"
                            :key="milestone.year"
                            :class="[
                                'relative lg:flex lg:items-center',
                                index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'
                            ]"
                        >
                            <!-- Content -->
                            <div :class="[
                                'lg:w-1/2',
                                index % 2 === 0 ? 'lg:pr-12 lg:text-right' : 'lg:pl-12'
                            ]">
                                <div :class="[
                                    'rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-lg',
                                    index % 2 === 0 ? 'lg:ml-auto lg:mr-0' : 'lg:ml-0 lg:mr-auto',
                                    'max-w-md'
                                ]">
                                    <div class="mb-2 text-2xl font-bold text-rose-500">{{ milestone.year }}</div>
                                    <h3 class="text-lg font-semibold text-slate-900">{{ milestone.title }}</h3>
                                    <p class="mt-2 text-slate-600">{{ milestone.description }}</p>
                                </div>
                            </div>

                            <!-- Dot -->
                            <div class="absolute left-1/2 hidden h-5 w-5 -translate-x-1/2 rounded-full border-4 border-white bg-rose-500 shadow-lg lg:block"></div>

                            <!-- Spacer -->
                            <div class="hidden lg:block lg:w-1/2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section id="team" class="py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl">Meet Our Leadership</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-slate-600">Experienced professionals dedicated to your success</p>
                </div>

                <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        v-for="member in team"
                        :key="member.name"
                        class="group relative"
                    >
                        <div class="relative overflow-hidden rounded-3xl bg-slate-100">
                            <img :src="member.image || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop&crop=face'" :alt="member.name" class="aspect-[3/4] w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900/90 via-zinc-900/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                            
                            <!-- Social Links -->
                            <div class="absolute bottom-4 left-4 right-4 flex items-center justify-center gap-3 opacity-0 transition-all duration-300 group-hover:opacity-100">
                                <a
                                    v-for="link in (member.social_links || [])"
                                    :key="link.platform + link.url"
                                    :href="link.platform === 'email' ? `mailto:${link.url}` : link.url"
                                    :target="link.platform === 'email' ? undefined : '_blank'"
                                    :rel="link.platform === 'email' ? undefined : 'noopener noreferrer'"
                                    class="rounded-full bg-white/20 p-2 text-white backdrop-blur-sm transition-colors hover:bg-white/30"
                                    :aria-label="`${member.name} on ${link.platform}`"
                                >
                                    <svg v-if="link.platform === 'linkedin'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    <svg v-else-if="link.platform === 'twitter'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    <svg v-else-if="link.platform === 'facebook'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    <svg v-else-if="link.platform === 'instagram'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    <svg v-else-if="link.platform === 'youtube'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    <svg v-else-if="link.platform === 'email'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.172-1.171 4.828-4.828a4 4 0 00-5.656-5.656l-4 4"/></svg>
                                </a>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <h3 class="text-lg font-bold text-slate-900">{{ member.name }}</h3>
                            <p class="text-sm font-medium text-rose-600">{{ member.position }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ member.specialization }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Certifications -->
        <section class="bg-gradient-to-r from-zinc-900 to-slate-900 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-8 lg:flex-row">
                    <div>
                        <h3 class="text-2xl font-bold text-white">Our Certifications & Affiliations</h3>
                        <p class="mt-2 text-slate-400">Recognized and certified by leading regulatory bodies</p>
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-4 lg:justify-end">
                        <div
                            v-for="cert in certifications"
                            :key="cert"
                            class="rounded-full border border-white/20 bg-white/5 px-5 py-2.5 text-sm font-medium text-white backdrop-blur-sm"
                        >
                            {{ cert }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-3xl">
                    <div class="absolute inset-0">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80" alt="" class="h-full w-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-r from-zinc-900/95 to-zinc-900/80"></div>
                    </div>

                    <div class="relative px-6 py-16 text-center sm:px-12 sm:py-24">
                        <h2 class="text-3xl font-bold text-white sm:text-4xl">Ready to Work Together?</h2>
                        <p class="mx-auto mt-4 max-w-xl text-lg text-slate-300">
                            Join thousands of satisfied clients who trust MBSC Firm for their legal and tax needs.
                        </p>
                        <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                            <a href="/contact" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-red-600 px-8 py-4 text-sm font-semibold text-white shadow-lg shadow-rose-500/30 transition-all hover:-translate-y-0.5 hover:shadow-xl">
                                Schedule a Consultation
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                            <Link href="/services" class="inline-flex items-center gap-2 rounded-xl border-2 border-white/20 bg-white/5 px-8 py-4 text-sm font-semibold text-white backdrop-blur-sm transition-all hover:bg-white/10">
                                Explore Services
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(10px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
