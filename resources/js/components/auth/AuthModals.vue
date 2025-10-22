<script setup lang="ts">
import Modal from "@/components/common/Modal.vue";
import SignInForm from "@/components/auth/SignInForm.vue";
import SignUpForm from "@/components/auth/SignUpForm.vue";
import { AuthModalNames } from "@/types/interfaces";
import ForgotPassForm from "@/components/auth/ForgotPassForm.vue";
import ResetPasswordForm from "@/components/auth/ResetPasswordForm.vue";
import { ref } from "vue";

const modalName = defineModel<AuthModalNames>("modalName");

const params = new URLSearchParams(window.location.search);
const token = ref(params.get("token"));

const handleCloseModal = () => {
    modalName.value = "";

    const params = new URLSearchParams(window.location.search);

    params.delete("token");
    params.delete("email");

    const newUrl = window.location.pathname;
    history.replaceState(null, "", newUrl);
    token.value = "";
};
</script>
<template>
    <Teleport to="body">
        <Transition name="fade">
            <Modal v-if="modalName === 'signIn'" @close="handleCloseModal">
                <SignInForm @logged-in="handleCloseModal" />
                <template #modal-bottom>
                    <div class="mt-2 flex w-full justify-center gap-4">
                        <span
                            class="cursor-pointer hover:underline"
                            @click="modalName = 'forgot'"
                        >
                            Forgot password?
                        </span>
                        <span
                            class="cursor-pointer hover:underline"
                            @click="modalName = 'signUp'"
                        >
                            Sign up
                        </span>
                    </div>
                </template>
            </Modal>

            <Modal v-else-if="modalName === 'signUp'" @close="handleCloseModal">
                <SignUpForm @registered="handleCloseModal" />
                <template #modal-bottom>
                    <div class="mt-2 flex w-full justify-center gap-4">
                        <span
                            class="cursor-pointer hover:underline"
                            @click="modalName = 'forgot'"
                        >
                            Forgot password?
                        </span>
                        <span
                            class="cursor-pointer hover:underline"
                            @click="modalName = 'signIn'"
                        >
                            Sign in
                        </span>
                    </div>
                </template>
            </Modal>

            <Modal v-else-if="modalName === 'forgot'" @close="handleCloseModal">
                <ForgotPassForm @email-sent="modalName = ''" />
            </Modal>

            <Modal v-else-if="token" @close="handleCloseModal">
                <ResetPasswordForm @password-resetted="modalName = 'signIn'" />
            </Modal>
        </Transition>
    </Teleport>
</template>
