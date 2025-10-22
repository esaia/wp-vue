<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>
<template>
    <button
        class="cursor-pointer rounded-full border border-black bg-white px-8 py-4 text-[17px] font-semibold text-black shadow-[0_0_0_0_black] transition-all duration-300 ease-in-out hover:-translate-x-0.5 hover:-translate-y-1 hover:shadow-[2px_5px_0_0_black] active:translate-[1pxd_2px] active:shadow-[0_0_0_0_black]"
    >
        {{ title }}
    </button>
</template>
