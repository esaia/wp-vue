<script setup lang="ts">
const emit = defineEmits<{
    (e: "handleSubmit"): void;
}>();
defineProps<{
    title: string;
}>();
</script>
<template>
    <form
        class="flex flex-col space-y-5 md:p-8"
        @submit.prevent="emit('handleSubmit')"
    >
        <h5 class="border-b-2 border-gray-300 pb-8 text-4xl font-bold">
            {{ title }}
        </h5>

        <slot />
    </form>
</template>
