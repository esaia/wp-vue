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

const emit = defineEmits<{
    (e: "loggedIn"): void;
}>();

const form = useForm({
    email: "asgnj@asg.sfg",
    password: "123456Aa",
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
        onStart: () => {
            emit("loggedIn");
        },
    });
};
</script>
<template>
    <form class="flex flex-col space-y-5" @submit.prevent="handleSubmitForm">
        <h5 class="border-b-2 border-gray-300 pb-8 text-4xl font-bold">
            Sign in
        </h5>

        <Input
            v-model="form.email"
            type="email"
            placeholder="Email"
            label="Email"
            :error="getError('email')"
        />

        <Input
            v-model="form.password"
            type="text"
            placeholder="Password"
            label="Password"
            :error="getError('password')"
        />

        <ErrorText :error="error" />

        <div class="flex gap-3">
            <Button title="Sign in" severity="secondary" class="w-fit" />
        </div>
    </form>
</template>
