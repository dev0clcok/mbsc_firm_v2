<script setup lang="ts">
import ContactDetails from '@/components/site/ContactDetails.vue';
import EnquiryForm from '@/components/site/EnquiryForm.vue';
import OfficeMap from '@/components/site/OfficeMap.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { useSite } from '@/composables/useSite';
import { Head } from '@inertiajs/vue3';

const site = useSite();
</script>

<template>
    <Head title="Contact" />

    <PublicLayout current-page="contact">
        <section class="py-section">
            <div class="site-container">
                <h1 class="site-display">Contact</h1>
                <p class="site-lead mt-6">
                    Send an enquiry, call, or message us on WhatsApp. The first consultation is free.
                    <template v-if="site.response_time">We reply {{ site.response_time }}.</template>
                </p>

                <div class="mt-12 grid gap-x-16 gap-y-14 lg:grid-cols-[minmax(0,7fr)_minmax(0,5fr)]">
                    <section id="enquiry" class="scroll-mt-24" aria-labelledby="contact-enquiry">
                        <h2 id="contact-enquiry" class="site-title mb-6">Send an enquiry</h2>
                        <div class="site-card p-6 sm:p-8">
                            <EnquiryForm />
                        </div>
                    </section>

                    <section aria-labelledby="contact-office">
                        <h2 id="contact-office" class="site-title mb-6">Office</h2>
                        <ContactDetails />
                        <OfficeMap class="mt-8" />
                    </section>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
