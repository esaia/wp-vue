<script setup lang="ts">
import Input from "@/components/form/Input.vue";
import { computed, ref } from "vue";
import Button from "@/components/form/Button.vue";
import { useForm } from "@inertiajs/vue3";
import {
    LOGIN_ERROR,
    PASSWORD_MATCH_MSG,
    REQUIRED_MSG,
} from "@/composables/constants";
import { helpers, required, sameAs } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { route } from "ziggy-js";
import ErrorText from "@/components/form/ErrorText.vue";
import AuthModalLayout from "@/components/layout/AuthModalLayout.vue";
import Alert from "@/components/UI/Alert.vue";

const emit = defineEmits<{
    (e: "passwordResetted"): void;
}>();

let params = new URLSearchParams(window.location.search);
const token = params.get("token");
const email = params.get("email");

const form = useForm({
    token: token || "",
    email: email || "",
    password: "",
    confirmPassword: "",
});

const rules = computed(() => {
    return {
        password: { required: helpers.withMessage(REQUIRED_MSG, required) },
        confirmPassword: {
            required: helpers.withMessage(REQUIRED_MSG, required),
            sameAs: helpers.withMessage(
                PASSWORD_MATCH_MSG,
                sameAs(form.password),
            ),
        },
    };
});

const error = ref("");
const isSuccess = ref(false);

const v$ = useVuelidate(rules, form);

const getError = (field: string) => {
    return (
        v$.value.$errors
            ?.find((i) => i.$property === field)
            ?.$message.toString() || ""
    );
};

const handleSubmitForm = async () => {
    error.value = "";

    await v$.value.$validate();

    if (v$.value.$error || form.processing) return;

    form.post(route("password.update"), {
        onError: (err) => {
            error.value = Object.values(err)?.[0] || LOGIN_ERROR;
        },
        onSuccess: (data) => {
            isSuccess.value = true;
            setTimeout(() => {
                emit("passwordResetted");
            }, 5000);
        },
    });
};
</script>
<template>
    <AuthModalLayout
        title="Reset your password"
        @handle-submit="handleSubmitForm"
    >
        <Alert
            v-if="isSuccess"
            title="Success!"
            teaser="Your password has been successfully reset. Please sign in with your new password."
        />

        <div v-else class="space-y-5">
            <Input
                v-model="form.email"
                type="email"
                placeholder="john@example.com"
                label="Email"
                disabled
                :error="getError('email')"
            />

            <Input
                v-model="form.password"
                type="password"
                placeholder="Password"
                label="Password"
                :error="getError('password')"
            />

            <Input
                v-model="form.confirmPassword"
                type="password"
                placeholder=""
                label="Repeat password"
                :error="getError('confirmPassword')"
            />

            <ErrorText :error="error" />

            <div class="flex gap-3">
                <Button
                    title="Reset"
                    severity="secondary"
                    class="w-fit"
                    :loading="form.processing"
                />
            </div>
        </div>
    </AuthModalLayout>
</template>
