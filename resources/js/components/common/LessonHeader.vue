<script setup lang="ts">
import { Course, Lesson } from "@/types/interfaces";
import Button from "@/components/form/Button.vue";
import AuthModals from "@/components/auth/AuthModals.vue";
import { useAuth } from "@/composables/useAuth";
import { ref } from "vue";
import { route } from "ziggy-js";
import { Link } from "@inertiajs/vue3";

defineProps<{
    course: Course;
    currentLesson: Lesson;
}>();

const { user } = useAuth();

const showSignInModal = ref(false);
</script>
<template>
    <div class="flex h-[64px] items-center justify-between border-b p-3">
        <div class="flex items-center gap-4">
            <Link :href="route('home')">
                <div>LOGO</div>
            </Link>
            <span>/</span>
            <div>{{ course.title }}</div>
            <span>/</span>
            <div>{{ currentLesson.title }}</div>
        </div>

        <Button
            v-if="!user"
            title="Sign in"
            severity="secondary"
            size="sm"
            @click="showSignInModal = true"
        />

        <AuthModals v-model:show-sign-in-modal="showSignInModal" />
    </div>
</template>
