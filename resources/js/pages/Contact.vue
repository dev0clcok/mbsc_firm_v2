<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const contactForm = ref({
    name: '',
    email: '',
    phone: '',
    subject: '',
    service: '',
    message: '',
});

const formSubmitting = ref(false);
const formSubmitted = ref(false);

const submitForm = () => {
    formSubmitting.value = true;
    // Simulate form submission
    setTimeout(() => {
        formSubmitting.value = false;
        formSubmitted.value = true;
        // Reset form
        contactForm.value = {
            name: '',
            email: '',
            phone: '',
            subject: '',
            service: '',
            message: '',
        };
    }, 1500);
};


const services = [
    'RJSC Limited Company Services',
    'Partnership Firm Services',
    'Foundation, Trust & Society Services',
    'Club Limited Company Services',
    'Trade Organization Services',
    'Income Tax Consultancy & Litigation',
    'VAT Advisory & Compliance',
    'Audit Support & Financial Assurance',
    'Other Services',
];


</script>

<template>
    <Head title="Contact Us - MBSC Firm" />

    <PublicLayout current-page="contact">
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
                            <svg class="h-4 w-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-sm font-medium text-rose-300">Get in Touch</span>
                        </div>
                        <h1 class="text-4xl font-bold text-white sm:text-5xl lg:text-6xl">
                            Let's Start a
                            <span class="bg-gradient-to-r from-rose-400 to-red-400 bg-clip-text text-transparent"> Conversation </span>
                        </h1>
                        <p class="mt-6 text-lg leading-relaxed text-slate-300">
                            Have questions about our services? We're here to help. Reach out to us and our team will get back to you within 24 hours.
                        </p>
                    </div>

                    <!-- Quick Contact Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 p-6 transition-all hover:-translate-y-1">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div class="text-lg font-bold text-white">Call Us</div>
                                <div class="mt-1 text-sm text-white/80">+88 01868-196716</div>
                            </div>
                            <div class="absolute -bottom-4 -right-4 h-20 w-20 rounded-full bg-white/20 blur-2xl"></div>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all hover:-translate-y-1 hover:bg-white/10">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="text-lg font-bold text-white">Email Us</div>
                                <div class="mt-1 text-sm text-slate-400">mbscfirm@gmail.com</div>
                            </div>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all hover:-translate-y-1 hover:bg-white/10">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="text-lg font-bold text-white">Visit Us</div>
                                <div class="mt-1 text-sm text-slate-400">Kotowali, Chattogram</div>
                            </div>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm transition-all hover:-translate-y-1 hover:bg-white/10">
                            <div class="relative z-10">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/20">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-lg font-bold text-white">Working Hours</div>
                                <div class="mt-1 text-sm text-slate-400">Mon - Fri: 9AM - 6PM</div>
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

       

        <!-- Main Content -->
        <section class="pb-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-12 lg:grid-cols-2">
                    <!-- Contact Form -->
                    <div>
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">Send Us a Message</h2>
                            <p class="mt-2 text-slate-600">Fill out the form below and we'll get back to you as soon as possible.</p>
                        </div>

                        <!-- Success Message -->
                        <Transition name="fade">
                            <div v-if="formSubmitted" class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-emerald-900">Message Sent Successfully!</h3>
                                        <p class="mt-1 text-sm text-emerald-700">Thank you for reaching out. We'll get back to you within 24 hours.</p>
                                    </div>
                                </div>
                            </div>
                        </Transition>

                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Full Name *</label>
                                    <input
                                        v-model="contactForm.name"
                                        type="text"
                                        required
                                        placeholder="John Doe"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-500/10"
                                    />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Email Address *</label>
                                    <input
                                        v-model="contactForm.email"
                                        type="email"
                                        required
                                        placeholder="john@example.com"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-500/10"
                                    />
                                </div>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Phone Number</label>
                                    <input
                                        v-model="contactForm.phone"
                                        type="tel"
                                        placeholder="+88 01868-196716"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-500/10"
                                    />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Service Interest</label>
                                    <select
                                        v-model="contactForm.service"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-500/10"
                                    >
                                        <option value="">Select a service</option>
                                        <option v-for="service in services" :key="service" :value="service">{{ service }}</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Subject *</label>
                                <input
                                    v-model="contactForm.subject"
                                    type="text"
                                    required
                                    placeholder="How can we help you?"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-500/10"
                                />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Message *</label>
                                <textarea
                                    v-model="contactForm.message"
                                    rows="5"
                                    required
                                    placeholder="Tell us more about your inquiry..."
                                    class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-500/10"
                                ></textarea>
                            </div>

                            <button
                                type="submit"
                                :disabled="formSubmitting"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-red-600 px-8 py-4 text-sm font-semibold text-white shadow-lg shadow-rose-500/30 transition-all hover:-translate-y-0.5 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-70"
                            >
                                <svg v-if="formSubmitting" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span v-if="formSubmitting">Sending...</span>
                                <span v-else>Send Message</span>
                                <svg v-if="!formSubmitting" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>
                    </div>

                    <!-- Map & Info -->
                    <div class="space-y-8">
                        <!-- Map -->
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg">
                            <div class="relative h-[350px] bg-slate-100">
                                <!-- Dummy Map -->
                                <div class="absolute inset-0 bg-gradient-to-br from-slate-100 to-slate-200">
                                    <!-- Map Grid Pattern -->
                                    <div class="absolute inset-0 bg-[linear-gradient(to_right,#cbd5e1_1px,transparent_1px),linear-gradient(to_bottom,#cbd5e1_1px,transparent_1px)] bg-[size:40px_40px] opacity-50"></div>
                                    
                                    <!-- Map Roads -->
                                    <svg class="absolute inset-0 h-full w-full" viewBox="0 0 400 350" preserveAspectRatio="xMidYMid slice">
                                        <!-- Main Roads -->
                                        <path d="M0 175 L400 175" stroke="#94a3b8" stroke-width="8" fill="none" />
                                        <path d="M200 0 L200 350" stroke="#94a3b8" stroke-width="8" fill="none" />
                                        <path d="M50 50 L350 300" stroke="#94a3b8" stroke-width="4" fill="none" />
                                        <path d="M350 50 L50 300" stroke="#94a3b8" stroke-width="4" fill="none" />
                                        
                                        <!-- Secondary Roads -->
                                        <path d="M0 100 L400 100" stroke="#cbd5e1" stroke-width="3" fill="none" />
                                        <path d="M0 250 L400 250" stroke="#cbd5e1" stroke-width="3" fill="none" />
                                        <path d="M100 0 L100 350" stroke="#cbd5e1" stroke-width="3" fill="none" />
                                        <path d="M300 0 L300 350" stroke="#cbd5e1" stroke-width="3" fill="none" />
                                        
                                        <!-- Buildings -->
                                        <rect x="120" y="120" width="30" height="25" fill="#e2e8f0" rx="2" />
                                        <rect x="250" y="80" width="40" height="30" fill="#e2e8f0" rx="2" />
                                        <rect x="60" y="200" width="35" height="40" fill="#e2e8f0" rx="2" />
                                        <rect x="310" y="220" width="45" height="35" fill="#e2e8f0" rx="2" />
                                        <rect x="150" y="260" width="30" height="30" fill="#e2e8f0" rx="2" />
                                        <rect x="220" y="190" width="25" height="35" fill="#e2e8f0" rx="2" />
                                    </svg>
                                    
                                    <!-- Location Marker -->
                                    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-full">
                                        <div class="relative">
                                            <div class="absolute -inset-2 animate-ping rounded-full bg-rose-500/30"></div>
                                            <div class="relative flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-red-600 shadow-lg shadow-rose-500/50">
                                                <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Map Labels -->
                                    <div class="absolute bottom-4 left-4 rounded-lg bg-white/90 px-3 py-2 text-xs font-medium text-slate-700 shadow backdrop-blur-sm">
                                        📍 MBSC Firm, Kotowali, Chattogram
                                    </div>
                                    
                                    <!-- Zoom Controls -->
                                    <div class="absolute right-4 top-4 flex flex-col gap-1">
                                        <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-slate-600 shadow transition-colors hover:bg-slate-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
                                        </button>
                                        <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-slate-600 shadow transition-colors hover:bg-slate-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <a href="https://maps.google.com" target="_blank" class="flex items-center justify-center gap-2 rounded-xl border-2 border-slate-200 py-3 text-sm font-semibold text-slate-700 transition-all hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Open in Google Maps
                                </a>
                            </div>
                        </div>

                    

                        <!-- Social Links -->
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg">
                            <h3 class="mb-4 text-lg font-bold text-slate-900">Connect With Us</h3>
                            <div class="flex flex-wrap gap-3">
                                <a href="https://www.facebook.com/profile.php?id=61578616047092" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 rounded-xl bg-blue-50 px-4 py-2.5 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-100">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    Facebook
                                </a>
                                <a href="#" class="flex items-center gap-2 rounded-xl bg-sky-50 px-4 py-2.5 text-sm font-medium text-sky-700 transition-colors hover:bg-sky-100">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    LinkedIn
                                </a>
                                <a href="https://wa.me/8801868196716" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-700 transition-colors hover:bg-emerald-100">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    WhatsApp
                                </a>
                            </div>
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

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
