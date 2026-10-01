<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import { useSite } from '@/composables/useSite';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, nextTick, reactive, ref, useId } from 'vue';

const props = defineProps<{
    /** Preselects the service, for use on a service page. */
    service?: string;
}>();

type Field = 'name' | 'phone' | 'email' | 'message';

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

const sentTo = ref<string | null>(null);
const thanksEl = ref<HTMLElement | null>(null);
const root = ref<HTMLElement | null>(null);

// Checked as the visitor leaves each field, and again on submit. The
// server applies the same rules, so these only save a round trip.
const touched = reactive<Record<Field, boolean>>({ name: false, phone: false, email: false, message: false });

const rules: Record<Field, () => string | undefined> = {
    name: () => (form.name.trim() ? undefined : 'Enter your name.'),
    phone: () => {
        const phone = form.phone.trim();
        if (!phone) return form.email.trim() ? undefined : 'Enter a phone number or an email address so we can reply.';
        return /^[0-9+\-\s()]{6,}$/.test(phone) ? undefined : 'Enter a valid phone number, for example 01XXX-XXXXXX.';
    },
    email: () => {
        const email = form.email.trim();
        if (!email) return undefined;
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) ? undefined : 'Enter a valid email address, for example name@example.com.';
    },
    message: () => {
        const length = form.message.trim().length;
        if (!length) return 'Tell us what you need help with.';
        return length >= 10 ? undefined : 'Add a little more detail, at least 10 characters.';
    },
};

const serverErrors = computed(() => form.errors as Record<string, string | undefined>);

const errorFor = (field: Field) => (touched[field] ? rules[field]() : undefined) ?? serverErrors.value[field];

const leave = (field: Field) => {
    touched[field] = true;
    form.clearErrors(field);

    // Phone and email depend on each other: once both have been visited, check the pair.
    if (field === 'email') touched.phone = true;
};

const formErrorEl = ref<HTMLElement | null>(null);

// A field error takes the focus first; an error about the whole form
// (the rate limit) is shown beside the button and focused there.
const focusFirstError = () =>
    nextTick(() => (root.value?.querySelector<HTMLElement>('[aria-invalid="true"]') ?? formErrorEl.value)?.focus());

const submit = () => {
    (Object.keys(touched) as Field[]).forEach((field) => (touched[field] = true));

    if ((Object.keys(rules) as Field[]).some((field) => rules[field]())) {
        focusFirstError();
        return;
    }

    const name = form.name.trim();

    form.post('/enquiries', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.service = props.service ?? '';
            (Object.keys(touched) as Field[]).forEach((field) => (touched[field] = false));
            sentTo.value = name;
            nextTick(() => thanksEl.value?.focus());
        },
        onError: focusFirstError,
    });
};

const another = () => {
    sentTo.value = null;
    nextTick(() => root.value?.querySelector<HTMLElement>('input')?.focus());
};

// The WhatsApp option carries over whatever has been typed so far, and
// falls back to the standard template message when the form is empty.
const whatsappHref = computed(() => {
    if (!site.value.whatsapp_base_url) return null;

    const lines = [form.name && `Name: ${form.name}`, form.service && `Service: ${form.service}`, form.message].filter(Boolean);

    return lines.length ? `${site.value.whatsapp_base_url}?text=${encodeURIComponent(lines.join('\n'))}` : site.value.whatsapp_url;
});
</script>

