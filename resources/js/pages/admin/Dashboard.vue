<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Briefcase, CircleCheck, CircleDashed, ExternalLink, HelpCircle, Inbox, MessageSquareQuote, UsersRound } from 'lucide-vue-next';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

type Status = 'new' | 'contacted' | 'closed';

const props = defineProps<{
    stats: {
        new_enquiries: number;
        enquiries: number;
        services: number;
        team_members: number;
        faqs: number;
        testimonials: number;
    };
    recentEnquiries: Array<{ id: number; name: string; service: string | null; message: string; status: Status; created_at: string }>;
    checklist: Array<{ key: string; done: boolean; href: string }>;
}>();

const { t, locale } = useI18n();

const cards = computed(() => [
    { key: 'services', value: props.stats.services, href: '/admin/services', icon: Briefcase },
    { key: 'team_members', value: props.stats.team_members, href: '/admin/team-members', icon: UsersRound },
    { key: 'faqs', value: props.stats.faqs, href: '/admin/faqs', icon: HelpCircle },
    { key: 'testimonials', value: props.stats.testimonials, href: '/admin/testimonials', icon: MessageSquareQuote },
]);

const remaining = computed(() => props.checklist.filter((item) => !item.done).length);

const statusClass = (status: Status) =>
    ({
        new: 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-400/30 dark:bg-amber-900/20 dark:text-amber-300',
        contacted: 'border-blue-300 bg-blue-50 text-blue-800 dark:border-blue-400/30 dark:bg-blue-900/20 dark:text-blue-300',
        closed: 'border-border bg-muted text-muted-foreground',
    })[status];

const formatDate = (value: string) =>
    new Date(value).toLocaleString(locale.value === 'bn' ? 'bn-BD' : 'en-GB', {
        day: 'numeric',
        month: 'short',
        hour: 'numeric',
        minute: '2-digit',
    });
</script>

<template>
    <Head :title="t('dashboard.title')" />

    <AppLayout>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ t('dashboard.title') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('dashboard.subtitle') }}</p>
            </div>
            <a
                href="/"
                target="_blank"
                rel="noopener"
                class="inline-flex min-h-10 items-center gap-2 rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-muted sm:hidden"
            >
                <ExternalLink class="size-4" />
                {{ t('dashboard.view_site') }}
            </a>
        </div>

        <div class="grid grid-cols-2 gap-4 xl:grid-cols-5">
            <Link
                href="/admin/enquiries?status=new"
                class="rounded-lg border border-primary bg-primary p-5 text-primary-foreground transition-colors hover:bg-primary/90 col-span-2 xl:col-span-1"
            >
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium opacity-80">{{ t('dashboard.stats.new_enquiries') }}</span>
                    <Inbox class="size-5 opacity-80" />
                </div>
                <p class="mt-3 text-4xl font-semibold tabular-nums">{{ stats.new_enquiries }}</p>
                <p class="mt-1 text-sm opacity-80">{{ t('dashboard.stats.enquiries_total', { count: stats.enquiries }) }}</p>
            </Link>

            <Link
                v-for="card in cards"
                :key="card.key"
                :href="card.href"
                class="rounded-lg border border-border bg-card p-4 transition-colors hover:bg-muted/50 sm:p-5"
            >
                <div class="flex items-center justify-between text-muted-foreground">
                    <span class="text-sm font-medium">{{ t(`dashboard.stats.${card.key}`) }}</span>
                    <component :is="card.icon" class="size-5" />
                </div>
                <p class="mt-3 text-4xl font-semibold tabular-nums">{{ card.value }}</p>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('dashboard.stats.shown_on_site') }}</p>
            </Link>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]">
            <section class="rounded-lg border border-border bg-card" aria-labelledby="dashboard-recent">
                <div class="flex items-center justify-between gap-3 border-b border-border px-5 py-4">
                    <h2 id="dashboard-recent" class="text-base font-semibold">{{ t('dashboard.recent.title') }}</h2>
                    <Link href="/admin/enquiries" class="text-sm font-medium text-primary underline-offset-4 hover:underline">
                        {{ t('dashboard.recent.view_all') }}
                    </Link>
                </div>
                <ul v-if="recentEnquiries.length" class="divide-y divide-border">
                    <li v-for="enquiry in recentEnquiries" :key="enquiry.id" class="px-5 py-4">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="font-medium">{{ enquiry.name }}</p>
                            <span :class="['rounded-md border px-2 py-0.5 text-xs font-medium', statusClass(enquiry.status)]">
                                {{ t(`enquiries.status.${enquiry.status}`) }}
                            </span>
                        </div>
                        <p class="mt-0.5 text-sm text-muted-foreground">
                            {{ formatDate(enquiry.created_at) }}<template v-if="enquiry.service">, {{ enquiry.service }}</template>
                        </p>
                        <p class="mt-1.5 line-clamp-2 text-sm">{{ enquiry.message }}</p>
                    </li>
                </ul>
                <p v-else class="px-5 py-10 text-center text-sm text-muted-foreground">{{ t('dashboard.recent.empty') }}</p>
            </section>

            <section class="rounded-lg border border-border bg-card" aria-labelledby="dashboard-checklist">
                <div class="border-b border-border px-5 py-4">
                    <h2 id="dashboard-checklist" class="text-base font-semibold">{{ t('dashboard.checklist.title') }}</h2>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        {{ remaining ? t('dashboard.checklist.remaining', { count: remaining }) : t('dashboard.checklist.all_done') }}
                    </p>
                </div>
                <ul class="divide-y divide-border">
                    <li v-for="item in checklist" :key="item.key">
                        <component
                            :is="item.done ? 'div' : Link"
                            :href="item.done ? undefined : item.href"
                            :class="['flex items-start gap-3 px-5 py-3.5', !item.done && 'hover:bg-muted/50']"
                        >
                            <CircleCheck v-if="item.done" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
                            <CircleDashed v-else class="mt-0.5 size-5 shrink-0 text-amber-600" />
                            <span>
                                <span :class="['block text-sm font-medium', item.done && 'text-muted-foreground line-through']">
                                    {{ t(`dashboard.checklist.items.${item.key}.label`) }}
                                </span>
                                <span v-if="!item.done" class="block text-sm text-muted-foreground">
                                    {{ t(`dashboard.checklist.items.${item.key}.help`) }}
                                </span>
                            </span>
                        </component>
                    </li>
                </ul>
            </section>
        </div>
    </AppLayout>
</template>
