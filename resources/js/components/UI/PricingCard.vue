<script setup lang="ts">
import Button from "@/components/form/Button.vue";
import SuccessIcon from "@/components/icons/SuccessIcon.vue";
import VueIcon from "@/components/icons/VueIcon.vue";
import WordPressIcon from "@/components/icons/WordPressIcon.vue";

const features = [
    "12 hours of video content (215 short lessons)",
    "Support from our instructor",
    "Private Discord community",
    "Lifetime updates",
];
</script>
<template>
    <div class="container-fluid mt-20">
        <p class="title-md mb-6 text-center">
            Code your idea fast, build your WP plugin today
        </p>

        <div class="flex items-center justify-center">
            <div class="w-fit max-w-[600px] space-y-6 bg-black p-16 text-white">
                <div>
                    <p class="mb-2 text-lg font-bold lg:text-4xl">
                        <span class="text-wp">WordPress</span> plugin
                        development with
                        <span class="text-vue"> Vue.js</span>
                    </p>

                    <p class="flex items-center gap-2 text-gray-400">
                        Ship your ideas even faster
                        <span class="flex items-center gap-2 [&_svg]:size-10">
                            <WordPressIcon />
                            <span class="text-2xl">+</span>
                            <VueIcon />
                        </span>
                    </p>
                </div>

                <div class="flex items-end gap-3">
                    <span class="text-gray-400 line-through">$648 </span>
                    <span
                        class="text-5xl font-extrabold tracking-tight text-gray-100"
                    >
                        $299
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
                    <Button title="Buy course" class="w-full" white-shadow />

                    <p class="mt-4 text-center text-gray-400">
                        Access forever (no subscription)
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
