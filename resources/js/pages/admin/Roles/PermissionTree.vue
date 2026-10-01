<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue';
import IndeterminateCheckbox from '@/components/admin/IndeterminateCheckbox.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import {
    ChevronDown,
    ChevronRight,
    ChevronsDownUp,
    ChevronsUpDown,
    Search,
    Square,
    SquareCheck,
} from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';
import { useLocaleFormat } from '@/composables/useLocaleFormat';

type PermissionDef = { slug: string; label: string };
type ModuleDef = { key: string; label: string; permissions: PermissionDef[] };
type GroupDef = { key: string; label: string; modules: ModuleDef[] };

const props = defineProps<{
    groups: GroupDef[];
    modelValue: string[];
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string[]): void;
}>();

const selected = computed({
    get: () => new Set(props.modelValue),
    set: (set: Set<string>) => emit('update:modelValue', Array.from(set)),
});

const { t, te } = useI18n();
const { formatNumber } = useLocaleFormat();

// Labels arrive from the server in English; translate them by key and keep the server label when no translation exists.
const label = (key: string, fallback: string) => (te(key) ? t(key) : fallback);

const localizedGroups = computed<GroupDef[]>(() =>
    props.groups.map((g) => ({
        ...g,
        label: label(`permissions.groups.${g.key}`, g.label),
        modules: g.modules.map((m) => ({
            ...m,
            label: label(`permissions.modules.${m.key}`, m.label),
            permissions: m.permissions.map((p) => ({
                ...p,
                label: label(`permissions.actions.${p.slug.split('.').pop()}`, p.label),
            })),
        })),
    })),
);

const search = ref<string>('');

const expanded = reactive({
    groups: new Set<string>(),
    modules: new Set<string>(),
});

const normalize = (v: string) => v.trim().toLowerCase();

const filteredGroups = computed<GroupDef[]>(() => {
    const q = normalize(search.value);
    if (!q) return localizedGroups.value;

    const matches = (s: string) => normalize(s).includes(q);

    return localizedGroups.value
        .map((g) => {
            const groupMatches = matches(g.label) || matches(g.key);
            const modules = g.modules
                .map((m) => {
                    const perms = m.permissions.filter(
                        (p) => matches(p.label) || matches(p.slug),
                    );
                    const moduleMatches = matches(m.label) || matches(m.key);

                    if (groupMatches) {
                        return m;
                    }

                    if (moduleMatches) {
                        return m;
                    }

                    if (perms.length > 0) {
                        return { ...m, permissions: perms };
                    }

                    return null;
                })
                .filter(Boolean) as ModuleDef[];

            if (groupMatches) return { ...g, modules: g.modules };
            if (modules.length > 0) return { ...g, modules };
            return null;
        })
        .filter(Boolean) as GroupDef[];
});

const allVisibleSlugs = computed<string[]>(() =>
    filteredGroups.value.flatMap((g) =>
        g.modules.flatMap((m) => m.permissions.map((p) => p.slug)),
    ),
);

const selectedCountFor = (slugs: string[]) => {
    const set = selected.value;
    return slugs.reduce((acc, slug) => acc + (set.has(slug) ? 1 : 0), 0);
};

const groupPermissionSlugs = (group: GroupDef) =>
    group.modules.flatMap((m) => m.permissions.map((p) => p.slug));

const modulePermissionSlugs = (module: ModuleDef) => module.permissions.map((p) => p.slug);

const expandAllVisible = () => {
    expanded.groups = new Set(filteredGroups.value.map((g) => g.key));
    expanded.modules = new Set(
        filteredGroups.value.flatMap((g) =>
            g.modules.map((m) => `${g.key}:${m.key}`),
        ),
    );
};

const collapseAll = () => {
    expanded.groups = new Set();
    expanded.modules = new Set();
};

const toggleMany = (slugs: string[], value: boolean) => {
    const set = new Set(selected.value);
    for (const slug of slugs) {
        if (value) set.add(slug);
        else set.delete(slug);
    }
    selected.value = set;
};

const stateFor = (slugs: string[]) => {
    const set = selected.value;
    const count = slugs.filter((s) => set.has(s)).length;
    return {
        checked: count > 0 && count === slugs.length,
        indeterminate: count > 0 && count < slugs.length,
    };
};

const toggleGroupExpanded = (key: string) => {
    const next = new Set(expanded.groups);
    if (next.has(key)) next.delete(key);
    else next.add(key);
    expanded.groups = next;
};

const toggleModuleExpanded = (key: string) => {
    const next = new Set(expanded.modules);
    if (next.has(key)) next.delete(key);
    else next.add(key);
    expanded.modules = next;
};

const selectAllVisible = () => toggleMany(allVisibleSlugs.value, true);
const clearVisible = () => toggleMany(allVisibleSlugs.value, false);

watch(
    () => search.value,
    (v) => {
        if (normalize(v)) {
            // When searching, auto-expand matches so results are immediately visible.
            expandAllVisible();
        }
    },
);
</script>

