<script setup lang="ts">
import Button from "@/components/form/Button.vue";
import SuccessIcon from "@/components/icons/SuccessIcon.vue";
import VueIcon from "@/components/icons/VueIcon.vue";
import WordPressIcon from "@/components/icons/WordPressIcon.vue";
import { route } from "ziggy-js";
import axios from "axios";
import { ref } from "vue";

defineProps<{
    hasCourseAccess: boolean;
}>();

const features = [
    "6.5 hours of video content",
    "Support from instructor",
    "Private Discord community",
    "Lifetime updates",
];

const loading = ref(false);

const handlePay = async () => {
    if (loading.value) return;
    loading.value = true;
    try {
        const { data } = await axios.post(route("payment.process"));

        if (data?.data?.url) {
            window.location.href = data.data.url;
        }
    } catch (error) {
    } finally {
        setTimeout(() => {
            loading.value = false;
        }, 2000);
    }
};
</script>
<template>
    <div id="pricing" class="container-fluid pt-32">
        <p class="title-md mb-10 text-center">
            Build your WordPress plugin today
        </p>

        <div class="flex items-center justify-center">
            <div
                class="w-fit max-w-[600px] space-y-6 bg-black p-8 text-white shadow md:p-16"
            >
                <div>
                    <p class="mb-2 text-2xl font-bold lg:text-4xl">
                        <span class="text-wp">WordPress</span> plugin
                        development with
                        <span class="text-vue"> Vue.js</span>
                    </p>

                    <div
                        class="flex flex-col gap-2 md:flex-row md:items-center"
                    >
                        <p class="text-gray-400">
                            Create
                            <span class="text-primary inline underline">
                                premium
                            </span>
                            WordPress plugins
                        </p>
                        <span class="flex items-center gap-2 [&_svg]:size-10">
                            <WordPressIcon />
                            <span class="text-2xl">+</span>
                            <VueIcon />
                        </span>
                    </div>
                </div>

                <div class="flex items-end gap-3">
                    <span
                        class="text-xl text-gray-400 line-through decoration-white/80"
                    >
                        $199
                    </span>
                    <span
                        class="text-5xl font-extrabold tracking-tight text-gray-100"
                    >
                        $59
                    </span>
                    <span class="text-sm font-bold text-gray-400">USD</span>
                </div>

                <div>
                    <ul class="space-y-4">
                        <li
                            v-for="item in features"
                            :key="item"
                            class="flex items-center gap-2"
                        >
                            <span>
                                <SuccessIcon
                                    class="size-5 [&_path]:fill-green-500"
                                />
                            </span>
                            {{ item }}
                        </li>
                    </ul>
                </div>

                <div>
                    <Button
                        :title="
                            hasCourseAccess
                                ? 'You have already purchased'
                                : 'Buy course'
                        "
                        class="w-full"
                        white-shadow
                        :loading="hasCourseAccess"
                        @click="handlePay"
                    />

                    <p class="mt-4 text-center text-gray-400">
                        Access forever (no subscription)
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
