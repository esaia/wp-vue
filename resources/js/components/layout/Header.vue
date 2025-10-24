<script setup lang="ts">
import { ref } from "vue";
import Button from "@/components/form/Button.vue";
import { useAuth } from "@/composables/useAuth";
import AuthModals from "@/components/auth/AuthModals.vue";
import Logo from "@/components/common/Logo.vue";
import { AuthModalNames } from "@/types/interfaces";
import { Link } from "@inertiajs/vue3";

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
    <div class="fixed top-0 z-30 w-full border-b bg-white/90 backdrop-blur-xs">
        <div
            class="container-fluid flex items-center justify-between px-6 py-3"
        >
            <Logo class="flex-1" />

            <div
                class="flex flex-1 items-center justify-center space-x-10 text-lg"
            >
                <Link
                    v-for="link in menu"
                    :key="link.title"
                    :href="link.url"
                    class="hover:text-amber-600 hover:underline"
                >
                    {{ link.title }}
                </Link>
            </div>
            <div class="flex flex-1 items-center justify-end gap-4">
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
    </div>
</template>
