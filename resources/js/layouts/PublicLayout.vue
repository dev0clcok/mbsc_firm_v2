<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineProps<{
    currentPage?: string;
}>();

const page = usePage();
const mobileMenuOpen = ref(false);

const canRegister = computed(() => page.props.canRegister as boolean);
const login = () => (page.props as any).auth?.loginUrl || '/login';
const register = () => (page.props as any).auth?.registerUrl || '/register';
const dashboard = () => '/dashboard';

const navLinks = [
    { name: 'Home', href: '/', page: 'home' },
    { name: 'Services', href: '/services', page: 'services' },
    { name: 'About Us', href: '/about', page: 'about' },
    { name: 'Contact', href: '/contact', page: 'contact' },
];
</script>

<template>
    <div class="min-h-screen bg-white">
        <!-- Top Bar -->
        <div class="bg-gradient-to-r from-zinc-950 to-slate-900">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2.5 sm:px-6 lg:px-8">
                <div class="flex items-center gap-6 text-sm text-slate-300">
                    <a href="mailto:mbscfirm@gmail.com" class="flex items-center gap-2 transition-colors hover:text-rose-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        mbscfirm@gmail.com
                    </a>
                    <span class="hidden text-slate-600 sm:inline">|</span>
                    <span class="hidden sm:inline">Sun - Thu: 9:00 AM - 6:00 PM</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="https://www.facebook.com/profile.php?id=61578616047092" target="_blank" rel="noopener noreferrer" class="text-slate-400 transition-colors hover:text-rose-400">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="text-slate-400 transition-colors hover:text-rose-400">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="#" class="text-slate-400 transition-colors hover:text-rose-400">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0012.05 0z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <header class="sticky top-0 z-50 border-b border-slate-100 bg-white/95 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <Link href="/" class="flex items-center gap-3">
                   <img src="/logo.png" class="h-16 w-auto" alt="">
                </Link>

                <nav class="hidden items-center gap-8 lg:flex">
                    <Link 
                        v-for="link in navLinks" 
                        :key="link.page"
                        :href="link.href" 
                        :class="[
                            'text-sm font-medium transition-colors hover:text-rose-600',
                            currentPage === link.page ? 'text-rose-600' : 'text-slate-700'
                        ]"
                    >
                        {{ link.name }}
                    </Link>
                </nav>

                <div class="flex items-center gap-3">
                    <a href="tel:+8801868196716" class="hidden items-center gap-2 rounded-full bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 lg:flex">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        +88 01868-196716
                    </a>
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="rounded-lg bg-gradient-to-r from-zinc-900 to-slate-800 px-5 py-2.5 text-sm font-medium text-white shadow-lg shadow-zinc-900/30 transition-all hover:shadow-xl hover:shadow-zinc-900/40"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="login()" class="hidden rounded-lg border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 sm:inline-flex">
                            Sign In
                        </Link>
                        <Link v-if="canRegister" :href="register()" class="rounded-lg bg-gradient-to-r from-rose-500 to-red-600 px-5 py-2.5 text-sm font-medium text-white shadow-lg shadow-rose-500/25 transition-all hover:shadow-xl hover:shadow-rose-500/30">
                            Get Started
                        </Link>
                    </template>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden">
                        <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div v-if="mobileMenuOpen" class="border-t border-slate-100 bg-white px-4 py-4 lg:hidden">
                <nav class="flex flex-col gap-3">
                    <Link 
                        v-for="link in navLinks" 
                        :key="link.page"
                        :href="link.href" 
                        :class="[
                            'rounded-lg px-4 py-2 text-sm font-medium hover:bg-slate-50',
                            currentPage === link.page ? 'bg-rose-50 text-rose-600' : 'text-slate-700'
                        ]"
                        @click="mobileMenuOpen = false"
                    >
                        {{ link.name }}
                    </Link>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="relative overflow-hidden bg-zinc-950 pt-20">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80" alt="" class="h-full w-full object-cover opacity-10" />
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/95 to-zinc-950/90"></div>
            </div>
            
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-12 lg:grid-cols-4">
                    <!-- Brand Column -->
                    <div class="lg:col-span-1">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-red-600 shadow-lg shadow-rose-500/30">
                                <span class="text-xl font-bold text-white">M</span>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-white">MBSC Firm</div>
                                <div class="text-xs text-slate-400">Legal & Tax Consultancy</div>
                            </div>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed text-slate-400">
                            Your trusted partner for comprehensive legal and tax solutions in Bangladesh. Expert guidance for business growth.
                        </p>
                        <div class="mt-6 flex gap-4">
                            <a href="https://www.facebook.com/profile.php?id=61578616047092" target="_blank" rel="noopener noreferrer" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-slate-400 transition-all hover:bg-rose-500 hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-slate-400 transition-all hover:bg-rose-500 hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            <a href="#" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-slate-400 transition-all hover:bg-rose-500 hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0012.05 0z"/></svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Quick Links</h4>
                        <ul class="mt-6 space-y-3">
                            <li><Link href="/" class="text-sm text-slate-400 transition-colors hover:text-rose-400">Home</Link></li>
                            <li><Link href="#" class="text-sm text-slate-400 transition-colors hover:text-rose-400">Services</Link></li>
                            <li><Link href="#" class="text-sm text-slate-400 transition-colors hover:text-rose-400">About Us</Link></li>
                            <li><Link href="#" class="text-sm text-slate-400 transition-colors hover:text-rose-400">Contact</Link></li>
                        </ul>
                    </div>
                    
                    <!-- Services -->
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Our Services</h4>
                        <ul class="mt-6 space-y-3">
                            <li><Link href="#" class="text-sm text-slate-400 transition-colors hover:text-rose-400">RJSC Limited Company</Link></li>
                            <li><Link href="#" class="text-sm text-slate-400 transition-colors hover:text-rose-400">Partnership Firm</Link></li>
                            <li><Link href="#" class="text-sm text-slate-400 transition-colors hover:text-rose-400">Foundation / Trust / Society</Link></li>
                            <li><Link href="#" class="text-sm text-slate-400 transition-colors hover:text-rose-400">Income Tax Consultancy & Litigation</Link></li>
                            <li><Link href="#" class="text-sm text-slate-400 transition-colors hover:text-rose-400">VAT Advisory & Compliance</Link></li>
                            <li><Link href="#" class="text-sm text-slate-400 transition-colors hover:text-rose-400">Audit Support & Assurance</Link></li>
                        </ul>
                    </div>
                    
                    <!-- Contact Info -->
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Contact Info</h4>
                        <ul class="mt-6 space-y-4">
                            <li class="flex items-start gap-3">
                                <svg class="h-5 w-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-sm text-slate-400">Burma Razu Building (02nd Floor), Besides Fancy Tailors, Kotowali, Chattogram</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="h-5 w-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span class="text-sm text-slate-400">+88 01868-196716</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="h-5 w-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="text-sm text-slate-400">mbscfirm@gmail.com</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Bottom Bar -->
                <div class="mt-16 flex flex-col items-center justify-between gap-4 border-t border-white/10 py-8 sm:flex-row">
                    <p class="text-sm text-slate-500">&copy; {{ new Date().getFullYear() }} MBSC Firm. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a href="#" class="text-sm text-slate-500 transition-colors hover:text-rose-400">Privacy Policy</a>
                        <a href="#" class="text-sm text-slate-500 transition-colors hover:text-rose-400">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
