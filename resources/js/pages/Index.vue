<script setup lang="ts">
import Header from "@/components/layout/Header.vue";
import { useAuth } from "@/composables/useAuth";
import { Course } from "@/types/interfaces";

defineProps<{
    firstLessonRoute: string;
}>();

const { user } = useAuth();
</script>

<template>
    <div class="container-fluid mx-auto min-h-screen">
        <Header :first-lesson-route="firstLessonRoute" />

        <pre>
            {{ user }}
        </pre>
        <pre>

            {{ firstLessonRoute }}
        </pre>
    </div>
</template>
