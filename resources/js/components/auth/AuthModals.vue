<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Modal from "@/components/common/Modal.vue";
import SignInForm from "@/components/auth/SignInForm.vue";
import SignUpForm from "@/components/auth/SignUpForm.vue";

const showSignInModal = defineModel("showSignInModal");
const showSignUpModal = defineModel("showSignUpModal");

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
    <Teleport to="body">
        <Transition name="fade">
            <Modal v-if="showSignInModal" @close="showSignInModal = false">
                <SignInForm @logged-in="showSignInModal = false" />
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
                <SignUpForm @registered="showSignUpModal = false" />
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
