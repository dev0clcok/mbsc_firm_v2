<script setup lang="ts">
import SiteIcon from '@/components/site/SiteIcon.vue';

export interface TeamMember {
    name: string;
    position: string | null;
    specialization: string | null;
    image: string | null;
    social_links: Array<{ platform: string; url: string }>;
}

defineProps<{ members: TeamMember[] }>();

const linkHref = (link: { platform: string; url: string }) => (link.platform === 'email' ? `mailto:${link.url}` : link.url);
</script>

<template>
    <!-- Two columns only from 1024px: below that a card is too narrow for the portrait beside the text. -->
    <ul :class="['grid gap-6', members.length > 1 && 'lg:grid-cols-2']">
        <li v-for="member in members" :key="member.name" class="site-card flex flex-col gap-6 p-6 sm:flex-row sm:items-center">
            <img
                v-if="member.image"
                :src="member.image"
                :alt="`Portrait of ${member.name}`"
                width="176"
                height="176"
                loading="lazy"
                decoding="async"
                class="h-44 w-44 shrink-0 rounded-card object-cover"
            />
            <div class="min-w-0">
                <h3 class="font-display text-2xl font-medium text-ink [overflow-wrap:anywhere]">{{ member.name }}</h3>
                <p v-if="member.position" class="mt-1 font-semibold text-rose">{{ member.position }}</p>
                <p v-if="member.specialization" class="text-ink-soft">{{ member.specialization }}</p>
                <ul v-if="member.social_links.length" class="mt-3 -ml-3 flex">
                    <li v-for="link in member.social_links" :key="link.platform + link.url">
                        <a
                            :href="linkHref(link)"
                            :target="link.platform === 'email' ? undefined : '_blank'"
                            :rel="link.platform === 'email' ? undefined : 'noopener noreferrer'"
                            :aria-label="`${member.name} on ${link.platform}`"
                            class="flex h-11 w-11 items-center justify-center text-ink-soft transition-colors hover:text-rose"
                        >
                            <SiteIcon :name="link.platform as any" />
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</template>
