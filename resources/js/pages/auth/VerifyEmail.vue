<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { logout } from '@/routes';
import { send } from '@/routes/verification';
import { Form, Head } from '@inertiajs/vue3';
import { CircleCheck } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <AuthLayout
        title="Verify email"
        description="Please verify your email address by clicking on the link we just emailed to you."
    >
        <Head title="Email verification" />

        <Alert
            v-if="status === 'verification-link-sent'"
            class="mb-4 border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-200"
        >
            <CircleCheck />
            <AlertTitle>Sent</AlertTitle>
            <AlertDescription>
                A new verification link has been sent to the email address you provided during registration.
            </AlertDescription>
        </Alert>

        <Form
            v-bind="send.form()"
            class="space-y-6 text-center"
            v-slot="{ processing }"
        >
            <Button :loading="processing" variant="secondary">
                Resend verification email
            </Button>

            <TextLink
                :href="logout()"
                as="button"
                class="mx-auto block text-sm"
            >
                Log out
            </TextLink>
        </Form>
    </AuthLayout>
</template>
