<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useConfirm } from '@/composables/useConfirm';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowLeft, Mail, MessageCircle, Phone, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

type Status = 'new' | 'contacted' | 'closed';

const props = defineProps<{
    enquiry: {
        id: number;
        name: string;
        phone: string | null;
        email: string | null;
        service: string | null;
        message: string;
        status: Status;
        assigned_to: number | null;
        created_at: string;
        whatsapp_number: string | null;
        notes: Array<{ id: number; body: string; author: string | null; created_at: string }>;
    };
    statuses: Status[];
    users: Array<{ id: number; name: string }>;
}>();

const { t, locale } = useI18n();
const { can } = usePermissions();
const { confirm } = useConfirm();
const page = usePage<any>();

const canUpdate = computed(() => can('enquiries.update'));
const canDelete = computed(() => can('enquiries.delete'));
const firm = computed(() => page.props.name as string);

// Replies open in the staff member's own phone, WhatsApp or mail app with a greeting already typed.
const greeting = computed(() => {
    const about = props.enquiry.service ? ` about ${props.enquiry.service}` : '';
    return `Hello ${props.enquiry.name}, this is ${firm.value}. Thank you for your enquiry${about}.`;
});

const whatsappHref = computed(() =>
    props.enquiry.whatsapp_number ? `https://wa.me/${props.enquiry.whatsapp_number}?text=${encodeURIComponent(greeting.value)}` : null,
);

const mailHref = computed(() =>
    props.enquiry.email
        ? `mailto:${props.enquiry.email}?subject=${encodeURIComponent(`Your enquiry to ${firm.value}`)}&body=${encodeURIComponent(`${greeting.value}\n\n`)}`
        : null,
);

const note = useForm({ body: '' });

const formatDate = (value: string) =>
    new Date(value).toLocaleString(locale.value === 'bn' ? 'bn-BD' : 'en-GB', { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: '2-digit' });

const update = (data: { status?: string; assigned_to?: number | null }) =>
    router.put(`/admin/enquiries/${props.enquiry.id}`, data, { preserveScroll: true, preserveState: true });

const addNote = () => note.post(`/admin/enquiries/${props.enquiry.id}/notes`, { preserveScroll: true, onSuccess: () => note.reset() });

const remove = async () => {
    const ok = await confirm({
        title: t('enquiries.delete.title'),
        description: t('enquiries.delete.description'),
        details: props.enquiry.name,
        confirmText: t('enquiries.delete.confirm'),
        confirmVariant: 'destructive',
    });
    if (ok) router.delete(`/admin/enquiries/${props.enquiry.id}`);
};
</script>

