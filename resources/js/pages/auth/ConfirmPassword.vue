<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/password/confirm';
import { Form, Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <AuthLayout
        :title="t('settings.confirm_password.heading')"
        :description="t('settings.confirm_password.description')"
    >
        <Head :title="t('settings.confirm_password.title')" />

        <Form
            v-bind="store.form()"
            reset-on-success
            v-slot="{ errors, processing }"
        >
            <div class="space-y-6">
                <div class="grid gap-2">
                    <Label htmlFor="password">{{ t('settings.confirm_password.password') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="current-password"
                        autofocus
                        :placeholder="t('settings.confirm_password.placeholder')"
                    />

                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center">
                    <Button
                        class="w-full"
                        :loading="processing"
                        data-test="confirm-password-button"
                    >
                        {{ t('settings.confirm_password.submit') }}
                    </Button>
                </div>
            </div>
        </Form>
    </AuthLayout>
</template>
