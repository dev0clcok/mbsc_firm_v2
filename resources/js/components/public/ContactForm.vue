<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div v-if="$page.props.flash?.success" class="rounded-md bg-green-50 p-4 text-green-800">
            {{ $page.props.flash.success }}
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium">
                    Name <span class="text-destructive">*</span>
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                    :class="{ 'border-destructive': form.errors.name }"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium">
                    Email <span class="text-destructive">*</span>
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                    :class="{ 'border-destructive': form.errors.email }"
                />
                <InputError :message="form.errors.email" />
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="phone" class="mb-2 block text-sm font-medium">
                    Phone
                </label>
                <input
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                />
            </div>

            <div>
                <label for="service_id" class="mb-2 block text-sm font-medium">
                    Service Interested In
                </label>
                <select
                    id="service_id"
                    v-model="form.service_id"
                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                >
                    <option :value="null">Select a service...</option>
                    <option
                        v-for="service in services"
                        :key="service.id"
                        :value="service.id"
                    >
                        {{ service.title }}
                    </option>
                </select>
            </div>
        </div>

        <div>
            <label for="company" class="mb-2 block text-sm font-medium">
                Company
            </label>
            <input
                id="company"
                v-model="form.company"
                type="text"
                class="w-full rounded-md border border-input bg-background px-3 py-2"
            />
        </div>

        <div>
            <label for="message" class="mb-2 block text-sm font-medium">
                Message <span class="text-destructive">*</span>
            </label>
            <textarea
                id="message"
                v-model="form.message"
                rows="5"
                required
                class="w-full rounded-md border border-input bg-background px-3 py-2"
                :class="{ 'border-destructive': form.errors.message }"
            />
            <InputError :message="form.errors.message" />
        </div>

        <Button type="submit" :disabled="form.processing">
            <span v-if="form.processing">Sending...</span>
            <span v-else>Send Message</span>
        </Button>
    </form>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import InputError from '@/components/InputError.vue';
import * as contact from '@/routes/contact';

interface Service {
    id: number;
    title: string;
}

interface Props {
    services?: Service[];
}

const props = withDefaults(defineProps<Props>(), {
    services: () => [],
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    company: '',
    service_id: null as number | null,
    message: '',
});

const submit = () => {
    form.post(contact.store().url, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
