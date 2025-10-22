<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Button from "@/components/form/Button.vue";
import { useAuth } from "@/composables/useAuth";
import AuthModals from "@/components/auth/AuthModals.vue";
import { ref } from "vue";
import { AuthModalNames } from "@/types/interfaces";

defineProps<{
    firstLessonRoute: string;
}>();

const menu = [
    { title: "Reviews", url: "/#reviews" },
    { title: "Pricing", url: "/#pricing" },
    { title: "FAQ", url: "/#faq" },
];

const { user } = useAuth();

const modalName = ref<AuthModalNames>("");
</script>
<template>
    <div class="flex w-full items-center justify-between p-4">
        <div>LOGO</div>
        <div class="space-x-6">
            <Link
                v-for="link in menu"
                :key="link.title"
                :href="link.url"
                class="hover:underline"
            >
                {{ link.title }}
            </Link>
        </div>
        <div class="flex items-center gap-4">
            <Link :href="firstLessonRoute">
                <Button title="Open course" size="sm" />
            </Link>

            <Button
                v-if="!user"
                title="Sign in"
                severity="secondary"
                size="sm"
                @click="modalName = 'signIn'"
            />
        </div>

        <AuthModals v-model:modal-name="modalName" />
    </div>
</template>
