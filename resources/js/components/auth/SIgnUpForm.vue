<script setup lang="ts">
import Input from "@/components/form/Input.vue";
import { computed, ref } from "vue";
import Button from "@/components/form/Button.vue";
import { useForm } from "@inertiajs/vue3";
import { email, helpers, required, sameAs } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { route } from "ziggy-js";
import ErrorText from "@/components/form/ErrorText.vue";
import AuthModalLayout from "@/components/layout/AuthModalLayout.vue";
import {
    EMAIL_MSG,
    LOGIN_ERROR,
    PASSWORD_MATCH_MSG,
    REQUIRED_MSG,
} from "@/composables/constants";

const emit = defineEmits<{
    (e: "registered"): void;
}>();

const form = useForm({
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
});

const rules = computed(() => {
    return {
        name: { required: helpers.withMessage(REQUIRED_MSG, required) },
        email: {
            required: helpers.withMessage(REQUIRED_MSG, required),
            email: helpers.withMessage(EMAIL_MSG, email),
        },
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

    form.post(route("signup"), {
        onError: (err) => {
            error.value = Object.values(err)?.[0] || LOGIN_ERROR;
        },
        onSuccess: () => emit("registered"),
    });
};
</script>
<template>
    <AuthModalLayout title="Sign up" @handle-submit="handleSubmitForm">
        <Input
            v-model="form.name"
            type="text"
            placeholder="Username"
            label="Username"
            :error="getError('name')"
        />

        <Input
            v-model="form.email"
            type="email"
            placeholder="Email"
            label="Email"
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
                title="Sign up"
                severity="secondary"
                class="w-fit"
                :loading="form.processing"
            />
        </div>
    </AuthModalLayout>
</template>
