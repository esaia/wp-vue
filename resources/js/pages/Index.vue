<script setup lang="ts">
import Button from "@/components/form/Button.vue";
import VueIcon from "@/components/icons/VueIcon.vue";
import WordPressIcon from "@/components/icons/WordPressIcon.vue";
import Header from "@/components/layout/Header.vue";
import PricingCard from "@/components/UI/PricingCard.vue";

defineProps<{
    firstLessonRoute: string;
}>();
</script>

<template>
    <div class="mx-auto min-h-screen">
        <Header :first-lesson-route="firstLessonRoute" />

        <!-- Header banner -->
        <div
            class="mx-auto flex max-w-5xl flex-col items-center justify-center gap-12 pt-20 text-center"
        >
            <span class="flex items-center gap-6 [&_svg]:size-20">
                <WordPressIcon />
                <span class="text-3xl">+</span>
                <VueIcon />
            </span>

            <h1
                class="text-5xl font-black tracking-tight lg:text-6xl lg:text-[4rem] lg:tracking-[-0.035em]"
            >
                Build WordPress plugins
                <span class="relative">
                    in weeks,
                    <span
                        class="bg-primary absolute bottom-0 left-0 z-[-1] h-5 w-full"
                    />
                </span>
                not months
            </h1>

            <p
                class="mx-auto max-w-2xl text-lg leading-relaxed text-gray-700 lg:text-2xl lg:leading-relaxed"
            >
                Everything you need to build your WordPress plugins with Vue.js
                as a complete beginner.
            </p>

            <Button title="Get instant access" severity="secondary" size="lg" />
        </div>

        <!-- Pricing -->

        <PricingCard />
    </div>
</template>