<template>
    <div class="space-y-4">
        <div class="space-y-3">
            <div class="grid gap-2 sm:grid-cols-[1fr_auto] sm:items-center">
                <div class="relative">
                    <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        v-model="search"
                        class="pl-9"
                        :placeholder="t('roles.tree.search_placeholder')"
                    />
                </div>

                <div class="flex flex-wrap items-center justify-end gap-2">
                    <Badge variant="secondary">
                        {{ t('common.selected') }} {{ formatNumber(modelValue.length) }}
                    </Badge>

                    <Button type="button" variant="secondary" size="sm" @click="expandAllVisible">
                        <ChevronsUpDown class="mr-2 h-4 w-4" />
                        {{ t('roles.tree.expand') }}
                    </Button>
                    <Button type="button" variant="secondary" size="sm" @click="collapseAll">
                        <ChevronsDownUp class="mr-2 h-4 w-4" />
                        {{ t('roles.tree.collapse') }}
                    </Button>

                    <Separator orientation="vertical" class="mx-1 hidden h-8 sm:block" />

                    <Button type="button" variant="outline" size="sm" @click="selectAllVisible">
                        <SquareCheck class="mr-2 h-4 w-4" />
                        {{ t('roles.tree.select_all') }}
                    </Button>
                    <Button type="button" variant="outline" size="sm" @click="clearVisible">
                        <Square class="mr-2 h-4 w-4" />
                        {{ t('roles.tree.clear') }}
                    </Button>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-border bg-card">
            <div
                v-for="group in filteredGroups"
                :key="group.key"
                class="border-b border-border last:border-b-0"
            >
                <div class="flex items-center justify-between gap-4 p-4">
                    <div class="flex min-w-0 items-start gap-3">
                        <button
                            type="button"
                            class="mt-0.5 text-muted-foreground hover:text-foreground"
                            @click="toggleGroupExpanded(group.key)"
                            :aria-label="
                                expanded.groups.has(group.key) ? t('roles.tree.collapse_group') : t('roles.tree.expand_group')
                            "
                        >
                            <ChevronDown
                                v-if="expanded.groups.has(group.key)"
                                class="h-4 w-4"
                            />
                            <ChevronRight v-else class="h-4 w-4" />
                        </button>

                        <div class="mt-0.5">
                            <IndeterminateCheckbox
                                :model-value="stateFor(groupPermissionSlugs(group)).checked"
                                :indeterminate="stateFor(groupPermissionSlugs(group)).indeterminate"
                                @update:model-value="toggleMany(groupPermissionSlugs(group), $event)"
                            />
                        </div>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <div class="truncate font-medium">{{ group.label }}</div>
                                <Badge variant="secondary" class="shrink-0">
                                    {{ formatNumber(selectedCountFor(groupPermissionSlugs(group))) }}/{{
                                        formatNumber(groupPermissionSlugs(group).length)
                                    }}
                                </Badge>
                            </div>
                            <div class="mt-0.5 text-xs text-muted-foreground">
                                {{ t('roles.tree.group_help') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="expanded.groups.has(group.key)" class="px-4 pb-4">
                    <div class="space-y-3 rounded-lg border border-border bg-background p-4">
                        <div v-for="module in group.modules" :key="module.key">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex min-w-0 items-start gap-3">
                                    <button
                                        type="button"
                                        class="mt-0.5 text-muted-foreground hover:text-foreground"
                                        @click="toggleModuleExpanded(`${group.key}:${module.key}`)"
                                        :aria-label="
                                            expanded.modules.has(`${group.key}:${module.key}`)
                                                ? t('roles.tree.collapse_module')
                                                : t('roles.tree.expand_module')
                                        "
                                    >
                                        <ChevronDown
                                            v-if="expanded.modules.has(`${group.key}:${module.key}`)"
                                            class="h-4 w-4"
                                        />
                                        <ChevronRight v-else class="h-4 w-4" />
                                    </button>

                                    <div class="mt-0.5">
                                        <IndeterminateCheckbox
                                            :model-value="stateFor(modulePermissionSlugs(module)).checked"
                                            :indeterminate="stateFor(modulePermissionSlugs(module)).indeterminate"
                                            @update:model-value="toggleMany(modulePermissionSlugs(module), $event)"
                                        />
                                    </div>

                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <div class="truncate font-medium">{{ module.label }}</div>
                                            <span class="text-xs text-muted-foreground">
                                                {{ formatNumber(selectedCountFor(modulePermissionSlugs(module))) }}/{{
                                                    formatNumber(modulePermissionSlugs(module).length)
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="expanded.modules.has(`${group.key}:${module.key}`)"
                                class="mt-3 grid gap-2 pl-8 sm:grid-cols-2 lg:grid-cols-4"
                            >
                                <label
                                    v-for="p in module.permissions"
                                    :key="p.slug"
                                    class="group flex cursor-pointer items-center gap-2 rounded-md border border-border bg-card px-3 py-2 text-sm transition-colors hover:bg-muted"
                                >
                                    <input
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-input"
                                        :checked="selected.has(p.slug)"
                                        @change="toggleMany([p.slug], ($event.target as HTMLInputElement).checked)"
                                    />
                                    <span class="truncate">{{ p.label }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

