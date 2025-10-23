<script setup lang="ts">
import { ref } from "vue";
import { Vue3SlideUpDown } from "vue3-slide-up-down";
import ArrowIcon from "@/components/icons/ArrowIcon.vue";

const FAQ = [
    {
        question: "What do I get exactly?",
        answer: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, enim! In modi eaque sint deleniti reprehenderit fugiat ullam voluptatum adipisci?",
    },
    {
        question:
            "What do I get exactly amet consectetur adipisicing elit. Dolor, enim! In modi ?",
        answer: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, enim! In modi eaque sint deleniti reprehenderit fugiat ullam voluptatum adipisci?",
    },
    {
        question: "What do I get exactly?",
        answer: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, enim! In modi eaque sint deleniti reprehenderit fugiat ullam voluptatum adipisci?",
    },
    {
        question: "What do I get exactly?",
        answer: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, enim! In modi eaque sint deleniti reprehenderit fugiat ullam voluptatum adipisci?",
    },
];

const openItems = ref<Record<number, boolean>>({});

const toggleItem = (index: number) => {
    const temp = !openItems.value[index];
    openItems.value = {};
    openItems.value[index] = temp;
};
</script>
<template>
    <div id="faq" class="container-fluid pt-20">
        <h3 class="title-md text-center">Frequently Asked Questions</h3>

        <div class="mt-10 flex flex-col items-center justify-center">
            <div
                v-for="(item, index) in FAQ"
                :key="index"
                class="w-full max-w-[600px]"
            >
                <div
                    class="group flex cursor-pointer items-center justify-between gap-4 border-b border-gray-400 p-4"
                    :class="{ 'border-b-0': openItems[index] }"
                    @click="toggleItem(index)"
                >
                    <p
                        class="cursor-pointer text-lg font-semibold group-hover:underline"
                    >
                        {{ item.question }}
                    </p>
                    <span
                        class="transition-all"
                        :class="{ 'rotate-180': openItems[index] }"
                    >
                        <ArrowIcon />
                    </span>
                </div>

                <Vue3SlideUpDown v-model="openItems[index]" :duration="200">
                    <div
                        class="p-4"
                        :class="{
                            'border-b border-gray-400': openItems[index],
                        }"
                    >
                        <p>{{ item.answer }}</p>
                    </div>
                </Vue3SlideUpDown>
            </div>
        </div>
    </div>
</template>
