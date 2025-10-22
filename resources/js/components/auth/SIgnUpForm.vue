<script setup lang="ts">
import Input from "@/components/form/Input.vue";
import { computed, ref } from "vue";
import Button from "@/components/form/Button.vue";
import { router, useForm } from "@inertiajs/vue3";
import {
    EMAIL_MSG,
    LOGIN_ERROR,
    PASSWORD_MATCH_MSG,
    REQUIRED_MSG,
} from "@/composables/constants";
import { email, helpers, required, sameAs } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { route } from "ziggy-js";
import ErrorText from "@/components/form/ErrorText.vue";

const form = useForm({
    name: "sagsag",
    email: "asgnj@asg.sfg",
    password: "123456Aa",
    confirmPassword: "123456Aa",
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
    await v$.value.$validate();

    if (v$.value.$error || form.processing) return;

    form.post(route("signup"), {
        onError: (err) => {
            error.value = Object.values(err)?.[0] || LOGIN_ERROR;
        },
        onSuccess: () => router.visit("/"),
    });
};
</script>
<template>
    <div>
        <form
            class="flex flex-col space-y-5"
            @submit.prevent="handleSubmitForm"
        >
            <h5 class="border-b-2 border-gray-300 pb-8 text-4xl">Sign up</h5>

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
        </form>
    </div>
</template>
