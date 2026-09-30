<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';
import { useSite } from '@/composables/useSite';

/** Office contact details as a ruled list. Rows with no value are left out. */
defineProps<{ onInk?: boolean }>();

const site = useSite();
</script>

<template>
    <dl :class="['divide-y border-y', onInk ? 'divide-white/15 border-white/15' : 'divide-rule border-rule']">
        <div v-if="site.phone && site.phone_href" class="flex items-baseline gap-4 py-2">
            <dt class="flex min-h-11 w-28 shrink-0 items-center gap-2 text-sm font-semibold"><SiteIcon name="phone" />Phone</dt>
            <dd><a :href="site.phone_href" :class="[onInk ? 'underline underline-offset-4 hover:no-underline' : 'site-link', 'inline-flex min-h-11 items-center']">{{ site.phone }}</a></dd>
        </div>
        <div v-if="site.whatsapp_url" class="flex items-baseline gap-4 py-2">
            <dt class="flex min-h-11 w-28 shrink-0 items-center gap-2 text-sm font-semibold"><SiteIcon name="whatsapp" />WhatsApp</dt>
            <dd>
                <a :href="site.whatsapp_url" target="_blank" rel="noopener noreferrer" :class="[onInk ? 'underline underline-offset-4 hover:no-underline' : 'site-link', 'inline-flex min-h-11 items-center']">Message us on WhatsApp</a>
            </dd>
        </div>
        <div v-if="site.email" class="flex items-baseline gap-4 py-2">
            <dt class="flex min-h-11 w-28 shrink-0 items-center gap-2 text-sm font-semibold"><SiteIcon name="mail" />Email</dt>
            <dd class="min-w-0">
                <a :href="`mailto:${site.email}`" :class="['inline-flex min-h-11 items-center break-all', onInk ? 'underline underline-offset-4 hover:no-underline' : 'site-link']">{{ site.email }}</a>
            </dd>
        </div>
        <div v-if="site.address" class="flex items-baseline gap-4 py-2">
            <dt class="flex min-h-11 w-28 shrink-0 items-center gap-2 text-sm font-semibold"><SiteIcon name="pin" />Office</dt>
            <dd>
                <address class="py-2.5 not-italic">{{ site.address }}</address>
                <a v-if="site.maps_url" :href="site.maps_url" target="_blank" rel="noopener noreferrer" :class="['inline-flex min-h-11 items-center', onInk ? 'underline underline-offset-4 hover:no-underline' : 'site-link']">Open in Google Maps</a>
            </dd>
        </div>
        <div v-if="site.office_hours" class="flex items-baseline gap-4 py-2">
            <dt class="flex min-h-11 w-28 shrink-0 items-center gap-2 text-sm font-semibold"><SiteIcon name="clock" />Hours</dt>
            <dd class="py-2.5">{{ site.office_hours }}</dd>
        </div>
    </dl>
</template>
