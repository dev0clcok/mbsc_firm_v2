<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { setAppLocale, type AppLocale } from '@/i18n';
import { useI18n } from 'vue-i18n';
import { computed } from 'vue';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const { t, locale } = useI18n();

const current = computed({
    get: () => String(locale.value) as AppLocale,
    set: (v: AppLocale) => {
        locale.value = v;
        setAppLocale(v);
    },
});
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex w-full items-center justify-between gap-2">
            <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
            </div>

            <div class="flex items-center gap-2">
                <label class="sr-only" for="rg-locale">
                    {{ t('language.label') }}
                </label>
                <select
                    id="rg-locale"
                    v-model="current"
                    class="h-9 rounded-md border border-input bg-background px-2 text-sm text-foreground"
                >
                    <option value="en">{{ t('language.en') }}</option>
                    <option value="bn">{{ t('language.bn') }}</option>
                </select>
            </div>
        </div>
    </header>
</template>
