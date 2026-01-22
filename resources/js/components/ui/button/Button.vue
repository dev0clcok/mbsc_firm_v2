<script setup lang="ts">
import type { PrimitiveProps } from 'reka-ui';
import type { HTMLAttributes } from 'vue';
import type { ButtonVariants } from '.';
import { Primitive } from 'reka-ui';
import { computed } from 'vue';
import { cn } from '@/lib/utils';
import { buttonVariants } from '.';
import { Spinner } from '@/components/ui/spinner';

interface Props extends PrimitiveProps {
    variant?: ButtonVariants['variant'];
    size?: ButtonVariants['size'];
    class?: HTMLAttributes['class'];
    loading?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    as: 'button',
    loading: false,
});

const disabledComputed = computed(() => Boolean(props.disabled) || Boolean(props.loading));
</script>

<template>
    <Primitive
        data-slot="button"
        :as="as"
        :as-child="asChild"
        :disabled="disabledComputed"
        :aria-busy="props.loading || undefined"
        :class="cn(buttonVariants({ variant, size }), props.class)"
    >
        <Spinner v-if="props.loading" />
        <slot />
    </Primitive>
</template>
