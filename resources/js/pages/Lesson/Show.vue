<script setup lang="ts">
import Button from "@/components/form/Button.vue";
import { Course } from "@/types/interfaces";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const props = defineProps<{
    course: Course;
    currentLesson: any;
}>();

console.log("props", props);
</script>

<template>
    <div class="container mx-auto p-4">
        <div v-for="chapter in course.chapters">
            {{ chapter.title }}

            <div v-for="lesson in chapter.lessons">
                {{ lesson.id }} - {{ lesson.title }}
            </div>
        </div>
        <h1 class="mb-4 text-3xl font-bold">{{ course.title }}</h1>
        <h2 class="mb-2 text-xl font-semibold">
            Chapter: {{ currentLesson.chapter.title }}
        </h2>
        <h3 class="mb-4 text-lg font-medium">
            Lesson: {{ currentLesson.title }}
        </h3>
        <div class="prose max-w-none" v-html="currentLesson.content"></div>

        <Link :href="route('home')">
            <Button title="Go back" size="sm" />
        </Link>
    </div>
</template>
