<script setup lang="ts">
import LessonHeader from "@/components/common/LessonHeader.vue";
import LessonsSidebar from "@/components/common/LessonsSidebar.vue";
import { Course, Lesson } from "@/types/interfaces";

defineProps<{
    course: Course;
    currentLesson: Lesson;
}>();
</script>

<template>
    <div class="mx-auto min-h-screen">
        <LessonHeader :course="course" :current-lesson="currentLesson" />

        <div class="bg-primary-500 flex">
            <LessonsSidebar :course="course" :current-lesson="currentLesson" />

            <div class="mx-auto flex w-full justify-center p-4 md:px-14">
                <div class="w-full max-w-[1500px]">
                    <h1 class="text-3xl">{{ currentLesson.title }}</h1>
                </div>
            </div>
        </div>
    </div>
</template>
