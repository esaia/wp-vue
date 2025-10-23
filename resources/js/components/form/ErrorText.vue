<script setup lang="ts">
import ErrorIcon from "@/components/icons/ErrorIcon.vue";

defineProps<{
    error: string;
}>();
</script>

<template>
    <div class="mb-2 min-h-5">
        <div
            v-if="error"
            class="flex -translate-y-1 gap-2 text-sm text-red-500"
        >
            <ErrorIcon class="size-5 min-w-5 fill-red-500" />
            <p>
                {{ error }}
            </p>
        </div>
    </div>
</template>
