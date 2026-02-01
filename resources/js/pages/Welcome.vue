<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const activeAccordion = ref<number | null>(null);

const toggleAccordion = (index: number) => {
    activeAccordion.value = activeAccordion.value === index ? null : index;
};

const services = [
    {
        icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
        title: 'VAT Advisory & Compliance',
        description: 'BIN registration, Mushak filings, VAT books, audit support and appeals under VAT & SD Act, 2012.',
    },
    {
        icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
        title: 'RJSC Limited Company Services',
        description: 'MoA/AoA, incorporation, director/share changes, charges, annual return, and winding up support.',
    },
    {
        icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        title: 'Income Tax Consultancy & Litigation',
        description: 'e-TIN, returns (all heads), IT-10B, corporate tax, audits, appeals/tribunal, and ADR support.',
    },
    {
        icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
        title: 'Foundation, Trust & Society',
        description: 'Registration, trust deed drafting, and annual compliance for social welfare entities.',
    },
    {
        icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>',
        title: 'Partnership Firm Services',
        description: 'Partnership deed, RJSC registration, amendments, and dissolution support.',
    },
    {
        icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>',
        title: 'Audit Support & Financial Assurance',
        description: 'Internal audit, statutory audit readiness, reporting and IAS/IFRS financial statements support.',
    },
];

const stats = [
    { value: '500+', label: 'Clients Served' },
    { value: '98%', label: 'Success Rate' },
    { value: '15+', label: 'Years Experience' },
    { value: '50+', label: 'Expert Team' },
];

const testimonials = [
    {
        name: 'Mohammad Rahman',
        position: 'CEO',
        company: 'TechStart BD',
        text: 'Outstanding service! They handled our VAT compliance with utmost professionalism. Highly recommended for any business.',
        rating: 5,
    },
    {
        name: 'Fatima Ahmed',
        position: 'Director',
        company: 'Green Exports Ltd',
        text: 'The team\'s expertise in IRC/ERC processing saved us weeks of work. Their knowledge of regulations is exceptional.',
        rating: 5,
    },
    {
        name: 'Karim Hassan',
        position: 'Managing Director',
        company: 'Dhaka Industries',
        text: 'Professional, responsive, and knowledgeable. They made our BIDA registration process smooth and hassle-free.',
        rating: 5,
    },
];

const faqs = [
    {
        question: 'What documents are required for company registration?',
        answer: 'For company registration, you typically need NID copies of directors, passport-size photos, proposed company names, memorandum of association, and proof of registered office address.',
    },
    {
        question: 'How long does VAT registration take?',
        answer: 'VAT registration usually takes 3-7 working days after submission of all required documents. We ensure expedited processing for urgent requirements.',
    },
    {
        question: 'Do you provide services for foreign companies?',
        answer: 'Yes, we specialize in assisting foreign companies with BIDA registration, branch office setup, liaison office establishment, and all regulatory compliance requirements.',
    },
    {
        question: 'What are your consultation fees?',
        answer: 'We offer free initial consultations. Our service fees vary based on the complexity and scope of work. Contact us for a detailed quotation tailored to your needs.',
    },
    {
        question: 'Do you provide ongoing compliance support?',
        answer: 'Yes, we offer comprehensive ongoing support including VAT return filing, tax compliance, annual filings, and regulatory updates to keep your business compliant.',
    },
];

const processSteps = [
    { step: '1', title: 'Consultation', description: 'Free initial consultation to understand your needs.' },
    { step: '2', title: 'Documentation', description: 'We collect and prepare all required documents.' },
    { step: '3', title: 'Processing', description: 'Expert handling of your application.' },
    { step: '4', title: 'Delivery', description: 'Timely delivery with ongoing support.' },
];

const whyChooseUs = [
    { title: 'Experienced Team', description: '15+ years of industry expertise.' },
    { title: 'Fast Turnaround', description: 'Quick processing without delays.' },
    { title: 'Transparent Pricing', description: 'No hidden fees or surprises.' },
    { title: 'Dedicated Support', description: 'Personal assistance throughout.' },
];

const teamMembers = [
    { name: 'Mr. Rahman', position: 'Managing Partner', specialization: 'Corporate Law & Taxation' },
    { name: 'Ms. Fatima', position: 'Senior Consultant', specialization: 'VAT & Customs' },
    { name: 'Mr. Hossain', position: 'Legal Advisor', specialization: 'Business Registration' },
];

