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
    (e: "emailSent"): void;
}>();

const form = useForm({
    email: "",
});

const rules = computed(() => {
    return {
        email: {
            required: helpers.withMessage(REQUIRED_MSG, required),
            email: helpers.withMessage(EMAIL_MSG, email),
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

    form.post(route("password.email"), {
        onError: (err) => {
            error.value = Object.values(err)?.[0] || LOGIN_ERROR;
        },
        onSuccess: (data) => {
            isSuccess.value = true;
            setTimeout(() => {
                emit("emailSent");
            }, 10000);
        },
    });
};
</script>
<template>
    <AuthModalLayout title="Forgot password?" @handle-submit="handleSubmitForm">
        <Alert
            v-if="isSuccess"
            title="Success!"
            :teaser="`A password reset link has been sent to your email. Please check your inbox (and spam folder) to reset your password. (${form.email})`"
        />

        <div v-else class="space-y-5">
            <Input
                v-model="form.email"
                type="email"
                placeholder="Email"
                label="Email"
                :error="getError('email')"
            />

            <ErrorText :error="error" />

            <div class="flex gap-3">
                <Button
                    title="Send link"
                    severity="secondary"
                    class="w-fit"
                    :loading="form.processing"
                />
            </div>
        </div>
    </AuthModalLayout>
</template>
