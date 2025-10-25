<script setup lang="ts">
import Button from "@/components/form/Button.vue";
import VueIcon from "@/components/icons/VueIcon.vue";
import WordPressIcon from "@/components/icons/WordPressIcon.vue";
import { Link } from "@inertiajs/vue3";
</script>
<template>
    <div class="container-fluid">
        <div
            class="mx-auto flex max-w-5xl flex-col items-center justify-center gap-10 pt-8 text-center md:gap-12 lg:pt-20"
        >
            <span
                class="flex items-center gap-6 [&_svg]:size-16 md:[&_svg]:size-20"
            >
                <WordPressIcon />
                <span class="text-3xl">+</span>
                <VueIcon />
            </span>

            <h1
                class="text-4xl font-black tracking-tight lg:text-6xl lg:text-[4rem] lg:tracking-[-0.035em]"
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
                class="mx-auto max-w-4xl text-lg leading-relaxed text-gray-600 lg:text-2xl lg:leading-relaxed"
            >
                Building a WordPress plugin doesn't have to be overwhelming. In
                this guide, I'll show you how to leverage Vue.js to create
                powerful, dynamic plugins in a fraction of the time it typically
                takes. Learn the essential tools, workflows, and best practices
                to accelerate your WordPress development and bring your plugin
                ideas to life.
            </p>

            <Link href="#pricing">
                <Button
                    title="Get instant access"
                    severity="secondary"
                    size="lg"
                />
            </Link>
        </div>
    </div>
</template>
