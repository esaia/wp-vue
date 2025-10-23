<script setup lang="ts">
import HammerIcon from "@/components/icons/HammerIcon.vue";
import MoneyIcon from "@/components/icons/MoneyIcon.vue";
import ReadIcon from "@/components/icons/ReadIcon.vue";
import RocketIcon from "@/components/icons/RocketIcon.vue";
</script>
<template>
    <div
        class="container-fluid mt-32 flex flex-col items-center justify-center gap-20 md:flex-row md:items-start [&_p]:text-center [&_p]:text-2xl [&_p]:font-bold"
    >
        <div class="flex-1">
            <ReadIcon class="mx-auto mb-3 size-14" />
            <p>Learn what you really need</p>
        </div>

        <div class="flex-1">
            <HammerIcon class="mx-auto mb-3 size-14" />
            <p>Build features</p>
        </div>

        <div class="flex-1">
            <RocketIcon class="mx-auto mb-3 size-14" />
            <p>Launch</p>
        </div>

        <div class="flex-1">
            <MoneyIcon class="mx-auto mb-3 size-14" />
            <p>Earn money</p>
        </div>
    </div>
</template>
