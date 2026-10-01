<template>
    <AppLayout>
        <Head :title="t('users.edit.title')" />

        <div class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-2xl font-semibold tracking-tight">{{ t('users.edit.title') }}</h1>
                <Link href="/admin/users" class="text-muted-foreground hover:text-foreground">
                    {{ t('users.back_to_list') }}
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-lg border border-border bg-card p-6">
                    <h2 class="mb-4 text-xl font-semibold">{{ t('users.edit.user') }}</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-muted-foreground">{{ t('users.form.name') }}</div>
                            <div class="mt-1">{{ user.name }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-muted-foreground">{{ t('users.form.email') }}</div>
                            <div class="mt-1">{{ user.email }}</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-border bg-card p-6">
                    <h2 class="mb-4 text-xl font-semibold">{{ t('users.form.roles') }}</h2>
                    <div class="grid gap-2 md:grid-cols-2">
                        <label
                            v-for="role in roles"
                            :key="role.id"
                            class="flex items-center gap-2 rounded-md border border-border bg-background px-3 py-2"
                        >
                            <input
                                type="checkbox"
                                class="h-4 w-4 rounded border-input"
                                :checked="form.roles.includes(role.id)"
                                @change="toggleRole(role.id, ($event.target as HTMLInputElement).checked)"
                            />
                            <span class="font-medium">{{ role.name }}</span>
                            <span class="text-xs text-muted-foreground">({{ role.slug }})</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <Button as-child variant="secondary">
                        <Link href="/admin/users">{{ t('common.cancel') }}</Link>
                    </Button>
                    <Button type="submit" :loading="form.processing">
                        {{ t('common.save') }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { useUnsavedWarning } from '@/composables/useUnsavedWarning';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    user: { id: number; name: string; email: string };
    roles: Array<{ id: number; name: string; slug: string }>;
    selectedRoles: number[];
}>();

const form = useForm({
    roles: props.selectedRoles || ([] as number[]),
});

const { t } = useI18n();

useUnsavedWarning(() => form.isDirty, t('common.leave_unsaved'));

const toggleRole = (id: number, checked: boolean) => {
    const next = new Set(form.roles);
    if (checked) next.add(id);
    else next.delete(id);
    form.roles = Array.from(next);
};

const submit = () => {
    form.put(`/admin/users/${props.user.id}`);
};
</script>

