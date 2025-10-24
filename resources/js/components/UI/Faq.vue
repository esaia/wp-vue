<script setup lang="ts">
import { ref } from "vue";
import { Vue3SlideUpDown } from "vue3-slide-up-down";
import ArrowIcon from "@/components/icons/ArrowIcon.vue";
const FAQ = [
    {
        question: "What is this course about?",
        answer: "<p>This course teaches you how to build modern, dynamic WordPress plugins using <strong>Vue.js</strong>.</p> <br /> <p>You'll learn to create interactive, scalable plugins with Vue's reactive components and integrate them with WordPress's Ajax, hooks, and admin interfaces, leveraging WordPress's <b>43%</b> market share to reach millions of websites.</p>",
    },
    {
        question:
            "I'm a WordPress developer, but I don't know Vue.js. Is this course for me?",
        answer: "<p><strong>Absolutely!</strong> This course is designed for WordPress developers who want to modernize their skills. I include a dedicated section on Vue.js fundamentals, teaching you just what you need to know to start building reactive admin interfaces.</p> <br/> <p>The course is specifically tailored to WordPress plugin development, so you can immediately apply your new skills.</p>",
    },
    {
        question:
            "I'm a Vue.js developer, but I don't know PHP. Is this course for me?",
        answer: "<p>Yes, this is the perfect course to bridge that gap! We've designed it to be accessible for front-end developers. You'll learn the essential PHP you need specifically for WordPress plugin development.</p><br/><p>The course focuses on how to connect your Vue.js expertise to the WordPress backend using WordPress Ajax, allowing you to build powerful applications within the world's most popular CMS.</p>",
    },

    {
        question: "What are the prerequisites for this course?",
        answer: "you should have a basic understanding of <b>HTML, CSS (tailwind), JavaScript, PHP, and WordPress</b> (such as how themes and plugins work). You don't need to be an expert, but this foundation will help you follow along smoothly.",
    },

    {
        question:
            "Why should I use Vue.js in WordPress plugins? Isn't jQuery enough?",
        answer: "<p>While jQuery is still useful, <strong>Vue.js</strong> allows you to build fast, dynamic, and sophisticated admin pages and front-end components that feel like a modern web application. It simplifies managing complex, interactive features with clean, maintainable code.</p> <br/> <p>This skill set makes your plugins more powerful and your development process faster.</p>",
    },
    {
        question: "What is the exact tech stack we'll be using?",
        answer: "<p>The core stack is <strong>PHP</strong>, <strong>Vue.js 3</strong> (composition API), <strong>Vite</strong>, <strong>Tailwind</strong>, and <strong>PrimeVue</strong>.</p> <br/> <p>You'll finish the course with a solid understanding of how these technologies work together.</p>",
    },
    {
        question: "Will the code be reusable for my own projects?",
        answer: "<p>Yes, 100%. The complete <strong>'Slider Pro'</strong> plugin you build serves as a professional-grade boilerplate.</p> <br/> <p>You'll be able to copy, paste, and adapt the code structure, Vue components, and build process for your own plugin ideas, saving you countless hours of development time.</p>",
    },

    {
        question: "How is this different from a Udemy course?",
        answer: "<p>This course is built on a completely different philosophy: <b>efficiency and real-world results.</b></p><br/><p>Unlike typical Udemy courses that can get lost in theory, I focus exclusively on what you truly need to know to build and ship a professional plugin. I <b>don't waste hours</b> on deep dives into every single function—in the age of AI, you can ask for a detailed explanation of any method in seconds.</p> <br/> <p>Instead, this course is a concentrated dose of practical, project-based execution. You'll learn the 'how' and the 'why' behind the entire process of structuring, building, and—most importantly—packaging your Vue.js-powered plugin into a finished, shippable product. It's the fast track from concept to a cool, working plugin, without the unnecessary overhead.</p>",
    },

    {
        question: "What if I get stuck?",
        answer: "<p>I provide dedicated support through a private Discord community or direct support channels. You're not on your own! Our goal is to ensure you can follow along and successfully build your plugin.</p>",
    },
    {
        question: "Is there a money-back guarantee?",
        answer: "Yes! I offer 7 days money-back guarantee. I am confident in the value of the course and want you to enroll with complete peace of mind.",
    },
    {
        question: "What should I know before I purchase?",
        answer: "Please be aware that this course uses a clear, AI-generated voice for the narration. As the creator, my primary focus is on delivering the highest-quality, actionable content. Using a professional text-to-speech program ensures that the technical concepts are explained with perfect clarity and pacing, making it easier for a global audience to follow along without any language barriers.",
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
    <div id="faq" class="container-fluid pt-32">
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
                        <div v-html="item.answer"></div>
                    </div>
                </Vue3SlideUpDown>
            </div>
        </div>
    </div>
</template>