const blogPosts = [
    { title: 'New VAT Regulations for 2026', category: 'Tax Updates', date: 'January 20, 2026' },
    { title: 'Guide to Company Formation in Bangladesh', category: 'Business Guide', date: 'January 15, 2026' },
    { title: 'Understanding BIDA Requirements', category: 'Investment', date: 'January 10, 2026' },
];
</script>

<template>
    <Head title="MBSC Firm - Legal & Tax Consultancy" />

    <PublicLayout current-page="home">
        <!-- Hero Section -->
            <section class="relative min-h-[90vh] overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80" alt="" class="h-full w-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-r from-zinc-950/95 via-slate-900/90 to-zinc-950/80"></div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute right-1/4 top-20 h-72 w-72 rounded-full bg-rose-500/10 blur-3xl"></div>
                    <div class="absolute bottom-20 left-1/4 h-64 w-64 rounded-full bg-red-500/10 blur-3xl"></div>
                </div>
                
                <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-28 lg:px-8 lg:py-32">
                    <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
                        <div>
                            <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-rose-400/30 bg-rose-500/10 px-4 py-2 backdrop-blur-sm">
                                <span class="relative flex h-2 w-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-rose-400"></span>
                                </span>
                                <span class="text-sm font-medium text-rose-300">Trusted by 500+ Businesses in Bangladesh</span>
                            </div>
                            <h1 class="text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl">
                                Expert 
                                <span class="bg-gradient-to-r from-rose-400 to-red-400 bg-clip-text text-transparent">Legal</span> & 
                                <span class="bg-gradient-to-r from-rose-400 to-pink-400 bg-clip-text text-transparent">Tax</span> 
                                Solutions for Your Business
                            </h1>
                            <p class="mt-6 text-lg leading-relaxed text-slate-300">
                                RJSC, Income Tax, VAT, and Audit Support services in Bangladesh—built for compliance-first execution, clear documentation, and confident regulatory communication.
                            </p>
                            <div class="mt-8 flex flex-wrap items-center gap-4">
                                <a href="/#" class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-red-600 px-7 py-4 text-base font-semibold text-white shadow-lg shadow-gray-900/30 transition-all hover:-translate-y-0.5 hover:shadow-xl hover:shadow-rose-500/40">
                                    Free Consultation
                                    <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                <a href="tel:+8801868196716" class="inline-flex items-center gap-2 rounded-xl border-2 border-white/20 bg-white/5 px-6 py-3.5 text-base font-medium text-white backdrop-blur-sm transition-all hover:border-rose-400/40 hover:bg-white/10">
                                    <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    +88 01868-196716
                                </a>
                            </div>
                            
                            <!-- Stats Row -->
                            <div class="mt-12 grid grid-cols-3 gap-6 rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-rose-400">500+</div>
                                    <div class="mt-1 text-sm text-slate-400">Happy Clients</div>
                                </div>
                                <div class="border-x border-white/10 text-center">
                                    <div class="text-3xl font-bold text-rose-400">98%</div>
                                    <div class="mt-1 text-sm text-slate-400">Success Rate</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-rose-400">15+</div>
                                    <div class="mt-1 text-sm text-slate-400">Years Exp.</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hero Images Grid -->
                        <div class="relative hidden lg:block">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Main Large Image -->
                                <div class="col-span-2 overflow-hidden rounded-2xl shadow-2xl">
                                    <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=800&h=400&fit=crop" alt="Team Meeting" class="h-56 w-full object-cover" />
                                </div>
                                <!-- Bottom Left Image -->
                                <div class="overflow-hidden rounded-2xl shadow-xl">
                                    <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=400&h=300&fit=crop" alt="Tax Documents" class="h-40 w-full object-cover" />
                                </div>
                                <!-- Bottom Right Image -->
                                <div class="overflow-hidden rounded-2xl shadow-xl">
                                    <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=400&h=300&fit=crop" alt="Business Handshake" class="h-40 w-full object-cover" />
                                </div>
                            </div>
                            
                            <!-- Floating Callback Card -->
                            <div class="absolute -bottom-8 -left-8 w-72 rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl">
                                <div class="mb-4 flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-zinc-900 to-slate-800">
                                        <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900">Quick Callback</h3>
                                        <p class="text-xs text-slate-500">Response within 2 hours</p>
                                    </div>
                                </div>
                                <form class="space-y-3">
                                    <input type="text" placeholder="Your name" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-rose-500 focus:outline-none" />
                                    <input type="tel" placeholder="Phone number" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-rose-500 focus:outline-none" />
                                    <button type="submit" disabled class="w-full rounded-lg bg-gradient-to-r from-zinc-900 to-slate-800 py-2.5 text-sm font-medium text-white transition-shadow hover:shadow-lg">
                                        Request Callback
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Floating Rating Badge -->
                            <div class="absolute -right-4 top-8 flex items-center gap-3 rounded-xl bg-white px-4 py-3 shadow-xl">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-100">
                                    <svg class="h-5 w-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">4.9/5 Rating</div>
                                    <div class="text-xs text-slate-500">200+ Reviews</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

      
            <!-- Services Section -->
            <section id="services" class="relative py-20 sm:py-24">
                <!-- Background -->
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-gradient-to-b from-slate-50 via-white to-slate-50"></div>
                    <div class="absolute left-0 top-1/4 h-96 w-96 rounded-full bg-rose-100/50 blur-3xl"></div>
                    <div class="absolute right-0 bottom-1/4 h-96 w-96 rounded-full bg-red-100/50 blur-3xl"></div>
                </div>
                
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid items-center gap-12 lg:grid-cols-2">
                        <!-- Left: Image Grid -->
                        <div class="relative hidden lg:block">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="overflow-hidden rounded-2xl shadow-lg">
                                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&h=300&fit=crop" alt="Tax Planning" class="h-48 w-full object-cover transition-transform duration-500 hover:scale-110" />
                                    </div>
                                    <div class="overflow-hidden rounded-2xl shadow-lg">
                                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=400&h=400&fit=crop" alt="Business Professional" class="h-56 w-full object-cover transition-transform duration-500 hover:scale-110" />
                                    </div>
                                </div>
                                <div class="space-y-4 pt-8">
                                    <div class="overflow-hidden rounded-2xl shadow-lg">
                                        <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=400&h=400&fit=crop" alt="Office Meeting" class="h-56 w-full object-cover transition-transform duration-500 hover:scale-110" />
                                    </div>
                                    <div class="overflow-hidden rounded-2xl shadow-lg">
                                        <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=400&h=300&fit=crop" alt="Legal Documents" class="h-48 w-full object-cover transition-transform duration-500 hover:scale-110" />
                                    </div>
                                </div>
                            </div>
                            <!-- Floating Stats -->
                            <div class="absolute -right-6 bottom-12 rounded-xl bg-white p-4 shadow-xl">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-100">
                                        <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-slate-900">2,500+</div>
                                        <div class="text-sm text-slate-500">Cases Resolved</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right: Content -->
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-1.5 text-sm font-medium text-rose-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                What We Offer
                            </span>
                            <h2 class="mt-4 text-3xl font-bold text-slate-900 sm:text-4xl">Explore Our Range of Expert Legal Solutions</h2>
                            <p class="mt-4 text-lg text-slate-600">At MBSC Firm, we simplify the complexities of doing business in Bangladesh by offering expert support.</p>
                        </div>
                    </div>

                    <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div v-for="(service, index) in services" :key="index" class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-rose-100">
                            <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-gradient-to-br from-rose-100 to-slate-100 opacity-0 transition-opacity group-hover:opacity-100"></div>
                            <div class="relative">
                                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-zinc-900 to-slate-800 text-rose-400 shadow-lg shadow-slate-300" v-html="service.icon"></div>
                                <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ service.title }}</h3>
                                <p class="text-sm text-slate-600">{{ service.description }}</p>
                                <a href="#" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-rose-600 transition-colors hover:text-rose-700">
                                    Learn more
                                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
      <!-- Trusted Partners Banner -->
            <section class="relative overflow-hidden py-10 bg-red-50">
                <!-- <div class="absolute inset-0 opacity-10">
                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1920&q=80" alt="" class="h-full w-full object-cover" />
                </div> -->
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <p class="mb-6 text-sm font-medium uppercase tracking-wider text-rose-400">Licensed With</p>
                        <div class="flex flex-wrap items-center justify-center gap-8 lg:gap-16">
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 p-3 backdrop-blur-sm">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Flag_of_Bangladesh.svg/64px-Flag_of_Bangladesh.svg.png" alt="NBR" class="h-10 w-auto" />
                                </div>
                                <span class="text-sm font-medium text-slate-900">NBR</span>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 p-3 backdrop-blur-sm">
                                    <div class="text-2xl font-bold text-rose-400">RJSC</div>
                                </div>
                                <span class="text-sm font-medium text-slate-900">RJSC</span>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 p-3 backdrop-blur-sm">
                                    <div class="text-2xl font-bold text-rose-400">BIDA</div>
                                </div>
                                <span class="text-sm font-medium text-slate-900">BIDA</span>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 p-3 backdrop-blur-sm">
                                    <svg class="h-10 w-10 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">City Corp</span>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 p-3 backdrop-blur-sm">
                                    <svg class="h-10 w-10 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">Licensed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Section -->
            <section id="about" class="relative overflow-hidden py-20 sm:py-24">
                <!-- Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-zinc-950 via-slate-900 to-zinc-900"></div>
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute right-0 top-0 h-96 w-96 rounded-full bg-rose-500/10 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 h-96 w-96 rounded-full bg-red-500/10 blur-3xl"></div>
                </div>
                
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid items-center gap-12 lg:grid-cols-2">
                        <!-- Left: Images -->
                        <div class="relative">
                            <div class="grid grid-cols-12 gap-4">
                                <!-- Large Image -->
                                <div class="col-span-7 overflow-hidden rounded-2xl">
                                    <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=500&h=600&fit=crop" alt="Professional Consultation" class="h-80 w-full object-cover shadow-2xl lg:h-96" />
                                </div>
                                <!-- Small Images Column -->
                                <div class="col-span-5 space-y-4">
                                    <div class="overflow-hidden rounded-2xl">
                                        <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=300&h=200&fit=crop" alt="Team Member" class="h-36 w-full object-cover shadow-xl lg:h-44" />
                                    </div>
                                    <div class="overflow-hidden rounded-2xl">
                                        <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?w=300&h=200&fit=crop" alt="Office" class="h-36 w-full object-cover shadow-xl lg:h-44" />
                                    </div>
                                </div>
                            </div>
                            <!-- Experience Badge -->
                            <div class="absolute -bottom-6 right-8 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-6 py-4 text-center shadow-xl">
                                <div class="text-4xl font-bold text-white">15+</div>
                                <div class="text-sm font-medium text-white/90">Years Experience</div>
                            </div>
                        </div>
                        
                        <!-- Right: Content -->
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium text-rose-300 backdrop-blur-sm">
                                Our Journey of Legal & Financial Confidence
                            </span>
                            <h2 class="mt-4 text-3xl font-bold text-white sm:text-4xl lg:text-5xl">Empowering You with Financial Guidance</h2>
                            <p class="mt-6 text-lg text-slate-300">
                                MBSC Firm is a Dhaka-based legal and consultancy firm established to serve businesses of all sizes. Our dedicated professionals bring expertise across multiple disciplines.
                            </p>
                            <div class="mt-8 grid grid-cols-2 gap-4">
                                <div class="flex items-center gap-3 rounded-xl bg-white/5 p-4 backdrop-blur-sm">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-500/20">
                                        <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-medium text-white">Registration Ease</span>
                                </div>
                                <div class="flex items-center gap-3 rounded-xl bg-white/5 p-4 backdrop-blur-sm">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-500/20">
                                        <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-medium text-white">Transparent Process</span>
                                </div>
                                <div class="flex items-center gap-3 rounded-xl bg-white/5 p-4 backdrop-blur-sm">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-500/20">
                                        <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-medium text-white">Expert Guidance</span>
                                </div>
                                <div class="flex items-center gap-3 rounded-xl bg-white/5 p-4 backdrop-blur-sm">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-500/20">
                                        <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-medium text-white">Trusted Partnership</span>
                                </div>
                            </div>
                            <div class="mt-8 flex items-center gap-4">
                                <a href="#" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-red-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:-translate-y-0.5 hover:shadow-xl">
                                    Get Started
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                <a href="tel:+8801868196716" class="inline-flex items-center gap-2 text-sm font-medium text-rose-300 transition-colors hover:text-rose-200">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    +88 01868-196716
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats Row -->
                    <div class="mt-16 grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div v-for="(stat, index) in stats" :key="index" class="rounded-2xl bg-white/5 p-6 text-center backdrop-blur-sm transition-all hover:bg-white/10">
                            <div class="text-3xl font-bold text-rose-400 sm:text-4xl">{{ stat.value }}</div>
                            <div class="mt-2 text-sm text-slate-400">{{ stat.label }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Process Section -->
            <section id="process" class="relative overflow-hidden bg-slate-50 py-20 sm:py-24">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=1920&q=80&auto=format" alt="" class="h-full w-full object-cover opacity-5" />
                </div>
                
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid items-center gap-12 lg:grid-cols-2">
                        <!-- Left Content -->
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-1.5 text-sm font-medium text-rose-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                How It Works
                            </span>
                            <h2 class="mt-4 text-3xl font-bold text-slate-900 sm:text-4xl">Real Results, Trusted by Businesses</h2>
                            <p class="mt-4 text-lg text-slate-600">Discover how MBSC Firm has empowered companies from startups to large corporations with our expert services.</p>
                            
                            <div class="mt-8 space-y-4">
                                <div v-for="(step, index) in processSteps" :key="index" class="flex items-start gap-4 rounded-xl bg-white p-4 shadow-sm transition-all hover:shadow-md">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-zinc-900 to-slate-800 text-lg font-bold text-rose-400 shadow-lg">{{ step.step }}</div>
                                    <div>
                                        <h3 class="font-semibold text-slate-900">{{ step.title }}</h3>
                                        <p class="text-sm text-slate-600">{{ step.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Image Grid -->
                        <div class="relative">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="overflow-hidden rounded-2xl bg-rose-100 p-6 text-center">
                                        <div class="text-4xl font-bold text-rose-700">500+</div>
                                        <div class="text-sm text-rose-600">Business Clients</div>
                                    </div>
                                    <div class="overflow-hidden rounded-2xl shadow-lg">
                                        <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=400&h=300&fit=crop" alt="E-commerce VAT Setup" class="h-40 w-full object-cover" />
                                        <div class="bg-white p-3">
                                            <div class="text-xs font-medium text-rose-600">E-commerce VAT Setup</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4 pt-8">
                                    <div class="overflow-hidden rounded-2xl shadow-lg">
                                        <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=400&h=300&fit=crop" alt="Company Registration" class="h-40 w-full object-cover" />
                                        <div class="bg-white p-3">
                                            <div class="text-xs font-medium text-rose-600">Foreign Company Registration</div>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden rounded-2xl shadow-lg">
                                        <img src="https://images.unsplash.com/photo-1600880292089-90a7e086ee0c?w=400&h=300&fit=crop" alt="Tax Planning" class="h-40 w-full object-cover" />
                                        <div class="bg-white p-3">
                                            <div class="text-xs font-medium text-rose-600">Startup Tax Planning</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section id="testimonials" class="relative overflow-hidden py-20 sm:py-24">
                <!-- Background -->
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=1920&q=80" alt="" class="h-full w-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-b from-zinc-950/95 via-slate-900/90 to-zinc-950/95"></div>
                </div>
                
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl text-center">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium text-rose-300 backdrop-blur-sm">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Best Financial Advisory Company
                        </span>
                        <h2 class="mt-4 text-3xl font-bold text-white sm:text-4xl">What Our Clients Say</h2>
                        <p class="mx-auto mt-4 max-w-xl text-lg text-slate-300">"Thanks to MBSC, our BIDA registration was smooth and fast. Highly recommended for foreign businesses."</p>
                    </div>

                    <!-- Featured Testimonial -->
                    <div class="mx-auto mt-10 max-w-3xl rounded-2xl bg-white/10 p-8 text-center backdrop-blur-sm">
                        <div class="mb-4 flex justify-center gap-1">
                            <svg v-for="i in 5" :key="i" class="h-6 w-6 text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face" alt="Client" class="mx-auto h-16 w-16 rounded-full border-4 border-rose-400/30" />
                        <div class="mt-4 text-lg font-semibold text-white">Karim Hassan</div>
                        <div class="text-sm text-slate-300">Managing Director, Dhaka Industries</div>
                    </div>

                    <div class="mt-12 grid gap-6 lg:grid-cols-3">
                        <div v-for="(testimonial, index) in testimonials" :key="index" class="relative rounded-2xl bg-white p-6 shadow-xl">
                            <!-- Quote Icon -->
                            <div class="absolute -top-3 left-6 flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-zinc-900 to-slate-800">
                                <svg class="h-3 w-3 text-rose-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            </div>
                            
                            <div class="mb-3 flex gap-1 pt-2">
                                <svg v-for="i in testimonial.rating" :key="i" class="h-4 w-4 text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <p class="mb-4 text-sm text-slate-600">"{{ testimonial.text }}"</p>
                            <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
                                <img :src="`https://images.unsplash.com/photo-${index === 0 ? '1472099645785-5658abf4ff4e' : index === 1 ? '1494790108377-be9c29b29330' : '1500648767791-00dcc994a43e'}?w=80&h=80&fit=crop&crop=face`" :alt="testimonial.name" class="h-10 w-10 rounded-full object-cover" />
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">{{ testimonial.name }}</div>
                                    <div class="text-xs text-slate-500">{{ testimonial.position }}, {{ testimonial.company }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Team Section -->
            <section class="relative overflow-hidden bg-white py-20 sm:py-24">
                <div class="absolute left-0 top-0 h-72 w-72 rounded-full bg-rose-50 blur-3xl"></div>
                <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-red-50 blur-3xl"></div>
                
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl text-center">
                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-1.5 text-sm font-medium text-rose-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Our Experts
                        </span>
                        <h2 class="mt-4 text-3xl font-bold text-slate-900 sm:text-4xl">Meet Our Team</h2>
                        <p class="mt-4 text-lg text-slate-600">Our team of qualified professionals is dedicated to providing exceptional service.</p>
                    </div>

                    <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div v-for="(member, index) in teamMembers" :key="index" class="group relative overflow-hidden rounded-2xl bg-white shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img :src="`https://images.unsplash.com/photo-${index === 0 ? '1560250097-0b93528c311a' : index === 1 ? '1573496359142-b8d87734a5a2' : '1519085360753-af0119f7cbe7'}?w=400&h=300&fit=crop&crop=face`" :alt="member.name" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                            </div>
                            <div class="p-6 text-center">
                                <h3 class="text-lg font-semibold text-slate-900">{{ member.name }}</h3>
                                <p class="text-sm font-medium text-rose-600">{{ member.position }}</p>
                                <p class="mt-2 text-sm text-slate-500">{{ member.specialization }}</p>
                                <div class="mt-4 flex justify-center gap-3">
                                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-all hover:bg-rose-600 hover:text-white"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>
                                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-all hover:bg-rose-600 hover:text-white"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-all hover:bg-rose-600 hover:text-white"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Blog Section -->
            <section class="relative overflow-hidden bg-slate-50 py-20 sm:py-24">
                <div class="absolute inset-0 opacity-50">
                    <div class="absolute left-1/4 top-0 h-96 w-96 rounded-full bg-rose-100 blur-3xl"></div>
                    <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-red-100 blur-3xl"></div>
                </div>
                
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex items-end justify-between">
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-1.5 text-sm font-medium text-rose-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                Blog
                            </span>
                            <h2 class="mt-4 text-3xl font-bold text-slate-900 sm:text-4xl">Discover Valuable Insights in Our Blog</h2>
                            <p class="mt-2 text-slate-600">Stay updated with the latest in tax, legal, and business regulations.</p>
                        </div>
                        <a href="#" class="hidden items-center gap-2 rounded-xl bg-gradient-to-r from-zinc-900 to-slate-800 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:from-zinc-950 hover:to-slate-900 sm:inline-flex">
                            View all articles
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>

                    <div class="mt-12 grid gap-8 lg:grid-cols-3">
                        <div v-for="(post, index) in blogPosts" :key="index" class="group overflow-hidden rounded-2xl bg-white shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl">
                            <div class="relative aspect-[16/10] overflow-hidden">
                                <img :src="`https://images.unsplash.com/photo-${index === 0 ? '1554224155-6726b3ff858f' : index === 1 ? '1450101499163-c8848c66ca85' : '1586281380349-632531db7ed4'}?w=600&h=400&fit=crop`" :alt="post.title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <span class="absolute bottom-4 left-4 rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-rose-700 backdrop-blur-sm">{{ post.category }}</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-slate-900 transition-colors group-hover:text-rose-700">{{ post.title }}</h3>
                                <p class="mt-2 text-sm text-slate-500">{{ post.date }}</p>
                                <a href="#" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-rose-600 transition-colors hover:text-rose-700">
                                    Read article
                                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="relative overflow-hidden py-20 sm:py-24">
                <!-- Background Image -->
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1560179707-f14e90ef3623?w=1920&q=80" alt="" class="h-full w-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-r from-zinc-950/95 via-slate-900/90 to-zinc-950/95"></div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute left-1/4 top-0 h-64 w-64 rounded-full bg-rose-500/10 blur-3xl"></div>
                    <div class="absolute bottom-0 right-1/4 h-64 w-64 rounded-full bg-red-500/10 blur-3xl"></div>
                </div>
                
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid items-center gap-12 lg:grid-cols-2">
                        <div class="text-center lg:text-left">
                            <span class="inline-flex items-center gap-2 rounded-full bg-rose-500/20 px-4 py-1.5 text-sm font-medium text-rose-300 backdrop-blur-sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Start Your Journey Today
                            </span>
                            <h2 class="mt-4 text-3xl font-bold text-white sm:text-4xl lg:text-5xl">Ready to Get Started?</h2>
                            <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300 lg:mx-0">
                                Let us help you with your business registration and compliance needs. Get expert guidance today and take your business to the next level.
                            </p>
                            <div class="mt-8 flex flex-wrap justify-center gap-4 lg:justify-start">
                                <a href="https://wa.me/8801868196716" target="_blank" class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-green-500 to-green-600 px-6 py-3.5 text-base font-semibold text-white shadow-lg shadow-green-500/30 transition-all hover:-translate-y-0.5 hover:shadow-xl">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    WhatsApp Us
                                </a>
                                <a href="#" class="inline-flex items-center gap-2 rounded-xl border-2 border-rose-400/40 bg-rose-500/10 px-6 py-3.5 text-base font-semibold text-rose-300 backdrop-blur-sm transition-all hover:border-rose-400/60 hover:bg-rose-500/20">
                                    Contact Us
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Stats Cards -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-white/10 p-6 text-center backdrop-blur-sm transition-all hover:bg-white/15">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-rose-400">500+</div>
                                <div class="mt-1 text-sm text-slate-300">Happy Clients</div>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-6 text-center backdrop-blur-sm transition-all hover:bg-white/15">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-rose-400">98%</div>
                                <div class="mt-1 text-sm text-slate-300">Success Rate</div>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-6 text-center backdrop-blur-sm transition-all hover:bg-white/15">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-rose-400">15+</div>
                                <div class="mt-1 text-sm text-slate-300">Years Experience</div>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-6 text-center backdrop-blur-sm transition-all hover:bg-white/15">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div class="text-3xl font-bold text-rose-400">24/7</div>
                                <div class="mt-1 text-sm text-slate-300">Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section id="faq" class="relative overflow-hidden bg-white py-20 sm:py-24">
                <!-- Background Decorations -->
                <div class="absolute inset-0">
                    <div class="absolute -left-40 top-0 h-80 w-80 rounded-full bg-rose-50 blur-3xl"></div>
                    <div class="absolute -right-40 bottom-0 h-80 w-80 rounded-full bg-red-50 blur-3xl"></div>
                </div>
                
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid items-start gap-12 lg:grid-cols-2">
                        <!-- Left Content -->
                        <div class="lg:sticky lg:top-24">
                            <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-1.5 text-sm font-medium text-rose-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                FAQ
                            </span>
                            <h2 class="mt-4 text-3xl font-bold text-slate-900 sm:text-4xl">Frequently Asked Questions</h2>
                            <p class="mt-4 text-lg text-slate-600">Find answers to common questions about our services. Can't find what you're looking for? Contact us directly.</p>
                            
                            <!-- Image -->
                            <div class="mt-8 overflow-hidden rounded-2xl shadow-lg">
                                <img src="https://images.unsplash.com/photo-1551836022-deb4988cc6c0?w=600&h=400&fit=crop" alt="FAQ Support" class="h-full w-full object-cover" />
                            </div>
                            
                            <!-- Quick Contact -->
                            <div class="mt-6 rounded-xl bg-gradient-to-br from-zinc-900 to-slate-800 p-6">
                                <h4 class="font-semibold text-white">Still have questions?</h4>
                                <p class="mt-2 text-sm text-slate-300">Our team is ready to help you with any inquiries.</p>
                                <a href="/#" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-rose-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-rose-600">
                                    Contact Us
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Right: FAQ Accordion -->
                        <div class="space-y-4">
                            <div v-for="(faq, index) in faqs" :key="index" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-all hover:shadow-md">
                                <button @click="toggleAccordion(index)" class="flex w-full items-center justify-between px-6 py-5 text-left">
                                    <span class="pr-4 font-medium text-slate-900">{{ faq.question }}</span>
                                    <div :class="['flex h-8 w-8 shrink-0 items-center justify-center rounded-full transition-colors', activeAccordion === index ? 'bg-rose-600' : 'bg-slate-100']">
                                        <svg :class="['h-4 w-4 transition-all', activeAccordion === index ? 'rotate-180 text-white' : 'text-slate-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </button>
                                <div :class="['overflow-hidden transition-all duration-300', activeAccordion === index ? 'max-h-96' : 'max-h-0']">
                                    <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 text-slate-600">{{ faq.answer }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="relative overflow-hidden py-20 sm:py-24">
                <!-- Background -->
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80" alt="" class="h-full w-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-br from-zinc-950/95 via-slate-900/90 to-zinc-900/95"></div>
                </div>
                
                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid gap-12 lg:grid-cols-2">
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full bg-rose-500/20 px-4 py-1.5 text-sm font-medium text-rose-300 backdrop-blur-sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Contact Us
                            </span>
                            <h2 class="mt-4 text-3xl font-bold text-white sm:text-4xl lg:text-5xl">Get in Touch</h2>
                            <p class="mt-4 text-lg text-slate-300">Have questions? We're here to help. Reach out to us and our expert team will get back to you within 24 hours.</p>

                            <div class="mt-10 space-y-6">
                                <div class="flex items-start gap-4 rounded-xl bg-white/5 p-5 backdrop-blur-sm transition-colors hover:bg-white/10">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-red-600 shadow-lg shadow-rose-500/25">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-white">Office Address</h4>
                                        <p class="mt-1 text-slate-300">Burma Razu Building (02nd Floor), Besides Fancy Tailors, Kotowali<br/>Chattogram, Bangladesh</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 rounded-xl bg-white/5 p-5 backdrop-blur-sm transition-colors hover:bg-white/10">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-red-600 shadow-lg shadow-rose-500/25">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-white">Phone Number</h4>
                                        <p class="mt-1 text-slate-300">+88 01868-196716</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 rounded-xl bg-white/5 p-5 backdrop-blur-sm transition-colors hover:bg-white/10">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-red-600 shadow-lg shadow-rose-500/25">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-white">Email Address</h4>
                                        <p class="mt-1 text-slate-300">mbscfirm@gmail.com</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 rounded-xl bg-white/5 p-5 backdrop-blur-sm transition-colors hover:bg-white/10">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-red-600 shadow-lg shadow-rose-500/25">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-white">Working Hours</h4>
                                        <p class="mt-1 text-slate-300">Sun - Thu: 9:00 AM - 6:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Map Placeholder -->
                            <!-- <div class="mt-8 overflow-hidden rounded-2xl">
                                <img src="https://images.unsplash.com/photo-1569336415962-a4bd9f69c07a?w=600&h=300&fit=crop" alt="Dhaka Location" class="h-48 w-full object-cover opacity-80" />
                            </div> -->
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white p-8 shadow-2xl">
                            <h3 class="mb-6 text-xl font-semibold text-slate-900">Send us a Message</h3>
                            <form class="space-y-5">
                                <div class="grid gap-5 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm transition-colors focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20" />
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Phone Number</label>
                                        <input type="tel" placeholder="+880 1XXX-XXXXXX" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm transition-colors focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20" />
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Email Address</label>
                                    <input type="email" placeholder="example@company.com" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm transition-colors focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Service Interested In</label>
                                    <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm transition-colors focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20">
                                        <option>Select a service</option>
                                        <option>VAT Compliance</option>
                                        <option>Company Formation</option>
                                        <option>Tax Consultancy</option>
                                        <option>BIDA Registration</option>
                                        <option>IRC & ERC</option>
                                        <option>Legal Services</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Your Message</label>
                                    <textarea rows="4" placeholder="Tell us about your requirements..." class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm transition-colors focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20"></textarea>
                                </div>
                                <button type="submit" disabled class="w-full rounded-xl bg-gradient-to-r from-zinc-900 to-slate-800 py-3.5 text-sm font-semibold text-white shadow-lg shadow-zinc-900/25 transition-all hover:-translate-y-0.5 hover:shadow-xl">
                                    Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
    </PublicLayout>
</template>
