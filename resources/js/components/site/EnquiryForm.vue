<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import { useSite } from '@/composables/useSite';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, nextTick, ref, useId } from 'vue';

const props = defineProps<{
    /** Preselects the service, for use on a service page. */
    service?: string;
}>();

const site = useSite();
const id = useId();

const form = useForm({
    name: '',
    phone: '',
    email: '',
    service: props.service ?? '',
    message: '',
    website: '',
});

const sent = ref(false);
const statusEl = ref<HTMLElement | null>(null);

const errors = computed(() => form.errors as Record<string, string | undefined>);

const submit = () => {
    sent.value = false;
    form.post('/enquiries', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.service = props.service ?? '';
            sent.value = true;
            nextTick(() => statusEl.value?.focus());
        },
        onError: () => {
            nextTick(() => document.querySelector<HTMLElement>(`[data-form="${id}"] [aria-invalid="true"]`)?.focus());
        },
    });
};

// The WhatsApp option carries over whatever has been typed so far, and
// falls back to the standard template message when the form is empty.
const whatsappHref = computed(() => {
    if (!site.value.whatsapp_base_url) return null;

    const lines = [
        form.name && `Name: ${form.name}`,
        form.service && `Service: ${form.service}`,
        form.message,
    ].filter(Boolean);

    return lines.length ? `${site.value.whatsapp_base_url}?text=${encodeURIComponent(lines.join('\n'))}` : site.value.whatsapp_url;
});
</script>

<template>
    <div :data-form="id">
        <div
            v-if="sent"
            ref="statusEl"
            role="status"
            tabindex="-1"
            class="mb-6 flex gap-3 rounded-site border border-ok bg-ok-tint p-4 text-ok"
        >
            <SiteIcon name="check" />
            <div>
                <p class="font-semibold">Your enquiry has been sent.</p>
                <p v-if="site.response_time">We reply {{ site.response_time }}.</p>
                <p v-else>We will reply using the contact details you gave.</p>
            </div>
        </div>

        <div v-if="errors.form" role="alert" class="mb-6 rounded-site border border-rose bg-rose-tint p-4 font-medium text-rose-deep">
            {{ errors.form }}
        </div>

        <form class="space-y-5" novalidate @submit.prevent="submit">
            <div>
                <label :for="`${id}-name`" class="site-label">Your name</label>
                <input
                    :id="`${id}-name`"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    required
                    class="site-input"
                    :aria-invalid="errors.name ? 'true' : undefined"
                    :aria-describedby="errors.name ? `${id}-name-error` : undefined"
                />
                <p v-if="errors.name" :id="`${id}-name-error`" class="site-error">{{ errors.name }}</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label :for="`${id}-phone`" class="site-label">Phone</label>
                    <input
                        :id="`${id}-phone`"
                        v-model="form.phone"
                        type="tel"
                        inputmode="tel"
                        autocomplete="tel"
                        class="site-input"
                        :aria-invalid="errors.phone ? 'true' : undefined"
                        :aria-describedby="`${id}-reply-hint${errors.phone ? ` ${id}-phone-error` : ''}`"
                    />
                    <p v-if="errors.phone" :id="`${id}-phone-error`" class="site-error">{{ errors.phone }}</p>
                </div>
                <div>
                    <label :for="`${id}-email`" class="site-label">Email</label>
                    <input
                        :id="`${id}-email`"
                        v-model="form.email"
                        type="email"
                        autocomplete="email"
                        class="site-input"
                        :aria-invalid="errors.email ? 'true' : undefined"
                        :aria-describedby="`${id}-reply-hint${errors.email ? ` ${errors.email === errors.phone ? `${id}-phone-error` : `${id}-email-error`}` : ''}`"
                    />
                    <p v-if="errors.email && errors.email !== errors.phone" :id="`${id}-email-error`" class="site-error">{{ errors.email }}</p>
                </div>
                <p :id="`${id}-reply-hint`" class="-mt-3 text-sm text-ink-soft sm:col-span-2">Give a phone number, an email address, or both.</p>
            </div>

            <div v-if="site.services.length">
                <label :for="`${id}-service`" class="site-label">Service <span class="font-normal text-ink-soft">(optional)</span></label>
                <select :id="`${id}-service`" v-model="form.service" class="site-input">
                    <option value="">Not sure yet</option>
                    <option v-for="s in site.services" :key="s.slug" :value="s.title">{{ s.title }}</option>
                </select>
            </div>

            <div>
                <label :for="`${id}-message`" class="site-label">What do you need help with?</label>
                <textarea
                    :id="`${id}-message`"
                    v-model="form.message"
                    rows="5"
                    required
                    class="site-input"
                    :aria-invalid="errors.message ? 'true' : undefined"
                    :aria-describedby="errors.message ? `${id}-message-error` : undefined"
                ></textarea>
                <p v-if="errors.message" :id="`${id}-message-error`" class="site-error">{{ errors.message }}</p>
            </div>

            <!-- Honeypot: hidden from people, filled in by bots. -->
            <div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
                <label :for="`${id}-website`">Leave this field empty</label>
                <input :id="`${id}-website`" v-model="form.website" type="text" tabindex="-1" autocomplete="off" />
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="site-btn site-btn-primary" :disabled="form.processing">
                    {{ form.processing ? 'Sending enquiry' : 'Send enquiry' }}
                </button>
                <a v-if="whatsappHref" :href="whatsappHref" target="_blank" rel="noopener noreferrer" class="site-btn site-btn-secondary">
                    <SiteIcon name="whatsapp" />
                    Message on WhatsApp instead
                </a>
            </div>

            <p class="text-sm text-ink-soft">
                We use these details only to reply to your enquiry.
                <template v-if="site.privacy_published">
                    See our <Link href="/privacy" class="site-link">privacy policy</Link>.
                </template>
            </p>
        </form>
    </div>
</template>
