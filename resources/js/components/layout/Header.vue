<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Button from "@/components/form/Button.vue";
import { route } from "ziggy-js";
import Modal from "@/components/common/Modal.vue";
import { ref } from "vue";
import SignInForm from "@/components/auth/SignInForm.vue";
import SignUpForm from "@/components/auth/SignUpForm.vue";
import { useAuth } from "@/composables/useAuth";

defineProps<{
    firstLessonRoute: string;
}>();

const menu = [
    { title: "Reviews", url: "/#reviews" },
    { title: "Pricing", url: "/#pricing" },
    { title: "FAQ", url: "/#faq" },
];

const { user } = useAuth();

const showSignInModal = ref(false);
const showSignUpModal = ref(false);

const handleShowLogIn = () => {
    showSignInModal.value = true;
    showSignUpModal.value = false;
};

const handleShowSignUp = () => {
    showSignInModal.value = false;
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
            <Link :href="firstLessonRoute">
                <Button title="Open course" size="sm" />
            </Link>

            <Button
                v-if="!user"
                title="Sign in"
                severity="secondary"
                size="sm"
                @click="showSignInModal = true"
            />
        </div>
    </div>

    <Teleport to="body">
        <Transition name="fade">
            <Modal v-if="showSignInModal" @close="showSignInModal = false">
                <SignInForm />
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
                <SignUpForm />
                <template #modal-bottom>
                    <div class="mt-2 flex w-full justify-center gap-4">
                        <Link class="hover:underline"> Forgot password? </Link>
                        <span
                            class="cursor-pointer hover:underline"
                            @click="handleShowLogIn"
                        >
                            Sign in
                        </span>
                    </div>
                </template>
            </Modal>
        </Transition>
    </Teleport>
</template>
