<script setup lang="ts">
import { computed } from "vue";
import { Course, Lesson, Page } from "@/types/interfaces";
import { Link, router, usePage } from "@inertiajs/vue3";
import BlockIcon from "@/components/icons/BlockIcon.vue";
import Button from "@/components/form/Button.vue";
import { route } from "ziggy-js";

defineProps<{
    course: Course;
    currentLesson: Lesson;
}>();

const page = usePage<Page>();

const user = computed(() => page.props.auth.user);

const handleLogout = () => {
    router.post(
        route("logout"),
        {},
        {
            onSuccess: () => {
                router.visit("/");
            },
            onError: (errors) => {
                console.error("Logout failed:", errors);
            },
        },
    );
};
</script>
<template>
    <div
        class="x flex min-h-[calc(100vh-64px)] max-w-[270px] flex-col justify-between border-r"
    >
        <div class="flex-1 overflow-y-auto p-6">
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

        <div v-if="user" class="h-20 px-6">
            <Button title="Log out" @click="handleLogout" />
        </div>
    </div>
</template>
