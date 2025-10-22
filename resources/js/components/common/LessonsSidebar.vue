<script setup lang="ts">
import { Course, Lesson } from "@/types/interfaces";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import BlockIcon from "@/components/icons/BlockIcon.vue";

defineProps<{
    course: Course;
    currentLesson: Lesson;
}>();
</script>
<template>
    <div class="min-h-[calc(100vh-64px)] max-w-[270px] border-r p-6">
        <div v-for="chapter in course.chapters" :key="chapter.id">
            <h4>{{ chapter.title }} ({{ chapter.lessons.length }})</h4>

            <ol class="border-stroke mt-4 mb-7">
                <li
                    v-for="lesson in chapter.lessons"
                    :key="lesson.id"
                    class="flex cursor-pointer items-center justify-between gap-2 border-l border-[#c8d5e2] pl-5 hover:text-blue-600"
                    :class="{
                        'border-blue-600! text-blue-600':
                            lesson.id === currentLesson.id,
                    }"
                >
                    <Link
                        :href="
                            route('lesson.show', {
                                course: course.slug,
                                lesson: lesson.id,
                            })
                        "
                        class="line-clamp-1 flex-1"
                    >
                        {{ lesson.title }}
                    </Link>

                    <div class="py-2 [&_svg]:size-4">
                        <BlockIcon />
                    </div>
                </li>
            </ol>
        </div>
    </div>
</template>
