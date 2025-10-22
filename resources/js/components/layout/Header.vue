<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Button from "@/components/form/Button.vue";
import { useAuth } from "@/composables/useAuth";
import AuthModals from "@/components/auth/AuthModals.vue";
import { ref } from "vue";

defineProps<{
    firstLessonRoute: string;
}>();

const menu = [
    { title: "Reviews", url: "/#reviews" },
    { title: "Pricing", url: "/#pricing" },
    { title: "FAQ", url: "/#faq" },
];

const { user } = useAuth();

const showSignInModal = ref(false);
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
                @click="showSignInModal = true"
            />
        </div>

        <AuthModals v-model:show-sign-in-modal="showSignInModal" />
    </div>
</template>
