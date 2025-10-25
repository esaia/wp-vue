<script setup lang="ts">
import HtmlIcon from "@/components/icons/HtmlIcon.vue";
import { Link } from "@inertiajs/vue3";
</script>
<template>
    <div class="">
        <Link
            href="/"
            class="flex w-fit origin-center cursor-pointer items-center gap-2 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95"
        >
            <HtmlIcon class="size-8" />
            <span class="font-bold uppercase">wp-vue</span>
        </Link>
    </div>
</template>
