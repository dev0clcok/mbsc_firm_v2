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
        id: 'vat-registration',
        title: 'VAT Registration & Compliance',
        shortDescription: 'Complete VAT registration, return filing, and compliance management.',
        description: 'We provide many kinds of VAT assignment services such as Individual, Corporate, Property, Group etc. We ensure the best quality for calculating your tax using our own tax automation software. So the thing is that according to the law you will get the best advantage to us.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
        features: [
            'Registration of the new e-TIN certificate',
            'Computation of Income tax and Tax Rebate',
            'Preparation, review, submission of corporate income tax return',
            'Advising and attending hearings called by tax authority',
            'Advising quarterly payment of tax',
            'Guidance regarding new tax rules and regulations',
            'All kinds of Tax file Audit, Appeal & Tribunal related works',
        ],
        image: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=600&h=400&fit=crop',
    },
    {
        id: 'individual-taxation',
        title: 'Local Individual Taxation',
        shortDescription: 'Personal tax planning, filing, and compliance services.',
        description: 'Prepare tax registration, return and filling with proper guidance and support thereof in a timely manner at relevant tax zone. Including the following supporting activities.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
        features: [
            'Registration of the new e-TIN certificate',
            'Computation of Income tax and Tax Rebate',
            'Preparation, review, submission of income tax return',
            'Issuing tax withholding certificates along with relevant schedules',
            'Advising and attending hearings by tax authority',
            'Advising quarterly payment of tax',
            'Guidance regarding new tax rules and regulations',
        ],
        image: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&h=400&fit=crop',
    },
    {
        id: 'company-formation',
        title: 'Company Formation',
        shortDescription: 'End-to-end company registration including RJSC and trade license.',
        description: 'We provide comprehensive company formation services including all legal documentation, RJSC registration, and obtaining necessary licenses to start your business legally in Bangladesh.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
        features: [
            'Private Limited Company Registration',
            'Public Limited Company Registration',
            'Partnership Firm Registration',
            'One Person Company (OPC) Registration',
            'Branch Office Setup for Foreign Companies',
            'Liaison Office Establishment',
            'Trade License Procurement',
        ],
        image: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=400&fit=crop',
    },
    {
        id: 'commercial-irc',
        title: 'Commercial IRC',
        shortDescription: 'Import Registration Certificate processing and renewal.',
        description: 'Get your Import Registration Certificate (IRC) with our expert assistance. We handle all documentation and liaison with relevant authorities for smooth processing.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        features: [
            'New IRC Application Processing',
            'IRC Renewal Services',
            'IRC Amendment & Modification',
            'AD (Authorized Dealer) Certificate',
            'Bond License Processing',
            'Import Permit Assistance',
            'Customs Documentation Support',
        ],
        image: 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=600&h=400&fit=crop',
    },
    {
        id: 'foreigner-taxation',
        title: 'Foreigner Individual Taxation',
        shortDescription: 'Tax services for expatriates and foreign nationals.',
        description: 'Specialized tax services for foreign nationals working in Bangladesh, ensuring compliance with local tax laws while optimizing their tax obligations.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        features: [
            'Tax Registration for Expatriates',
            'Double Taxation Avoidance Advisory',
            'Tax Return Filing for Foreign Nationals',
            'Tax Clearance Certificate',
            'Salary Structuring for Tax Efficiency',
            'Repatriation Documentation',
            'Compliance with Immigration Requirements',
        ],
        image: 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=600&h=400&fit=crop',
    },
    {
        id: 'license-certificate',
        title: 'License and Certificate',
        shortDescription: 'All types of business licenses and certifications.',
        description: 'We assist in obtaining various business licenses and certificates required for smooth business operations in Bangladesh.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
        features: [
            'Trade License (New & Renewal)',
            'Fire License',
            'Environment Clearance Certificate',
            'BSTI Certification',
            'ISO Certification Assistance',
            'Factory License',
            'Drug License (for Pharmaceutical)',
        ],
        image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop',
    },
    {
        id: 'rjsc-activities',
        title: 'RJSC Related Activities',
        shortDescription: 'All RJSC documentation and compliance services.',
        description: 'Complete support for all Registrar of Joint Stock Companies and Firms (RJSC) related matters including filings, amendments, and annual compliance.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
        features: [
            'Annual Return Filing',
            'Schedule X Filing',
            'Director Change/Addition',
            'Share Transfer',
            'Registered Office Change',
            'Memorandum & Articles Amendment',
            'Company Name Change',
        ],
        image: 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=600&h=400&fit=crop',
    },
    {
        id: 'corporate-taxation',
        title: 'Public Limited Company Taxation',
        shortDescription: 'Tax services for publicly listed companies.',
        description: 'Comprehensive tax planning and compliance services tailored for public limited companies listed on stock exchanges.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>',
        features: [
            'Corporate Tax Planning',
            'Quarterly Tax Payment Advisory',
            'Tax Return Preparation & Filing',
            'Transfer Pricing Documentation',
            'Tax Audit Support',
            'Dividend Tax Computation',
            'Capital Gains Tax Advisory',
        ],
        image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
    },
    {
        id: 'private-company-taxation',
        title: 'Private Limited Company Taxation',
        shortDescription: 'Complete tax solutions for private companies.',
        description: 'Expert tax services designed specifically for private limited companies to ensure compliance and optimize tax efficiency.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
        features: [
            'Annual Tax Return Filing',
            'Advance Tax Computation',
            'Withholding Tax Compliance',
            'Tax Audit Representation',
            'Tax Planning Strategies',
            'Employee Tax Documentation',
            'Statutory Compliance Review',
        ],
        image: 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&h=400&fit=crop',
    },
    {
        id: 'partnership-taxation',
        title: 'Partnership Company Taxation',
        shortDescription: 'Tax services for partnership firms.',
        description: 'Specialized tax services for partnership firms including profit distribution, partner taxation, and firm compliance requirements.',
        icon: '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        features: [
            'Partnership Deed Drafting',
            'Partner Profit Allocation',
            'Partnership Tax Return',
            'Partner Individual Returns',
            'Registration with Tax Authority',
            'Partnership Dissolution Tax',
            'Capital Account Management',
        ],
        image: 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&h=400&fit=crop',
    },
];

const sidebarServices = computed(() => services.map(s => ({ id: s.id, title: s.title })));

const currentService = computed(() => {
    if (activeService.value) {
        return services.find(s => s.id === activeService.value) || services[1];
    }
    return services[1]; // Default to Local Individual Taxation
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
                            <a href="mailto:info@mbscfirm.com" class="flex items-center gap-2 text-slate-600 transition-colors hover:text-rose-600">
                                <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                info@mbscfirm.com
                            </a>
                            <a href="tel:+8801234567890" class="flex items-center gap-2 text-slate-600 transition-colors hover:text-rose-600">
                                <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                +880 1234-567890
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
                                Prepare tax registration, return and filling with proper guidance and support thereof in a timely manner at relevant tax zone. Including the following supporting activities.
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
