<template>
    <div class="min-h-screen bg-background">
        <header class="border-b border-border">
            <nav class="container mx-auto flex items-center justify-between px-4 py-4">
                <Link :href="home().url" class="text-xl font-bold">
                    MBSC
                </Link>
                <div class="flex items-center gap-6">
                    <Link :href="services.index().url" class="text-sm hover:text-primary">
                        Services
                    </Link>
                    <Link :href="blog.index().url" class="text-sm hover:text-primary">
                        Blog
                    </Link>
                    <Link :href="caseStudies.index().url" class="text-sm hover:text-primary">
                        Case Studies
                    </Link>
                    <Link :href="faq.index().url" class="text-sm hover:text-primary">
                        FAQ
                    </Link>
                    <Link :href="about().url" class="text-sm hover:text-primary">
                        About
                    </Link>
                    <Link
                        :href="contact.index().url"
                        class="rounded-md bg-primary px-4 py-2 text-sm text-primary-foreground hover:bg-primary/90"
                    >
                        Contact
                    </Link>
                </div>
            </nav>
        </header>

        <main>
            <slot />
        </main>

        <footer class="mt-auto border-t border-border bg-muted/50">
            <div class="container mx-auto px-4 py-8">
                <div class="grid gap-8 md:grid-cols-4">
                    <div>
                        <h3 class="mb-4 font-semibold">MODERN BUSINESS SOLUTION & CONSULTANCY</h3>
                        <p class="text-sm text-muted-foreground">
                            Your trusted partner for business consulting services in Bangladesh.
                        </p>
                    </div>
                    <div>
                        <h4 class="mb-4 font-semibold">Services</h4>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <Link :href="services.index().url" class="text-muted-foreground hover:text-primary">
                                    All Services
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 font-semibold">Company</h4>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <Link :href="about().url" class="text-muted-foreground hover:text-primary">
                                    About Us
                                </Link>
                            </li>
                            <li>
                                <Link :href="blog.index().url" class="text-muted-foreground hover:text-primary">
                                    Blog
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 font-semibold">Contact</h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>Email: mbscfirm@gmail.com</li>
                            <li>Phone: +88 01868-196716</li>
                            <li>Address: Burma Razu Building (02nd Floor), Besides Fancy Tailors, Kotowali, Chattogram</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 border-t border-border pt-8 text-center text-sm text-muted-foreground">
                    <p>&copy; {{ new Date().getFullYear() }} MODERN BUSINESS SOLUTION & CONSULTANCY. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home, about } from '@/routes';
import * as services from '@/routes/services';
import * as blog from '@/routes/blog';
import * as caseStudies from '@/routes/case-studies';
import * as faq from '@/routes/faq';
import * as contact from '@/routes/contact';
</script>
