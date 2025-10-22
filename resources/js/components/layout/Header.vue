<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Button from "@/components/form/Button.vue";

const menu = [
    { title: "Reviews", url: "/#reviews" },
    { title: "Pricing", url: "/#pricing" },
    { title: "FAQ", url: "/#faq" },
];
</script>
<template>
    <div class="w-full p-4 flex justify-between items-center">
        <div>LOGO</div>
        <div class="space-x-6">
            <Link
                v-for="link in menu"
                :key="link.title"
                :href="link.url"
                class="hover:underline"
            >
                {{ link.title }}
            </Link>
        </div>
        <div>
            <Button title="Get access" />
        </div>
    </div>
</template>
