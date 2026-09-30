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
    <ul :class="['grid gap-8', members.length > 1 && 'sm:grid-cols-2']">
        <li v-for="member in members" :key="member.name" class="flex items-start gap-5">
            <img
                v-if="member.image"
                :src="member.image"
                :alt="`Portrait of ${member.name}`"
                width="112"
                height="112"
                loading="lazy"
                decoding="async"
                class="h-28 w-28 shrink-0 rounded-site object-cover"
            />
            <div class="min-w-0">
                <h3 class="site-heading">{{ member.name }}</h3>
                <p v-if="member.position" class="font-semibold text-rose">{{ member.position }}</p>
                <p v-if="member.specialization" class="text-ink-soft">{{ member.specialization }}</p>
                <ul v-if="member.social_links.length" class="mt-2 -ml-3 flex">
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
