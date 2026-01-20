<script setup lang="ts">
import { dashboard } from '@/routes/admin';
import { login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

const year = new Date().getFullYear();

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="RoleGuard" />

    <div class="min-h-screen bg-background text-foreground">
        <div class="mx-auto max-w-6xl px-6 py-10">
            <header class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="grid h-10 w-10 place-items-center rounded-xl border border-border bg-card shadow-sm"
                        aria-hidden="true"
                    >
                        <div
                            class="h-4 w-4 rounded-md bg-gradient-to-br from-indigo-500 to-violet-500"
                        />
                    </div>
                    <div class="leading-tight">
                        <div class="text-sm font-semibold">RoleGuard</div>
                        <div class="text-xs text-muted-foreground">
                            Laravel + Inertia + Vue role starter kit
                        </div>
                    </div>
                </div>

                <nav class="flex items-center gap-2">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow-sm hover:opacity-90"
                    >
                        Go to Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-flex items-center rounded-md border border-border bg-background px-4 py-2 text-sm font-medium hover:bg-muted"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow-sm hover:opacity-90"
                        >
                            Create account
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="mt-12">
                <section
                    class="relative overflow-hidden rounded-2xl border border-border bg-card p-8 shadow-sm"
                >
                    <div
                        class="pointer-events-none absolute -top-16 -right-16 h-56 w-56 rounded-full bg-gradient-to-br from-indigo-500/20 to-violet-500/20 blur-2xl"
                        aria-hidden="true"
                    />
                    <div
                        class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-gradient-to-br from-emerald-500/15 to-cyan-500/15 blur-2xl"
                        aria-hidden="true"
                    />

                    <div class="relative grid gap-6 lg:grid-cols-2 lg:items-center">
                        <div>
                            <div
                                class="inline-flex items-center rounded-full border border-border bg-background px-3 py-1 text-xs font-medium text-muted-foreground"
                            >
                                RoleGuard • RBAC + Audit Logging
                            </div>
                            <h1 class="mt-4 text-3xl font-semibold tracking-tight lg:text-4xl">
                                Build secure admin panels with roles, permissions, and full
                                activity tracking.
                            </h1>
                            <p class="mt-3 max-w-prose text-sm text-muted-foreground">
                                A clean starter kit for Laravel 11 + Inertia + Vue—designed to
                                ship real apps fast while keeping authorization consistent on
                                backend and frontend.
                            </p>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <Link
                                    v-if="$page.props.auth.user"
                                    :href="dashboard()"
                                    class="inline-flex items-center rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-primary-foreground shadow-sm hover:opacity-90"
                                >
                                    Open Admin
                                </Link>
                                <template v-else>
                                    <Link
                                        :href="login()"
                                        class="inline-flex items-center rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-primary-foreground shadow-sm hover:opacity-90"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        v-if="canRegister"
                                        :href="register()"
                                        class="inline-flex items-center rounded-md border border-border bg-background px-5 py-2.5 text-sm font-medium hover:bg-muted"
                                    >
                                        Create account
                                    </Link>
                                </template>
                            </div>

                            <div class="mt-6 text-xs text-muted-foreground">
                                Includes admin CRUD examples (Services / FAQs), reusable DataTable,
                                ConfirmDialog, and toast notifications.
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-border bg-background p-4">
                                <div class="text-sm font-medium">Granular permissions</div>
                                <div class="mt-1 text-xs text-muted-foreground">
                                    `permission:*` middleware + frontend `usePermissions()` guards.
                                </div>
                            </div>
                            <div class="rounded-xl border border-border bg-background p-4">
                                <div class="text-sm font-medium">Super Admin bypass</div>
                                <div class="mt-1 text-xs text-muted-foreground">
                                    Configurable via `SUPER_ADMIN_EMAIL` with safe fallback.
                                </div>
                            </div>
                            <div class="rounded-xl border border-border bg-background p-4">
                                <div class="text-sm font-medium">Audit logging</div>
                                <div class="mt-1 text-xs text-muted-foreground">
                                    Track user actions across the app for accountability.
                                </div>
                            </div>
                            <div class="rounded-xl border border-border bg-background p-4">
                                <div class="text-sm font-medium">Admin UI primitives</div>
                                <div class="mt-1 text-xs text-muted-foreground">
                                    Polished tables, filters, pagination, confirm dialogs, toasts.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-10 grid gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                        <div class="text-sm font-semibold">Backend-first security</div>
                        <div class="mt-2 text-sm text-muted-foreground">
                            Permissions live in middleware/controller rules—UI only reflects access.
                        </div>
                    </div>
                    <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                        <div class="text-sm font-semibold">Fast CRUD scaffolding</div>
                        <div class="mt-2 text-sm text-muted-foreground">
                            Examples you can copy for any resource: index filters + pagination +
                            confirm delete.
                        </div>
                    </div>
                    <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                        <div class="text-sm font-semibold">Inertia-friendly</div>
                        <div class="mt-2 text-sm text-muted-foreground">
                            Shared auth props power consistent permission checks across the app.
                        </div>
                    </div>
                </section>

                <footer class="mt-12 border-t border-border pt-6 text-xs text-muted-foreground">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>© {{ year }} RoleGuard</div>
                        <div class="flex items-center gap-3">
                            <span class="hidden sm:inline">Built with Laravel, Inertia, Vue</span>
                            <span class="hidden sm:inline" aria-hidden="true">•</span>
                            <span>RBAC Starter Kit</span>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>
</template>