<template>
    <div ref="root">
        <!-- Thank-you state: says what happens next, and when. -->
        <div v-if="sentTo !== null" ref="thanksEl" tabindex="-1" role="status" class="outline-none">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-ok-tint text-ok"><SiteIcon name="check" /></span>
            <h3 class="site-title mt-5">Thank you{{ sentTo ? `, ${sentTo}` : '' }}. Your enquiry has been sent.</h3>
            <p class="mt-4 font-semibold text-ink">What happens next</p>
            <ol class="mt-2 list-decimal space-y-2 pl-5 text-ink-soft">
                <li>Someone at {{ site.name }} reads your enquiry.</li>
                <li v-if="site.response_time">We reply by phone or email {{ site.response_time }}.</li>
                <li v-else>We reply using the phone number or email address you gave.</li>
                <li>The first consultation is free, so there is nothing to pay at this stage.</li>
            </ol>
            <p v-if="site.phone && site.phone_href" class="mt-4 text-ink-soft">
                If it is urgent, call <a :href="site.phone_href" class="site-link">{{ site.phone }}</a><template v-if="site.office_hours"> ({{ site.office_hours }})</template>.
            </p>
            <button type="button" class="site-btn site-btn-secondary mt-6" @click="another">Send another enquiry</button>
        </div>

        <template v-else>
            <form class="space-y-5" novalidate :aria-busy="form.processing" @submit.prevent="submit">
                <div>
                    <label :for="`${id}-name`" class="site-label">Your name <span class="text-rose" aria-hidden="true">*</span></label>
                    <input
                        :id="`${id}-name`"
                        v-model="form.name"
                        type="text"
                        autocomplete="name"
                        required
                        class="site-input"
                        :aria-invalid="errorFor('name') ? 'true' : undefined"
                        :aria-describedby="errorFor('name') ? `${id}-name-error` : undefined"
                        @blur="leave('name')"
                    />
                    <p v-if="errorFor('name')" :id="`${id}-name-error`" class="site-error">{{ errorFor('name') }}</p>
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
                            :aria-invalid="errorFor('phone') ? 'true' : undefined"
                            :aria-describedby="`${id}-reply-hint${errorFor('phone') ? ` ${id}-phone-error` : ''}`"
                            @blur="form.phone && leave('phone')"
                        />
                        <p v-if="errorFor('phone')" :id="`${id}-phone-error`" class="site-error">{{ errorFor('phone') }}</p>
                    </div>
                    <div>
                        <label :for="`${id}-email`" class="site-label">Email</label>
                        <input
                            :id="`${id}-email`"
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            class="site-input"
                            :aria-invalid="errorFor('email') ? 'true' : undefined"
                            :aria-describedby="`${id}-reply-hint${errorFor('email') ? ` ${id}-email-error` : ''}`"
                            @blur="leave('email')"
                        />
                        <p v-if="errorFor('email')" :id="`${id}-email-error`" class="site-error">{{ errorFor('email') }}</p>
                    </div>
                    <p :id="`${id}-reply-hint`" class="-mt-3 text-sm text-ink-soft sm:col-span-2">Give a phone number, an email address, or both.</p>
                </div>

                <div v-if="site.services.length">
                    <label :for="`${id}-service`" class="site-label">Service <span class="font-normal text-ink-soft">(optional)</span></label>
                    <select :id="`${id}-service`" v-model="form.service" class="site-input">
                        <option value="">Not sure yet</option>
                        <option v-for="s in site.services" :key="s.slug" :value="s.title">{{ s.title }}</option>
                    </select>
                    <p v-if="serverErrors.service" class="site-error">{{ serverErrors.service }}</p>
                </div>

                <div>
                    <label :for="`${id}-message`" class="site-label">What do you need help with? <span class="text-rose" aria-hidden="true">*</span></label>
                    <textarea
                        :id="`${id}-message`"
                        v-model="form.message"
                        rows="5"
                        required
                        class="site-input"
                        :aria-invalid="errorFor('message') ? 'true' : undefined"
                        :aria-describedby="errorFor('message') ? `${id}-message-error` : undefined"
                        @blur="leave('message')"
                    ></textarea>
                    <p v-if="errorFor('message')" :id="`${id}-message-error`" class="site-error">{{ errorFor('message') }}</p>
                </div>

                <!-- Honeypot: hidden from people, filled in by bots. -->
                <div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
                    <label :for="`${id}-website`">Leave this field empty</label>
                    <input :id="`${id}-website`" v-model="form.website" type="text" tabindex="-1" autocomplete="off" />
                </div>

                <!-- Always in the page so screen readers announce the message when it appears. -->
                <div ref="formErrorEl" tabindex="-1" aria-live="assertive" class="outline-none [&:focus>div]:ring-2 [&:focus>div]:ring-rose">
                    <div v-if="serverErrors.form" class="rounded-site border border-rose bg-rose-tint p-4 text-rose-deep">
                        <p class="font-medium">{{ serverErrors.form }}</p>
                        <p v-if="(site.phone && site.phone_href) || site.whatsapp_url" class="mt-2">
                            To reach us now,
                            <template v-if="site.phone && site.phone_href">call <a :href="site.phone_href" class="font-semibold underline">{{ site.phone }}</a></template>
                            <template v-if="site.phone && site.phone_href && site.whatsapp_url"> or </template>
                            <template v-if="site.whatsapp_url"><a :href="whatsappHref ?? site.whatsapp_url" target="_blank" rel="noopener noreferrer" class="font-semibold underline">message us on WhatsApp</a></template>.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="site-btn site-btn-primary" :disabled="form.processing">
                        <svg v-if="form.processing" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" class="opacity-30" />
                            <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                        </svg>
                        {{ form.processing ? 'Sending enquiry' : 'Send enquiry' }}
                    </button>
                    <a v-if="whatsappHref" :href="whatsappHref" target="_blank" rel="noopener noreferrer" class="site-btn site-btn-secondary">
                        <SiteIcon name="whatsapp" />
                        Message on WhatsApp instead
                    </a>
                </div>

                <p class="text-sm text-ink-soft">
                    Fields marked <span class="text-rose" aria-hidden="true">*</span><span class="sr-only">with a star</span> are required. We use
                    these details only to reply to your enquiry.
                    <template v-if="site.privacy_published">
                        See our <Link href="/privacy" class="site-link">privacy policy</Link>.
                    </template>
                </p>
            </form>
        </template>
    </div>
</template>
