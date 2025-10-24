<script setup lang="ts">
import { ref } from "vue";
import { AuthModalNames, Course, Lesson } from "@/types/interfaces";
import Button from "@/components/form/Button.vue";
import AuthModals from "@/components/auth/AuthModals.vue";
import { useAuth } from "@/composables/useAuth";
import Logo from "@/components/common/Logo.vue";
import { route } from "ziggy-js";
import { Link, router } from "@inertiajs/vue3";
import ArrowIcon from "@/components/icons/ArrowIcon.vue";

defineProps<{
    course: Course;
    currentLesson: Lesson;
}>();

const { user } = useAuth();

const modalName = ref<AuthModalNames>("");
const showAccModal = ref(false);

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
    <div class="flex h-16 items-center justify-between border-b px-3">
        <div class="flex items-center gap-4 text-sm">
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

        <div
            v-else
            class="relative flex h-full cursor-pointer items-center gap-4 border-l pl-4"
            @click="showAccModal = !showAccModal"
        >
            <div
                class="bg-primary/90 flex aspect-square size-8 items-center justify-center rounded-full"
            >
                {{ user.name[0].toUpperCase() }}
            </div>

            <span class="select-none"> Account </span>

            <ArrowIcon :class="{ 'rotate-180': showAccModal }" />

            <Transition name="fade">
                <div
                    v-if="showAccModal"
                    class="absolute top-[calc(100%-10px)] right-0 min-w-full cursor-default border bg-white p-6 shadow"
                    @click.stop=""
                >
                    <p class="mb-4">{{ user.email }}</p>
                    <Button title="Log out" size="sm" @click="handleLogout" />
                </div>
            </Transition>
        </div>

        <AuthModals v-model:modal-name="modalName" />
    </div>
</template>
