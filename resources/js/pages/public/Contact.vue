<template>
    <PublicLayout>
        <Head title="Contact Us - MBSC" />

        <div class="bg-muted/50 py-12">
            <div class="container mx-auto px-4">
                <div class="mb-8 text-center">
                    <h1 class="mb-4 text-4xl font-bold">Contact Us</h1>
                    <p class="text-muted-foreground">
                        Get in touch with us for expert business consulting services
                    </p>
                </div>

                <div class="grid gap-8 lg:grid-cols-2">
                    <!-- Contact Form -->
                    <div class="rounded-lg border border-border bg-card p-6">
                        <h2 class="mb-6 text-2xl font-semibold">Send us a Message</h2>
                        <ContactForm :services="services" />
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-6">
                        <div class="rounded-lg border border-border bg-card p-6">
                            <h2 class="mb-6 text-2xl font-semibold">Contact Information</h2>
                            <div class="space-y-4">
                                <div v-for="info in contactInfo" :key="info.id" class="flex items-start gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                        <Icon
                                            :name="getIconForType(info.type)"
                                            class="h-5 w-5"
                                        />
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ info.label }}</div>
                                        <div class="text-sm text-muted-foreground">{{ info.value }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-border bg-card p-6">
                            <h3 class="mb-4 font-semibold">Business Hours</h3>
                            <div class="space-y-2 text-sm text-muted-foreground">
                                <div>Monday - Friday: 9:00 AM - 6:00 PM</div>
                                <div>Saturday: 10:00 AM - 4:00 PM</div>
                                <div>Sunday: Closed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import ContactForm from '@/components/public/ContactForm.vue';
import Icon from '@/components/Icon.vue';

interface Props {
    services: Array<{
        id: number;
        title: string;
    }>;
    contactInfo: Array<{
        id: number;
        type: string;
        label: string;
        value: string;
    }>;
}

defineProps<Props>();

const getIconForType = (type: string) => {
    const icons: Record<string, string> = {
        phone: 'phone',
        email: 'mail',
        address: 'map-pin',
    };
    return icons[type] || 'info';
};
</script>
