<template>
    <AppLayout>
        <Head :title="t('users.create.title')" />

        <div class="space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">
                        {{ t('users.create.title') }}
                    </h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ t('users.create.subtitle') }}
                    </p>
                </div>

                <Link
                    href="/admin/users"
                    class="inline-flex items-center text-sm text-muted-foreground hover:text-foreground"
                >
                    {{ t('users.back_to_list') }}
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>{{ t('users.form.details') }}</CardTitle>
                            <CardDescription>
                                {{ t('users.form.details_help') }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="space-y-2">
                                <Label for="name">
                                    {{ t('users.form.name') }} <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    :placeholder="t('users.form.name_placeholder')"
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="space-y-2">
                                <Label for="email">
                                    {{ t('users.form.email') }} <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    placeholder="email@example.com"
                                />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="password">
                                        {{ t('users.form.password') }} <span class="text-destructive">*</span>
                                    </Label>
                                    <Input
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        required
                                        placeholder="••••••••"
                                        autocomplete="new-password"
                                    />
                                    <InputError :message="form.errors.password" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="password_confirmation">
                                        {{ t('users.form.password_confirmation') }} <span class="text-destructive">*</span>
                                    </Label>
                                    <Input
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        type="password"
                                        required
                                        placeholder="••••••••"
                                        autocomplete="new-password"
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="space-y-1">
                            <CardTitle>{{ t('users.form.roles') }}</CardTitle>
                            <CardDescription>
                                {{ t('users.form.roles_optional') }} {{ t('common.selected') }}
                                <span class="font-medium text-foreground">
                                    {{ formatNumber(form.roles.length) }}
                                </span>
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div v-if="roles.length" class="grid gap-2 md:grid-cols-2">
                                <label
                                    v-for="role in roles"
                                    :key="role.id"
                                    class="flex cursor-pointer items-center gap-2 rounded-md border border-border bg-background px-3 py-2 text-sm hover:bg-muted"
                                >
                                    <input
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-input"
                                        :checked="form.roles.includes(role.id)"
                                        @change="toggleRole(role.id, ($event.target as HTMLInputElement).checked)"
                                    />
                                    <span class="font-medium">{{ role.name }}</span>
                                    <span class="text-xs text-muted-foreground">
                                        ({{ role.slug }})
                                    </span>
                                </label>
                            </div>
                            <div v-else class="text-sm text-muted-foreground">
                                {{ t('users.form.no_roles') }}
                            </div>
                            <InputError :message="form.errors.roles" />
                        </CardContent>
                    </Card>
                </div>

                <div
                    class="sticky bottom-4 z-10 rounded-xl border border-border bg-background/80 p-4 shadow-sm backdrop-blur"
                >
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <Button as-child variant="secondary">
                            <Link href="/admin/users">{{ t('common.cancel') }}</Link>
                        </Button>
                        <Button type="submit" :loading="form.processing">
                            {{ t('users.create.save') }}
                        </Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { useLocaleFormat } from '@/composables/useLocaleFormat';
import { useUnsavedWarning } from '@/composables/useUnsavedWarning';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

defineProps<{
    roles: Array<{ id: number; name: string; slug: string }>;
}>();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [] as number[],
});

const { t } = useI18n();
const { formatNumber } = useLocaleFormat();

useUnsavedWarning(() => form.isDirty, t('common.leave_unsaved'));

const toggleRole = (id: number, checked: boolean) => {
    const next = new Set(form.roles);
    if (checked) next.add(id);
    else next.delete(id);
    form.roles = Array.from(next);
};

const submit = () => {
    form.post('/admin/users');
};
</script>

