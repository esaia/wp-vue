<script setup lang="ts">
import Input from "@/components/form/Input.vue";
import { computed, ref } from "vue";
import Button from "@/components/form/Button.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import {
    PASSWORD_MATCH_MSG,
    REQUIRED_MSG,
    SERVER_ERROR,
    SUCCESS_MESSAGE_TIMEOUT,
} from "@/composables/constants";
import { helpers, minLength, required, sameAs } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { route } from "ziggy-js";
import ErrorText from "@/components/form/ErrorText.vue";
import AuthModalLayout from "@/components/layout/AuthModalLayout.vue";
import Alert from "@/components/UI/Alert.vue";
// @ts-ignore
import VueTurnstile from "vue-turnstile";

const emit = defineEmits<{
    (e: "passwordResetted"): void;
}>();

const turnstileSiteKey = usePage().props?.turnstileSiteKey || "";

let params = new URLSearchParams(window.location.search);
const token = params.get("token");
const email = params.get("email");

const turnstileKey = ref(0);
const error = ref("");
const isSuccess = ref(false);

const form = useForm({
    token: token || "",
    email: email || "",
    password: "",
    confirmPassword: "",
    cfTurnstileResponse: "",
});

const rules = computed(() => {
    return {
        password: {
            required: helpers.withMessage(REQUIRED_MSG, required),
            min: minLength(8),
        },
        confirmPassword: {
            required: helpers.withMessage(REQUIRED_MSG, required),
            sameAs: helpers.withMessage(
                PASSWORD_MATCH_MSG,
                sameAs(form.password),
            ),
        },
    };
});

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
            turnstileKey.value++;
            error.value = Object.values(err)?.[0] || SERVER_ERROR;
        },
        onSuccess: () => {
            isSuccess.value = true;
            setTimeout(() => {
                emit("passwordResetted");
            }, SUCCESS_MESSAGE_TIMEOUT);
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
            title="Password Reset Successful"
            teaser="Your password has been updated. Please use your new password to sign in to your account."
        />

        <template v-else>
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

            <!-- @ts-ignore -->
            <vue-turnstile
                :key="turnstileKey"
                :site-key="(turnstileSiteKey as string) || ''"
                v-model="form.cfTurnstileResponse"
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
        </template>
    </AuthModalLayout>
</template>
