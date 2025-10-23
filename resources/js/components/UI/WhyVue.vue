<script setup lang="ts">
import WordPressIcon from "@/components/icons/WordPressIcon.vue";
import VueIcon from "@/components/icons/VueIcon.vue";

const DATA = [
    {
        title: "Vue.js vs Jquery",
        content:
            "jQuery requires manual DOM manipulation while Vue.js Uses a <span>  virtual DOM</span>. It Offers reactive, two-way data binding, automatically updating the DOM when data changes, reducing manual manipulation and improving efficiency.",
    },
    {
        title: "Developer Productivity",
        content:
            "Vue’s <span> simple syntax and clear documentation</span> reduce the learning curve, allowing faster development of plugin features compared to vanilla JavaScript or jquery.",
    },
    {
        title: "Scalability",
        content:
            "Vue’s structure supports building complex plugins, from simple widgets to full-fledged applications, while maintaining clean, maintainable code.",
    },
    {
        title: "Community and Ecosystem",
        content:
            "Vue has a strong <span>community and libraries</span> (e.g., Pinia for state management), which streamline plugin development.",
    },
    {
        title: "Improved Admin Experience",
        content:
            "Vue can enhance WordPress admin panels with modern, intuitive interfaces, making plugin settings or dashboards more <span>user-friendly for clients.</span>",
    },
    {
        title: "Seamless Reactivity",
        content:
            "Vue's reactive data binding simplifies state management, enabling real-time updates (e.g., live previews or dynamic settings) without page reloads.",
    },
];
</script>
<template>
    <div class="container-fluid mt-20">
        <div class="flex items-center justify-center gap-2">
            <h3 class="title-md">Why <span class="text-vue">Vue.js?</span></h3>
            <VueIcon class="hidden size-12 md:block" />
        </div>

        <div
            class="mx-auto mt-10 grid max-w-7xl grid-cols-1 gap-10 text-left md:grid-cols-2"
        >
            <div v-for="item in DATA">
                <p class="mb-2 text-lg font-semibold">{{ item.title }}</p>

                <div
                    v-html="item.content"
                    class="[&_span]:after:bg-primary [&_span]:relative [&_span]:after:absolute [&_span]:after:bottom-1/8 [&_span]:after:left-0 [&_span]:after:-z-10 [&_span]:after:h-2 [&_span]:after:w-full [&_span]:after:content-['']"
                />
            </div>
        </div>

        <div
            class="mx-auto mt-20 flex max-w-4xl flex-col items-center justify-center gap-4"
        >
            <div class="flex items-center justify-center gap-2">
                <h3 class="title-md">
                    Why <span class="text-wp">WordPress?</span>
                </h3>
                <WordPressIcon class="hidden size-12 md:block" />
            </div>
            <p class="text-center text-xl">
                WordPress powers approximately
                <span class="text-2xl font-bold text-amber-600"> 43%</span> of
                websites globally, making it the most popular content management
                system (CMS). Developing WordPress plugins in this context
                offers significant advantages due to its massive market share.
            </p>
        </div>
    </div>
</template>
