<script setup lang="ts">
import Footer from "@/components/layout/Footer.vue";
import Header from "@/components/layout/Header.vue";
import Faq from "@/components/UI/Faq.vue";
import MainBanner from "@/components/UI/MainBanner.vue";
import MyStorey from "@/components/UI/MyStorey.vue";
import PricingCard from "@/components/UI/PricingCard.vue";
import Steps from "@/components/UI/Steps.vue";
import WhyVue from "@/components/UI/WhyVue.vue";

defineProps<{
    firstLessonRoute: string;
    hasCourseAccess: boolean;
}>();
</script>

<template>
    <div class="min-h-screen">
        <Header :first-lesson-route="firstLessonRoute" />

        <div class="h-20"></div>

        <MainBanner />

        <Steps />

        <WhyVue />

        <MyStorey />

        <PricingCard :has-course-access="hasCourseAccess" />

        <Faq />

        <Footer />
    </div>
</template>
