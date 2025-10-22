<script setup lang="ts">
import Button from "@/components/form/Button.vue";
import { Course } from "@/types/interfaces";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

defineProps<{
    course: Course;
    lesson: any;
}>();
</script>

<template>
    <div class="container mx-auto p-4">
        <h1 class="mb-4 text-3xl font-bold">{{ course.title }}</h1>
        <h2 class="mb-2 text-xl font-semibold">
            Chapter: {{ lesson.chapter.title }}
        </h2>
        <h3 class="mb-4 text-lg font-medium">Lesson: {{ lesson.title }}</h3>
        <div class="prose max-w-none" v-html="lesson.content"></div>

        <Link :href="route('home')">
            <Button title="Go back" size="sm" />
        </Link>
    </div>
</template>
