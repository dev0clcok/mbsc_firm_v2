<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
    notice?: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-svh flex-col items-center justify-center bg-background p-6 md:p-10"
    >
        <div
            class="pointer-events-none absolute inset-0 overflow-hidden"
            aria-hidden="true"
        >
            <div
                class="absolute -top-24 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-gradient-to-br from-indigo-500/15 to-violet-500/15 blur-3xl"
            />
            <div
                class="absolute -bottom-24 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 blur-3xl"
            />
        </div>

        <div class="relative w-full max-w-md">
            <div class="mb-6 flex flex-col items-center gap-3">
                <Link :href="home()" class="flex items-center gap-2" aria-label="RoleGuard home">
                    <AppLogo />
                </Link>
                <div class="text-center">
                    <h1 class="text-xl font-semibold tracking-tight">
                        {{ title }}
                    </h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ description }}
                    </p>
                </div>
            </div>

            <Alert
                v-if="notice"
                class="mb-4 border-border bg-card text-muted-foreground"
            >
                <AlertDescription>{{ notice }}</AlertDescription>
            </Alert>

            <Card class="shadow-sm">
                <CardHeader class="sr-only">
                    <CardTitle>{{ title }}</CardTitle>
                    <CardDescription>{{ description }}</CardDescription>
                </CardHeader>
                <CardContent class="pt-6">
                    <slot />
                </CardContent>
            </Card>
        </div>
    </div>
</template>
