<script setup lang="ts">
import { ref } from "vue";
import { AuthModalNames, Course, Lesson } from "@/types/interfaces";
import Button from "@/components/form/Button.vue";
import AuthModals from "@/components/auth/AuthModals.vue";
import { useAuth } from "@/composables/useAuth";
import Logo from "@/components/common/Logo.vue";
import { route } from "ziggy-js";
import { Link } from "@inertiajs/vue3";

defineProps<{
    course: Course;
    currentLesson: Lesson;
}>();

const { user } = useAuth();

const modalName = ref<AuthModalNames>("");
</script>
<template>
    <div class="flex h-16 items-center justify-between border-b p-3">
        <div class="flex items-center gap-4">
            <Link :href="route('home')">
                <Logo />
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
            @click="modalName = 'signIn'"
        />

        <AuthModals v-model:modal-name="modalName" />
    </div>
</template>
