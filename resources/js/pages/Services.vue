<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const activeService = ref<string | null>(null);

interface Service {
    id: string;
    title: string;
    shortDescription: string;
    description: string;
    icon: string;
    features: string[];
    image: string;
}

const services: Service[] = [
    {
        id: 'rjsc-limited-company',
        title: 'RJSC Limited Company Services',
        shortDescription: 'Complete incorporation, changes, charges, annual compliance and winding up support.',
        description:
            'Professional support for RJSC matters including drafting constitutional documents (MoA/AoA), end-to-end incorporation of private/public limited companies, director changes, share transfer/allotment, conversion from private to public, mortgage & charge creation/registration, annual return preparation and submission, and full winding up & liquidation formalities.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
        features: [
            'Drafting MoA & AoA tailored to business objectives',
            'Private & Public Limited Company incorporation',
            'Appointment/resignation/removal of directors',
            'Share transfer & share allotment processing',
            'Conversion: Private Limited to Public Limited',
            'Mortgage deed drafting and charge registration',
            'Annual return preparation and submission to RJSC',
            'Winding up & liquidation (including voluntary winding up)',
        ],
        image: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=600&h=400&fit=crop',
    },
    {
        id: 'rjsc-partnership-firm',
        title: 'Partnership Firm Services',
        shortDescription: 'Partnership deed drafting, registration, amendments and dissolution support.',
        description:
            'Complete partnership support including professional partnership deed drafting, RJSC registration, legal handling of amendments (name/address/constitution changes), and managed dissolution with settlement of accounts and formal cancellation of RJSC registration.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        features: [
            'Partnership deed drafting',
            'Partnership firm registration with RJSC',
            'Changes in firm name / office address / constitution',
            'Dissolution management and formal cancellation at RJSC',
        ],
        image: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&h=400&fit=crop',
    },
    {
        id: 'foundation-trust-society',
        title: 'Foundation, Trust & Society Services',
        shortDescription: 'Formation, deed drafting, and annual compliance for welfare entities.',
        description:
            'We assist in establishing and maintaining social welfare entities with high legal precision. Services include foundation registration, trust registration and trust deed drafting (private or charitable trusts), and managing annual return filing and regulatory reporting for trusts and foundations.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
        features: [
            'Foundation registration support',
            'Trust registration and trust deed drafting',
            'Private and charitable trust formation',
            'Annual return filing & regulatory reporting',
        ],
        image: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=400&fit=crop',
    },
    {
        id: 'club-limited-company',
        title: 'Club Limited Company Services',
        shortDescription: 'Section 28 registration, annual return, amendments and winding up support.',
        description:
            'Full support for registering Club Limited Companies as per Section 28 of the Companies Act, 1994, including annual return preparation/submission, amendment support (name/address/management changes), and expert handling of winding up proceedings.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        features: [
            'Registration as per Section 28 (Companies Act, 1994)',
            'Annual return preparation and submission to RJSC',
            'Amendments: name, office address, management modifications',
            'Winding up proceedings management',
        ],
        image: 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=600&h=400&fit=crop',
    },
    {
        id: 'trade-organization',
        title: 'Trade Organization Services',
        shortDescription: 'Section 29 registration, annual return and organization amendments support.',
        description:
            'Full support for Trade Organization registration as per Section 29 of the Companies Act, 1994, including annual return preparation/submission to RJSC and legal handling of amendments and management modifications.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>',
        features: [
            'Registration as per Section 29 (Companies Act, 1994)',
            'Annual return preparation and submission to RJSC',
            'Amendments and organization management modifications',
        ],
        image: 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=600&h=400&fit=crop',
    },
    {
        id: 'income-tax',
        title: 'Income Tax Consultancy & Litigation',
        shortDescription: 'Registration, return preparation, corporate tax, planning and full appeal representation.',
        description:
            'Full-spectrum income tax support for individuals, partnership firms, companies, and trusts under the Income Tax Act, 2023—from e-TIN and compliance to return preparation (all heads of income), asset & liability statement (IT-10B), investment allowance/rebates, corporate withholding tax (TDS/VDS), deferred tax computation, tax planning, and representation through audits, appeals, tribunal and High Court references including ADR.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        features: [
            'E-TIN registration and updates',
            'Tax clearance certificates (visa, bank loans, renewals)',
            'Return preparation across all heads of income',
            'Asset & liability statement (IT-10B)',
            'Investment allowance & tax rebate optimization',
            'Withholding tax (TDS/VDS) advisory and compliance',
            'Tax audits, appeals, tribunal representation and ADR',
            'Specialized support: transfer pricing, gift tax, capital gains, exemptions',
        ],
        image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop',
    },
    {
        id: 'vat',
        title: 'VAT Advisory & Compliance',
        shortDescription: 'BIN registration, monthly returns, VAT books, audits, appeals and refunds.',
        description:
            'Comprehensive VAT management under the VAT and Supplementary Duty Act, 2012. We support BIN registration/amendment, central vs branch registration strategy, price declaration (Mushak-4.3), monthly VAT return filing (Mushak 9.1) and amended returns (Mushak 9.2), VDS monitoring and Mushak 6.6 certificates, mandatory VAT books (Mushak 6.1/6.2/6.3/6.10), audit & investigation representation, demand notice defense, appeals and ADR, plus VAT refund and exemption consultancy.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
        features: [
            'BIN registration and amendment via online VAT portal',
            'Price declaration (Mushak-4.3) preparation/submission',
            'Monthly VAT return (Mushak 9.1) and amended return (Mushak 9.2)',
            'VDS management and Mushak 6.6 certificates',
            'VAT books: Mushak 6.1, 6.2, 6.3 and contractual register (Mushak 6.10)',
            'Audit, intelligence & investigation representation',
            'Appeals, tribunal representation and ADR',
            'Refunds and exemption/zero-rate consultancy',
        ],
        image: 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=600&h=400&fit=crop',
    },
    {
        id: 'audit-support',
        title: 'Audit Support & Financial Assurance',
        shortDescription: 'Internal audit, statutory audit readiness, reporting, and financial statement support.',
        description:
            'We act as a bridge between your business and financial transparency. We provide internal audits and full documentation support to facilitate statutory audits through our Chartered Accountancy partners. Services include audit readiness (lead schedules, reconciliations, supporting documents), liaison with CA firms, internal audit & risk management (system/operational/compliance audit), management reporting (performance analysis, budgetary control), special purpose audits (due diligence, forensic review, inventory & fixed asset verification), and accounts/financial statement preparation in line with IAS/IFRS including consolidation.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>',
        features: [
            'Statutory audit readiness and document preparation',
            'Coordination with Chartered Accountants for timely audit',
            'Internal audit: system, operational and compliance reviews',
            'Risk management and compliance to BFRS',
            'Management audit, performance analysis and reporting',
            'Budgetary control and expenditure monitoring',
            'Due diligence, forensic review, inventory & fixed asset audit',
            'Accounts & financial statement preparation (IAS/IFRS) and consolidation',
        ],
        image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
    },
];

