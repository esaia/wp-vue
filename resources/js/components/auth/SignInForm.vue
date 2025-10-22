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

    form.post(route("login"), {
        onError: (err) => {
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
            <ErrorText :error="error" />

            <div class="flex gap-3">
                <Button
                    title="Sign in"
                    severity="secondary"
                    class="w-fit"
                    size="sm"
                    :loading="form.processing"
                />
            </div>
        </div>
    </AuthModalLayout>
</template>