<template>
    <AppLayout>
        <Head :title="`${t('enquiries.detail.title')}: ${enquiry.name}`" />

        <div>
            <Link href="/admin/enquiries" class="inline-flex min-h-10 items-center gap-2 text-sm font-medium text-muted-foreground hover:text-foreground">
                <ArrowLeft class="size-4" />
                {{ t('enquiries.back_to_list') }}
            </Link>
            <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">{{ enquiry.name }}</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ t('enquiries.detail.received', { date: formatDate(enquiry.created_at) }) }}<template v-if="enquiry.service">, {{ enquiry.service }}</template>
                    </p>
                </div>
                <button v-if="canDelete" type="button" class="inline-flex min-h-10 items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-destructive hover:bg-destructive/10" @click="remove">
                    <Trash2 class="size-4" />
                    {{ t('common.delete') }}
                </button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]">
            <div class="space-y-6">
                <section class="rounded-lg border border-border bg-card p-5 sm:p-6" aria-labelledby="enquiry-message">
                    <h2 id="enquiry-message" class="text-lg font-semibold">{{ t('enquiries.detail.message') }}</h2>
                    <p class="mt-3 leading-relaxed whitespace-pre-line">{{ enquiry.message }}</p>

                    <h3 class="mt-6 text-sm font-semibold">{{ t('enquiries.detail.reply') }}</h3>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a v-if="enquiry.phone" :href="`tel:${enquiry.phone}`" class="inline-flex min-h-11 items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                            <Phone class="size-4" />
                            {{ t('enquiries.detail.call', { phone: enquiry.phone }) }}
                        </a>
                        <a v-if="whatsappHref" :href="whatsappHref" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center gap-2 rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-muted">
                            <MessageCircle class="size-4" />
                            {{ t('enquiries.detail.whatsapp') }}
                        </a>
                        <a v-if="mailHref" :href="mailHref" class="inline-flex min-h-11 items-center gap-2 rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-muted">
                            <Mail class="size-4" />
                            {{ t('enquiries.detail.email', { email: enquiry.email }) }}
                        </a>
                    </div>
                    <p class="mt-3 text-xs text-muted-foreground">{{ t('enquiries.detail.reply_help') }}</p>
                </section>

                <section class="rounded-lg border border-border bg-card p-5 sm:p-6" aria-labelledby="enquiry-notes">
                    <h2 id="enquiry-notes" class="text-lg font-semibold">{{ t('enquiries.detail.notes') }}</h2>
                    <p class="mt-1 text-sm text-muted-foreground">{{ t('enquiries.detail.notes_help') }}</p>

                    <form v-if="canUpdate" class="mt-4" @submit.prevent="addNote">
                        <label for="enquiry-note" class="sr-only">{{ t('enquiries.detail.add_note') }}</label>
                        <textarea
                            id="enquiry-note"
                            v-model="note.body"
                            rows="3"
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                            :placeholder="t('enquiries.detail.note_placeholder')"
                            :aria-invalid="note.errors.body ? true : undefined"
                            @input="note.clearErrors('body')"
                        />
                        <p v-if="note.errors.body" class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-500">{{ note.errors.body }}</p>
                        <Button type="submit" class="mt-3" :loading="note.processing">{{ t('enquiries.detail.add_note') }}</Button>
                    </form>

                    <ol v-if="enquiry.notes.length" class="mt-6 space-y-4 border-t border-border pt-5">
                        <li v-for="item in enquiry.notes" :key="item.id" class="border-l-2 border-border pl-4">
                            <p class="text-xs text-muted-foreground">
                                <span class="font-semibold text-foreground">{{ item.author ?? t('enquiries.detail.removed_user') }}</span>, {{ formatDate(item.created_at) }}
                            </p>
                            <p class="mt-1 text-sm leading-relaxed whitespace-pre-line">{{ item.body }}</p>
                        </li>
                    </ol>
                    <p v-else class="mt-6 border-t border-border pt-5 text-sm text-muted-foreground">{{ t('enquiries.detail.no_notes') }}</p>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="rounded-lg border border-border bg-card p-5 sm:p-6" aria-labelledby="enquiry-handling">
                    <h2 id="enquiry-handling" class="text-lg font-semibold">{{ t('enquiries.detail.handling') }}</h2>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="enquiry-status" class="mb-1.5 block text-sm font-medium">{{ t('enquiries.columns.status') }}</label>
                            <select id="enquiry-status" :value="enquiry.status" class="w-full rounded-md border border-input bg-background px-3 py-2" :disabled="!canUpdate" @change="update({ status: ($event.target as HTMLSelectElement).value })">
                                <option v-for="status in statuses" :key="status" :value="status">{{ t(`enquiries.status.${status}`) }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="enquiry-assignee" class="mb-1.5 block text-sm font-medium">{{ t('enquiries.columns.assigned') }}</label>
                            <select
                                id="enquiry-assignee"
                                :value="enquiry.assigned_to ?? ''"
                                class="w-full rounded-md border border-input bg-background px-3 py-2"
                                :disabled="!canUpdate"
                                @change="update({ assigned_to: Number(($event.target as HTMLSelectElement).value) || null })"
                            >
                                <option value="">{{ t('enquiries.unassigned') }}</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-border bg-card p-5 sm:p-6" aria-labelledby="enquiry-contact">
                    <h2 id="enquiry-contact" class="text-lg font-semibold">{{ t('enquiries.detail.contact') }}</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div>
                            <dt class="text-muted-foreground">{{ t('enquiries.detail.phone') }}</dt>
                            <dd class="font-medium">{{ enquiry.phone ?? t('enquiries.detail.not_given') }}</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">{{ t('enquiries.detail.email_label') }}</dt>
                            <dd class="font-medium break-all">{{ enquiry.email ?? t('enquiries.detail.not_given') }}</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">{{ t('enquiries.detail.service') }}</dt>
                            <dd class="font-medium">{{ enquiry.service ?? t('enquiries.detail.not_given') }}</dd>
                        </div>
                    </dl>
                </section>
            </aside>
        </div>
    </AppLayout>
</template>
