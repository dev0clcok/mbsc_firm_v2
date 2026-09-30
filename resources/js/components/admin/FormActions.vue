<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { ExternalLink } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

/**
 * Save bar that stays at the bottom of the screen on long forms, with an
 * unsaved-changes note and an optional link to the item on the public site.
 */
defineProps<{
    saveLabel: string;
    cancelHref: string;
    processing?: boolean;
    dirty?: boolean;
    viewHref?: string;
}>();

const { t } = useI18n();
</script>

<template>
    <div class="sticky bottom-0 z-10 -mx-4 flex flex-wrap items-center justify-between gap-3 border-t border-border bg-background/95 px-4 py-3 backdrop-blur">
        <div class="flex items-center gap-4 text-sm">
            <a
                v-if="viewHref"
                :href="viewHref"
                target="_blank"
                rel="noopener"
                class="inline-flex min-h-10 items-center gap-2 font-medium text-primary underline-offset-4 hover:underline"
            >
                <ExternalLink class="size-4" />
                {{ t('common.view_on_site') }}
            </a>
            <span v-if="dirty" class="text-amber-700 dark:text-amber-400" role="status">{{ t('common.unsaved_changes') }}</span>
        </div>
        <div class="flex items-center gap-3">
            <Button as-child variant="secondary">
                <Link :href="cancelHref">{{ t('common.cancel') }}</Link>
            </Button>
            <Button type="submit" :loading="processing">{{ saveLabel }}</Button>
        </div>
    </div>
</template>
