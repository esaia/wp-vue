<script setup lang="ts">
import Input from "@/components/form/Input.vue";
import { computed, ref } from "vue";
import Button from "@/components/form/Button.vue";
import { useForm } from "@inertiajs/vue3";
import { EMAIL_MSG, LOGIN_ERROR, REQUIRED_MSG } from "@/composables/constants";
import { email, helpers, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { route } from "ziggy-js";
import ErrorText from "@/components/form/ErrorText.vue";
import AuthModalLayout from "@/components/layout/AuthModalLayout.vue";
import Alert from "@/components/UI/Alert.vue";

const emit = defineEmits<{
    (e: "loggedIn"): void;
}>();

const form = useForm({
    email: "",
    password: "",
});

const rules = computed(() => {
    return {
        email: {
            required: helpers.withMessage(REQUIRED_MSG, required),
            email: helpers.withMessage(EMAIL_MSG, email),
        },
        password: { required: helpers.withMessage(REQUIRED_MSG, required) },
    };
});

const error = ref("");
const showResendVerification = ref(false);
const notificationSent = ref(false);

const v$ = useVuelidate(rules, form);

const getError = (field: string) => {
    return (
        v$.value.$errors
            ?.find((i) => i.$property === field)
            ?.$message.toString() || ""
    );
};

const resendEmailVerification = () => {
    if (form.processing) return;
    form.post(route("verification.send"), {
        onSuccess: () => {
            notificationSent.value = true;
            showResendVerification.value = false;
            error.value = "";

            setTimeout(() => {
                notificationSent.value = false;
            }, 10000);
        },
    });
};

const handleSubmitForm = async () => {
    error.value = "";
    showResendVerification.value = false;
    notificationSent.value = false;

    await v$.value.$validate();

    if (v$.value.$error || form.processing) return;

    form.post(route("login"), {
        onError: (err) => {
            if (err?.email_verify) {
                showResendVerification.value = true;
            }

            error.value = Object.values(err)?.[0] || LOGIN_ERROR;
        },
        onSuccess: () => {
            emit("loggedIn");
        },
    });
};
</script>
<template>
    <AuthModalLayout title="Sign in" @handle-submit="handleSubmitForm">
        <Input
            v-model="form.email"
            type="email"
            placeholder="john@example.com"
            label="Email"
            :error="getError('email')"
        />

        <Input
            v-model="form.password"
            type="password"
            placeholder="Enter Password"
            label="Password"
            :error="getError('password')"
        />

        <div>
            <Alert
                v-if="notificationSent"
                title="Check Your Email"
                teaser="A new verification link has been sent to your email address."
            />

            <div v-else class="flex flex-wrap items-center gap-x-2">
                <ErrorText :error="error" />
            </div>

            <div class="flex justify-between gap-3">
                <Button
                    title="Sign in"
                    severity="secondary"
                    class="w-fit"
                    size="sm"
                    :loading="form.processing"
                />

                <button
                    v-if="showResendVerification"
                    type="button"
                    class="cursor-pointer hover:text-amber-500 hover:underline"
                    @click="resendEmailVerification"
                >
                    {{ form.processing ? "resending..." : "resend" }}
                </button>
            </div>
        </div>
    </AuthModalLayout>
</template>
