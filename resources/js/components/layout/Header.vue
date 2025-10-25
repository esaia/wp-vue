<script setup lang="ts">
import { ref } from "vue";
import Button from "@/components/form/Button.vue";
import { useAuth } from "@/composables/useAuth";
import AuthModals from "@/components/auth/AuthModals.vue";
import Logo from "@/components/common/Logo.vue";
import { AuthModalNames } from "@/types/interfaces";
import { Link } from "@inertiajs/vue3";
import BurgerIcon from "@/components/icons/BurgerIcon.vue";
import Xicon from "@/components/icons/Xicon.vue";

defineProps<{
    firstLessonRoute: string;
}>();

const menu = [
    { title: "Story", url: "/#story" },
    { title: "Pricing", url: "/#pricing" },
    { title: "FAQ", url: "/#faq" },
];

const { user } = useAuth();

const authModalName = ref<AuthModalNames>("");
const openMenuModal = ref(false);
</script>
<template>
    <div class="fixed top-0 z-30 w-full border-b bg-white/90 backdrop-blur-xs">
        <div
            class="container-fluid flex items-center justify-between px-6 py-3"
        >
            <Logo class="flex-1" />

            <div
                class="hidden flex-1 items-center justify-center space-x-10 text-lg lg:flex"
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

            <div class="hidden flex-1 items-center justify-end gap-4 lg:flex">
                <Link :href="firstLessonRoute">
                    <Button title="Open course" size="sm" />
                </Link>

                <Button
                    v-if="!user"
                    title="Sign in"
                    severity="secondary"
                    size="sm"
                    @click="authModalName = 'signIn'"
                />
            </div>

            <div>
                <BurgerIcon
                    class="size-8 cursor-pointer lg:hidden"
                    @click="openMenuModal = true"
                />
            </div>

            <Transition name="fade">
                <div
                    v-if="openMenuModal"
                    class="absolute top-0 left-0 h-screen max-h-screen w-full overflow-y-auto bg-white py-3"
                >
                    <div class="flex items-center justify-between px-6">
                        <Logo />

                        <Xicon
                            class="mx-2 size-5 cursor-pointer"
                            @click="openMenuModal = false"
                        />
                    </div>

                    <div class="mt-10 flex flex-col gap-6 px-6 text-lg">
                        <Link
                            v-for="link in menu"
                            :key="link.title"
                            :href="link.url"
                            class="w-fit hover:underline"
                        >
                            {{ link.title }}
                        </Link>
                    </div>

                    <div class="mx-6 mt-10 flex flex-col gap-4">
                        <Link :href="firstLessonRoute">
                            <Button
                                title="Open course"
                                size="md"
                                class="w-full"
                            />
                        </Link>

                        <Button
                            v-if="!user"
                            title="Sign in"
                            severity="secondary"
                            size="md"
                            @click="authModalName = 'signIn'"
                        />
                    </div>
                </div>
            </Transition>

            <AuthModals v-model:modal-name="authModalName" />
        </div>
    </div>
</template>
