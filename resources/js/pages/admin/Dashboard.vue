<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Briefcase, CircleCheck, CircleDashed, ExternalLink, HelpCircle, Inbox, MessageSquareQuote, UsersRound } from 'lucide-vue-next';
import { useLocaleFormat } from '@/composables/useLocaleFormat';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

type Status = 'new' | 'contacted' | 'closed';

const props = defineProps<{
    /** A count is null when the signed-in person may not open that list; its card is hidden. */
    stats: {
        new_enquiries: number | null;
        enquiries: number | null;
        services: number | null;
        team_members: number | null;
        faqs: number | null;
        testimonials: number | null;
    };
    recentEnquiries: Array<{ id: number; name: string; service: string | null; message: string; status: Status; created_at: string }> | null;
    weeklyEnquiries: Array<{ from: string; to: string; count: number }> | null;
    checklist: Array<{ key: string; done: boolean; href: string }>;
}>();

const { t, locale } = useI18n();
const { formatNumber } = useLocaleFormat();

const cards = computed(() =>
    [
        { key: 'services', value: props.stats.services, href: '/admin/services', icon: Briefcase },
        { key: 'team_members', value: props.stats.team_members, href: '/admin/team-members', icon: UsersRound },
        { key: 'faqs', value: props.stats.faqs, href: '/admin/faqs', icon: HelpCircle },
        { key: 'testimonials', value: props.stats.testimonials, href: '/admin/testimonials', icon: MessageSquareQuote },
    ].filter((card) => card.value !== null),
);

// Bar chart: heights are a share of the busiest week, with room left for the count above each bar.
const weekMax = computed(() => Math.max(1, ...(props.weeklyEnquiries ?? []).map((w) => w.count)));
const weekTotal = computed(() => (props.weeklyEnquiries ?? []).reduce((sum, w) => sum + w.count, 0));
const shortDate = (value: string) => new Date(`${value}T00:00:00`).toLocaleDateString(locale.value === 'bn' ? 'bn-BD' : 'en-GB', { day: 'numeric', month: 'short' });

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
                v-if="stats.new_enquiries !== null"
                href="/admin/enquiries?status=new"
                class="rounded-lg border border-primary bg-primary p-5 text-primary-foreground transition-colors hover:bg-primary/90 col-span-2 xl:col-span-1"
            >
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium opacity-80">{{ t('dashboard.stats.new_enquiries') }}</span>
                    <Inbox class="size-5 opacity-80" />
                </div>
                <p class="mt-3 text-4xl font-semibold tabular-nums">{{ formatNumber(stats.new_enquiries) }}</p>
                <p class="mt-1 text-sm opacity-80">{{ t('dashboard.stats.enquiries_total', { count: formatNumber(stats.enquiries) }) }}</p>
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
                <p class="mt-3 text-4xl font-semibold tabular-nums">{{ formatNumber(card.value) }}</p>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('dashboard.stats.shown_on_site') }}</p>
            </Link>
        </div>

        <section v-if="weeklyEnquiries" class="rounded-lg border border-border bg-card p-5" aria-labelledby="dashboard-weekly">
            <div class="flex flex-wrap items-baseline justify-between gap-2">
                <h2 id="dashboard-weekly" class="text-base font-semibold">{{ t('dashboard.weekly.title') }}</h2>
                <p class="text-sm text-muted-foreground">{{ t('dashboard.weekly.total', { count: formatNumber(weekTotal) }) }}</p>
            </div>
            <ol class="mt-5 grid h-40 grid-cols-8 items-end gap-2 sm:gap-4" :aria-label="t('dashboard.weekly.title')">
                <li v-for="week in weeklyEnquiries" :key="week.from" class="flex h-full flex-col justify-end text-center">
                    <span class="text-sm font-semibold tabular-nums">{{ formatNumber(week.count) }}</span>
                    <span
                        :class="['mt-1 block w-full rounded-t', week.count ? 'bg-primary' : 'bg-muted']"
                        :style="{ height: `${Math.max(3, (week.count / weekMax) * 78)}%` }"
                        aria-hidden="true"
                    ></span>
                    <span class="mt-2 block truncate text-xs text-muted-foreground">
                        <span class="sr-only">{{ t('dashboard.weekly.week_from') }}</span>{{ shortDate(week.from) }}
                    </span>
                </li>
            </ol>
            <p class="mt-3 text-xs text-muted-foreground">{{ t('dashboard.weekly.help') }}</p>
        </section>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]">
            <section v-if="recentEnquiries" class="rounded-lg border border-border bg-card" aria-labelledby="dashboard-recent">
                <div class="flex items-center justify-between gap-3 border-b border-border px-5 py-4">
                    <h2 id="dashboard-recent" class="text-base font-semibold">{{ t('dashboard.recent.title') }}</h2>
                    <Link href="/admin/enquiries" class="text-sm font-medium text-primary underline-offset-4 hover:underline">
                        {{ t('dashboard.recent.view_all') }}
                    </Link>
                </div>
                <ul v-if="recentEnquiries.length" class="divide-y divide-border">
                    <li v-for="enquiry in recentEnquiries" :key="enquiry.id" class="px-5 py-4">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <Link :href="`/admin/enquiries/${enquiry.id}`" class="font-medium underline-offset-4 hover:underline">{{ enquiry.name }}</Link>
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

            <section v-if="checklist.length" class="rounded-lg border border-border bg-card" aria-labelledby="dashboard-checklist">
                <div class="border-b border-border px-5 py-4">
                    <h2 id="dashboard-checklist" class="text-base font-semibold">{{ t('dashboard.checklist.title') }}</h2>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        {{ remaining ? t('dashboard.checklist.remaining', { count: formatNumber(remaining) }) : t('dashboard.checklist.all_done') }}
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
