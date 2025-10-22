<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Button from "@/components/form/Button.vue";
import { Course } from "@/types/interfaces";
import { route } from "ziggy-js";

defineProps<{
    course: Course;
}>();

const menu = [
    { title: "Reviews", url: "/#reviews" },
    { title: "Pricing", url: "/#pricing" },
    { title: "FAQ", url: "/#faq" },
];
</script>
<template>
    <div class="flex w-full items-center justify-between p-4">
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
        <div class="flex items-center gap-4">
            <Link
                :href="
                    route('lesson.show', {
                        course: course.slug,
                        lesson: 1,
                    })
                "
            >
                <Button title="Get access" size="sm" />
            </Link>
            <Button title="Log in" severity="secondary" size="sm" />
        </div>
    </div>
</template>
