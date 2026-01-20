<template>
    <AppLayout>
        <Head title="Create User" />

        <div class="space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">
                        Create User
                    </h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Create a new user and optionally assign roles.
                    </p>
                </div>

                <Link
                    href="/admin/users"
                    class="inline-flex items-center text-sm text-muted-foreground hover:text-foreground"
                >
                    Back to Users
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>User Details</CardTitle>
                            <CardDescription>
                                Basic account information.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="space-y-2">
                                <Label for="name">
                                    Name <span class="text-destructive">*</span>
                                </Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    placeholder="Full name"
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="space-y-2">
                                <Label for="email">
                                    Email <span class="text-destructive">*</span>
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
                                        Password <span class="text-destructive">*</span>
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
                                        Confirm Password <span class="text-destructive">*</span>
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
                            <CardTitle>Roles</CardTitle>
                            <CardDescription>
                                Optional. Selected:
                                <span class="font-medium text-foreground">
                                    {{ form.roles.length }}
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
                                No roles found. Create roles first.
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
                            <Link href="/admin/users">Cancel</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Creating...' : 'Create User' }}
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
import { Head, Link, useForm } from '@inertiajs/vue3';

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

