<script setup lang="ts">
import ErrorIcon from "@/components/icons/ErrorIcon.vue";

defineProps<{
    error: string;
}>();
</script>

<template>
    <div v-if="error" class="flex gap-2 text-sm text-red-500">
        <ErrorIcon class="size-5 fill-red-500" />
        <p>
            {{ error }}
        </p>
    </div>
</template>
