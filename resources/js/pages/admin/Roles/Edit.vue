<template>
    <AppLayout>
        <Head :title="t('roles.edit.title')" />

        <div class="space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">
                        {{ t('roles.edit.title') }}
                    </h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ t('roles.edit.subtitle') }}
                    </p>
                </div>

                <Link
                    :href="rolesIndex().url"
                    class="inline-flex items-center text-sm text-muted-foreground hover:text-foreground"
                >
                    {{ t('roles.back_to_list') }}
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-6">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ t('roles.form.details') }}</CardTitle>
                                <CardDescription>
                                    {{ t('roles.form.details_help_edit') }}
                                </CardDescription>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div>
                                    <Label for="name">
                                        {{ t('roles.form.name') }} <span class="text-destructive">*</span>
                                    </Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        required
                                        :placeholder="t('roles.form.name_placeholder')"
                                    />
                                    <InputError :message="form.errors.name" />
                                </div>

                                <div>
                                    <Label for="slug">{{ t('roles.form.slug') }}</Label>
                                    <Input
                                        id="slug"
                                        v-model="form.slug"
                                        type="text"
                                    />
                                    <InputError :message="form.errors.slug" />
                                </div>

                                <div>
                                    <Label for="description">{{ t('roles.form.description') }}</Label>
                                    <textarea
                                        id="description"
                                        v-model="form.description"
                                        rows="3"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2"
                                        :placeholder="t('roles.form.description_placeholder')"
                                    ></textarea>
                                    <InputError :message="form.errors.description" />
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <div class="space-y-6">
                        <Card>
                            <CardHeader class="space-y-1">
                                <CardTitle>{{ t('roles.form.permissions') }}</CardTitle>
                                <CardDescription>
                                    {{ t('common.selected') }}
                                    <span class="font-medium text-foreground">
                                        {{ formatNumber(form.permissions.length) }}
                                    </span>
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <PermissionTree v-model="form.permissions" :groups="permissionGroups" />
                                <InputError :message="form.errors.permissions" />
                            </CardContent>
                        </Card>
                    </div>
                </div>

                <div
                    class="sticky bottom-4 z-10 rounded-xl border border-border bg-background/80 p-4 shadow-sm backdrop-blur"
                >
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <Button as-child variant="secondary">
                            <Link :href="rolesIndex().url">{{ t('common.cancel') }}</Link>
                        </Button>
                        <Button type="submit" :loading="form.processing">
                            {{ t('roles.edit.save') }}
                        </Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import PermissionTree from './PermissionTree.vue';
import { useLocaleFormat } from '@/composables/useLocaleFormat';
import { useUnsavedWarning } from '@/composables/useUnsavedWarning';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { index as rolesIndex, update as rolesUpdate } from '@/routes/admin/roles';

type PermissionDef = { slug: string; label: string };
type ModuleDef = { key: string; label: string; permissions: PermissionDef[] };
type GroupDef = { key: string; label: string; modules: ModuleDef[] };

const props = defineProps<{
    role: {
        id: number;
        name: string;
        slug: string;
        description: string | null;
    };
    selectedPermissions: string[];
    permissionGroups: GroupDef[];
}>();

const form = useForm({
    name: props.role.name,
    slug: props.role.slug,
    description: props.role.description || '',
    permissions: props.selectedPermissions || ([] as string[]),
});

const { t } = useI18n();
const { formatNumber } = useLocaleFormat();

useUnsavedWarning(() => form.isDirty, t('common.leave_unsaved'));

const submit = () => {
    form.put(rolesUpdate({ role: props.role.id }).url);
};
</script>

