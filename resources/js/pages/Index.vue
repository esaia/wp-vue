<script setup lang="ts">
import Header from "@/components/layout/Header.vue";
import { Course } from "@/types/interfaces";

defineProps<{
    course: Course;
}>();
</script>

<template>
    <div class="container-fluid mx-auto min-h-screen">
        <Header :course="course" />

        <pre>

            {{ course }}
        </pre>
    </div>
</template>
