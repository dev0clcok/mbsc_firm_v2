<script setup lang="ts">
import FloatingActions from '@/components/site/FloatingActions.vue';
import SiteIcon from '@/components/site/SiteIcon.vue';
import SiteLogo from '@/components/site/SiteLogo.vue';
import { useSite } from '@/composables/useSite';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    currentPage?: 'home' | 'services' | 'about' | 'contact';
}>();

const site = useSite();
const menuOpen = ref(false);

const navLinks = [
    { name: 'Home', href: '/', page: 'home' },
    { name: 'Services', href: '/services', page: 'services' },
    { name: 'About', href: '/about', page: 'about' },
    { name: 'Contact', href: '/contact', page: 'contact' },
];

const socialLabel = (platform: string) => (platform === 'x' ? 'X' : platform.charAt(0).toUpperCase() + platform.slice(1));
</script>

<template>
    <div class="site flex min-h-screen flex-col">
        <a
            href="#main"
            class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60] focus:rounded-site focus:bg-ink focus:px-4 focus:py-3 focus:font-semibold focus:text-white"
        >
            Skip to main content
        </a>

        <header class="sticky top-0 z-50 border-b border-rule bg-paper">
            <div class="site-container flex h-[4.5rem] items-center justify-between gap-6">
                <Link href="/" class="shrink-0">
                    <SiteLogo />
                    <span class="sr-only">, home page</span>
                </Link>

                <nav class="hidden lg:block" aria-label="Main">
                    <ul class="flex items-center gap-8">
                        <li v-for="link in navLinks" :key="link.page">
                            <Link
                                :href="link.href"
                                :aria-current="currentPage === link.page ? 'page' : undefined"
                                :class="[
                                    'inline-flex min-h-11 items-center border-b-2 font-semibold transition-colors hover:text-rose',
                                    currentPage === link.page ? 'border-rose text-ink' : 'border-transparent text-ink-soft',
                                ]"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </nav>

                <div class="flex items-center gap-2 sm:gap-4">
                    <a
                        v-if="site.phone && site.phone_href"
                        :href="site.phone_href"
                        class="hidden min-h-11 items-center gap-2 font-semibold text-ink transition-colors hover:text-rose lg:inline-flex"
                    >
                        <SiteIcon name="phone" />
                        {{ site.phone }}
                    </a>
                    <Link href="/contact#enquiry" class="site-btn site-btn-primary hidden sm:inline-flex">Send an enquiry</Link>
                    <button
                        type="button"
                        class="flex h-12 w-12 items-center justify-center rounded-site text-ink hover:bg-mist lg:hidden"
                        :aria-expanded="menuOpen"
                        aria-controls="site-menu"
                        :aria-label="menuOpen ? 'Close menu' : 'Open menu'"
                        @click="menuOpen = !menuOpen"
                    >
                        <SiteIcon :name="menuOpen ? 'close' : 'menu'" />
                    </button>
                </div>
            </div>

            <nav v-show="menuOpen" id="site-menu" class="border-t border-rule bg-paper lg:hidden" aria-label="Main" @keydown.esc="menuOpen = false">
                <ul class="site-container divide-y divide-rule">
                    <li v-for="link in navLinks" :key="link.page">
                        <Link
                            :href="link.href"
                            :aria-current="currentPage === link.page ? 'page' : undefined"
                            :class="['flex min-h-14 items-center text-lg font-semibold', currentPage === link.page ? 'text-rose' : 'text-ink']"
                            @click="menuOpen = false"
                        >
                            {{ link.name }}
                        </Link>
                    </li>
                </ul>
            </nav>
        </header>

        <main id="main" tabindex="-1" class="flex-1 outline-none">
            <slot />
        </main>

        <footer class="site-on-ink bg-ink pb-24 text-white lg:pb-0">
            <div class="site-container grid gap-12 py-16 lg:grid-cols-[minmax(0,4fr)_minmax(0,2fr)_minmax(0,3fr)_minmax(0,4fr)]">
                <div>
                    <SiteLogo on-ink />
                    <p class="mt-5 max-w-[36ch] text-white/75">RJSC, tax and legal compliance services from Chattogram, Bangladesh.</p>
                    <ul v-if="site.socials.length" class="mt-5 -ml-3 flex">
                        <li v-for="social in site.socials" :key="social.platform">
                            <a
                                :href="social.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                :aria-label="`${site.name} on ${socialLabel(social.platform)}`"
                                class="flex h-11 w-11 items-center justify-center text-white/75 transition-colors hover:text-white"
                            >
                                <SiteIcon :name="social.platform as any" />
                            </a>
                        </li>
                    </ul>
                </div>

                <nav aria-label="Footer">
                    <h2 class="text-sm font-semibold text-white/60">Pages</h2>
                    <ul class="mt-1">
                        <li v-for="link in navLinks" :key="link.page">
                            <Link :href="link.href" class="block py-2.5 leading-6 text-white/85 underline-offset-4 hover:text-white hover:underline">{{ link.name }}</Link>
                        </li>
                    </ul>
                </nav>

                <nav v-if="site.services.length" aria-label="Services">
                    <h2 class="text-sm font-semibold text-white/60">Services</h2>
                    <ul class="mt-1">
                        <li v-for="service in site.services" :key="service.slug">
                            <Link :href="`/services/${service.slug}`" class="block py-2.5 leading-6 text-white/85 underline-offset-4 hover:text-white hover:underline">{{ service.title }}</Link>
                        </li>
                    </ul>
                </nav>

                <div>
                    <h2 class="text-sm font-semibold text-white/60">Office</h2>
                    <ul class="mt-3 space-y-3 text-white/85">
                        <li v-if="site.address"><address class="not-italic">{{ site.address }}</address></li>
                        <li v-if="site.office_hours">{{ site.office_hours }}</li>
                        <li v-if="site.phone && site.phone_href">
                            <a :href="site.phone_href" class="inline-flex min-h-11 items-center gap-2 underline-offset-4 hover:text-white hover:underline"><SiteIcon name="phone" />{{ site.phone }}</a>
                        </li>
                        <li v-if="site.email">
                            <a :href="`mailto:${site.email}`" class="inline-flex min-h-11 items-center gap-2 break-all underline-offset-4 hover:text-white hover:underline"><SiteIcon name="mail" />{{ site.email }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/15">
                <div class="site-container flex flex-wrap items-center justify-between gap-x-6 gap-y-2 py-5 text-sm text-white/70">
                    <p>&copy; {{ new Date().getFullYear() }} {{ site.name }}. All rights reserved.</p>
                    <p>Design &amp; development by Devoclock</p>
                </div>
            </div>
        </footer>

        <FloatingActions />

        <!-- Phones: contact actions stay within reach while scrolling. -->
        <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-rule bg-paper shadow-raised lg:hidden" aria-label="Contact">
            <ul class="flex divide-x divide-rule">
                <li v-if="site.phone_href" class="flex-1">
                    <a :href="site.phone_href" class="flex min-h-14 items-center justify-center gap-2 font-semibold text-ink"><SiteIcon name="phone" />Call</a>
                </li>
                <li v-if="site.whatsapp_url" class="flex-1">
                    <a :href="site.whatsapp_url" target="_blank" rel="noopener noreferrer" class="flex min-h-14 items-center justify-center gap-2 font-semibold text-ink"><SiteIcon name="whatsapp" />WhatsApp</a>
                </li>
                <li class="flex-1">
                    <Link href="/contact#enquiry" class="flex min-h-14 items-center justify-center bg-rose font-semibold text-white">Enquire</Link>
                </li>
            </ul>
        </nav>
    </div>
</template>
