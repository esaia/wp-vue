<script setup lang="ts">
import Logo from "@/components/common/Logo.vue";
import { Link } from "@inertiajs/vue3";

const FOOTER_LINKS = [
    { title: "Reviews", url: "/#reviews" },
    { title: "Pricing", url: "/#pricing" },
    { title: "FAQ", url: "/#faq" },
    { title: "Interactive Real Estate", url: "http://www.ireplugin.com/" },
];
</script>
<template>
    <div class="mt-20 w-full border-t py-10">
        <div
            class="container-fluid flex flex-col items-start justify-between gap-10 lg:flex-row lg:items-center"
        >
            <Logo />

            <div
                class="flex flex-col items-start gap-8 lg:flex-row lg:items-center"
            >
                <Link
                    v-for="item in FOOTER_LINKS"
                    :key="item.title"
                    :href="item.url"
                    class="hover:text-amber-600 hover:underline"
                >
                    {{ item.title }}
                </Link>
            </div>
        </div>
    </div>
</template>
