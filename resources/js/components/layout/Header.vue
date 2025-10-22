<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Button from "@/components/form/Button.vue";
import { Course } from "@/types/interfaces";
import { route } from "ziggy-js";
import Modal from "@/components/common/Modal.vue";
import LogInForm from "@/components/auth/LogInForm.vue";
import SIgnUpForm from "@/components/auth/SIgnUpForm.vue";
import { ref } from "vue";

defineProps<{
    course: Course;
}>();

const menu = [
    { title: "Reviews", url: "/#reviews" },
    { title: "Pricing", url: "/#pricing" },
    { title: "FAQ", url: "/#faq" },
];

const showLogInModal = ref(false);
const showSignUpModal = ref(false);

const handleShowLogIn = () => {
    showLogInModal.value = true;
    showSignUpModal.value = false;
};

const handleShowSignUp = () => {
    showLogInModal.value = false;
    showSignUpModal.value = true;
};
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
            <Button
                title="Log in"
                severity="secondary"
                size="sm"
                @click="showLogInModal = true"
            />
        </div>
    </div>

    <Teleport to="body">
        <Transition name="fade">
            <Modal v-if="showLogInModal" @close="showLogInModal = false">
                <LogInForm />
                <template #modal-bottom>
                    <div class="mt-2 flex w-full justify-center gap-4">
                        <Link class="hover:underline"> Forgot password? </Link>
                        <span
                            class="cursor-pointer hover:underline"
                            @click="handleShowSignUp"
                        >
                            Sign up
                        </span>
                    </div>
                </template>
            </Modal>

            <Modal v-else-if="showSignUpModal" @close="showSignUpModal = false">
                <SIgnUpForm />
                <template #modal-bottom>
                    <div class="mt-2 flex w-full justify-center gap-4">
                        <Link class="hover:underline"> Forgot password? </Link>
                        <span
                            class="cursor-pointer hover:underline"
                            @click="handleShowLogIn"
                        >
                            Log in
                        </span>
                    </div>
                </template>
            </Modal>
        </Transition>
    </Teleport>
</template>