const sidebarServices = computed(() => services.map(s => ({ id: s.id, title: s.title })));

const currentService = computed(() => {
    if (activeService.value) {
        return services.find(s => s.id === activeService.value) || services[0];
    }
    return services[0]; // Default to first service
});

const setActiveService = (id: string) => {
    activeService.value = id;
};


</script>

<template>
    <Head title="Our Services - MBSC Firm" />

    <PublicLayout current-page="services">
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
                            <svg class="h-4 w-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-sm font-medium text-rose-300">Our Services</span>
                        </div>
                        <h1 class="text-4xl font-bold text-white sm:text-5xl lg:text-6xl">
                            Comprehensive
                            <span class="bg-gradient-to-r from-rose-400 to-red-400 bg-clip-text text-transparent"> Legal & Tax </span>
                            Solutions
                        </h1>
                        <p class="mt-6 text-lg leading-relaxed text-slate-300">
                            From tax consultation to corporate law, we provide a full spectrum of professional services designed to protect your interests and drive your business forward.
                        </p>
                    </div>

                    <!-- Service Highlights -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 p-6 transition-all hover:-translate-y-1">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-white">{{ services.length }}</div>
                                <div class="text-sm text-white/80">Service Categories</div>
                            </div>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all hover:-translate-y-1 hover:bg-white/10">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-white">5000<span class="text-rose-400">+</span></div>
                                <div class="text-sm text-slate-400">Clients Served</div>
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
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-white">15<span class="text-rose-400">+</span></div>
                                <div class="text-sm text-slate-400">Years Experience</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wave Divider -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg class="h-12 w-full text-slate-50 sm:h-16" preserveAspectRatio="none" viewBox="0 0 1440 54" fill="currentColor">
                    <path d="M0 22L60 16.7C120 11 240 1.00001 360 0.700012C480 1.00001 600 11 720 22C840 33 960 44 1080 44C1200 44 1320 33 1380 27.7L1440 22V54H1380C1320 54 1200 54 1080 54C960 54 840 54 720 54C600 54 480 54 360 54C240 54 120 54 60 54H0V22Z"/>
                </svg>
            </div>
        </section>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-12">
                <!-- Sidebar -->
                <aside class="lg:col-span-4 xl:col-span-3">
                    <!-- Services List -->
                    <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-zinc-900 to-slate-800 px-5 py-4">
                            <h3 class="font-semibold text-white">Our Services</h3>
                        </div>
                        <nav class="divide-y divide-slate-100">
                            <button
                                v-for="service in sidebarServices"
                                :key="service.id"
                                @click="setActiveService(service.id)"
                                :class="[
                                    'flex w-full items-center justify-between px-5 py-3.5 text-left text-sm transition-all',
                                    currentService.id === service.id
                                        ? 'bg-rose-50 font-medium text-rose-700'
                                        : 'text-slate-700 hover:bg-slate-50 hover:text-rose-600'
                                ]"
                            >
                                <span>{{ service.title }}</span>
                                <svg :class="['h-4 w-4 transition-colors', currentService.id === service.id ? 'text-rose-500' : 'text-slate-400']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </nav>
                    </div>

                 

                    <!-- Need Help Box -->
                    <div class="mt-8 rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-6 shadow-lg">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-red-600 shadow-lg shadow-rose-500/25">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900">Need Help?</h4>
                        <p class="mt-2 text-sm text-slate-600">Please Feel Free To Contact Us. We Will Get Back To You With 1-2 Business Days.</p>
                        <div class="mt-4 space-y-2 text-sm">
                            <a href="mailto:mbscfirm@gmail.com" class="flex items-center gap-2 text-slate-600 transition-colors hover:text-rose-600">
                                <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                mbscfirm@gmail.com
                            </a>
                            <a href="tel:+8801868196716" class="flex items-center gap-2 text-slate-600 transition-colors hover:text-rose-600">
                                <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                +88 01868-196716
                            </a>
                        </div>
                    </div>
                </aside>

                <!-- Main Content Area -->
                <div class="lg:col-span-8 xl:col-span-9">
                    <!-- Service Content -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg sm:p-8 lg:p-10">
                        <div class="mb-8 flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-zinc-900 to-slate-800 text-rose-400 shadow-lg" v-html="currentService.icon"></div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">{{ currentService.title }}</h2>
                                <p class="mt-1 text-slate-500">Professional services tailored to your needs</p>
                            </div>
                        </div>

                        <div class="prose prose-slate max-w-none">
                            <p class="text-base leading-relaxed text-slate-600">{{ currentService.description }}</p>
                            
                            <p class="mt-6 text-base leading-relaxed text-slate-600">
                                Each engagement is handled with clear documentation, compliance-first execution, and proactive updates—so you stay audit-ready and confident with regulators.
                            </p>
                        </div>

                        <!-- Features List -->
                        <div class="mt-8">
                            <h3 class="mb-4 text-lg font-semibold text-slate-900">What We Offer</h3>
                            <ul class="space-y-3">
                                <li v-for="(feature, index) in currentService.features" :key="index" class="flex items-start gap-3">
                                    <div class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-100">
                                        <svg class="h-3 w-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-slate-700">{{ feature }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Service Image -->
                        <div class="mt-10 overflow-hidden rounded-2xl">
                            <img :src="currentService.image" :alt="currentService.title" class="h-64 w-full object-cover sm:h-80 lg:h-96" />
                        </div>

                        <!-- CTA Section -->
                        <div class="mt-10 rounded-2xl bg-gradient-to-r from-zinc-900 to-slate-800 p-6 sm:p-8">
                            <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
                                <div>
                                    <h3 class="text-xl font-semibold text-white">Ready to Get Started?</h3>
                                    <p class="mt-1 text-slate-300">Contact us today for a free consultation on {{ currentService.title }}.</p>
                                </div>
                                <a href="/contact" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-red-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:-translate-y-0.5 hover:shadow-xl">
                                    Contact Us Now
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Related Services -->
                    <div class="mt-10">
                        <h3 class="mb-6 text-xl font-semibold text-slate-900">Other Services You May Need</h3>
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            <button
                                v-for="service in services.filter(s => s.id !== currentService.id).slice(0, 3)"
                                :key="service.id"
                                @click="setActiveService(service.id)"
                                class="group rounded-xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-rose-100"
                            >
                                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-gradient-to-br from-zinc-900 to-slate-800 text-rose-400 shadow" v-html="service.icon"></div>
                                <h4 class="font-semibold text-slate-900 transition-colors group-hover:text-rose-600">{{ service.title }}</h4>
                                <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ service.shortDescription }}</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </PublicLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
