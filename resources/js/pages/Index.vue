<script setup lang="ts">
import Header from "@/components/layout/Header.vue";
import { Course } from "@/types/interfaces";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

defineProps<{
    course: Course;
}>();

const page = usePage();

const user = computed(() => page.props.auth.user);
</script>

<template>
    <div class="container-fluid mx-auto min-h-screen">
        <Header :course="course" />

        <pre>

            {{ user }}
        </pre>
        <pre>

            {{ course }}
        </pre>
    </div>
</template>
