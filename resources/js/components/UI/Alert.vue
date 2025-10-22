<script setup lang="ts">
defineProps<{
    title: string;
    teaser: string;
}>();
</script>
<template>
    <div
        class="mb-6 rounded-md border-l-4 border-green-500 bg-green-100 p-4 text-green-700"
    >
        <p class="font-semibold">{{ title }}</p>
        <p>
            {{ teaser }}
        </p>
    </div>
</template>
