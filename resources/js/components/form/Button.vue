<script setup lang="ts">
withDefaults(
    defineProps<{
        title: string;
        severity?: "primary" | "secondary";
        size?: "sm" | "md" | "lg";
        loading?: boolean;
        whiteShadow?: boolean;
    }>(),
    {
        severity: "primary",
        size: "md",
        loading: false,
    },
);
</script>
<template>
    <button
        class="cursor-pointer rounded-full border border-black bg-white text-[17px] font-semibold text-black shadow-[0_0_0_0_black] transition-all duration-300 ease-in-out"
        :class="{
            'bg-primary!': severity === 'secondary' && !loading,
            'hover:-translate-x-0.5 hover:-translate-y-1 hover:shadow-[2px_5px_0_0_black] active:translate-[1pxd_2px] active:shadow-[0_0_0_0_black]':
                !loading,
            'hover:shadow-[2px_5px_0_0_#FFD230]!': !loading && whiteShadow,
            'cursor-default! bg-gray-500! text-white': loading,
            'px-4 py-2': size === 'sm',
            'px-8 py-4': size === 'md',
            'px-10 py-6 text-xl!': size === 'lg',
        }"
        :disabled="loading"
    >
        {{ title }}
    </button>
</template>
