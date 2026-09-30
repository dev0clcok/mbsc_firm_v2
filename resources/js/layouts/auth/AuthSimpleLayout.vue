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
        class="relative flex min-h-svh flex-col items-center justify-center bg-muted/60 p-6 md:p-10"
    >

        <div class="relative w-full max-w-md">
            <div class="mb-6 flex flex-col items-center gap-3">
                <Link :href="home()" class="flex items-center gap-2">
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
