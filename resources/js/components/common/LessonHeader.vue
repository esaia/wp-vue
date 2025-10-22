<script setup lang="ts">
import { Course, Lesson } from "@/types/interfaces";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import Button from "@/components/form/Button.vue";

defineProps<{
    course: Course;
    currentLesson: Lesson;
}>();
</script>
<template>
    <div class="flex h-[64px] items-center justify-between border-b p-3">
        <div class="flex items-center gap-4">
            <div>LOGO</div>
            <span>/</span>
            <div>{{ course.title }}</div>
            <span>/</span>
            <div>{{ currentLesson.title }}</div>
        </div>

        <Link :href="route('home')">
            <Button title="Go back" severity="secondary" size="sm" />
        </Link>
    </div>
</template>
