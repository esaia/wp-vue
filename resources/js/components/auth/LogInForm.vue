<script setup lang="ts">
import Input from "@/components/form/Input.vue";
import { ref } from "vue";
import Button from "@/components/form/Button.vue";

const email = ref("");
const password = ref("");
</script>
<template>
    <div>
        <div class="flex flex-col space-y-5">
            <h5 class="border-b-2 border-gray-300 pb-8 text-4xl">Log In</h5>
            <Input
                v-model="email"
                type="email"
                placeholder="Email"
                label="Email"
            />

            <Input
                v-model="password"
                type="text"
                placeholder="Password"
                label="Password"
            />

            <div class="flex gap-3">
                <Button title="Sign in" severity="secondary" class="w-fit" />
            </div>
        </div>
    </div>
</template>
