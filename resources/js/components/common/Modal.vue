<script setup lang="ts">
import { onMounted, onUnmounted, ref } from "vue";
import Button from "@/components/form/Button.vue";

const emit = defineEmits<{
    (e: "close"): void;
}>();

const slotContainer = ref();

onMounted(() => {
    document.body.style.overflow = "hidden";

    if (slotContainer.value) {
        slotContainer.value.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    }
});

onUnmounted(() => {
    document.body.style.overflow = "auto";
});
</script>
<template>
    <div
        class="font-bpg_le_studio fixed top-0 left-0 z-30 flex h-full w-full items-center justify-center bg-black/30 px-4 backdrop-blur-xs lg:px-10"
        @click="emit('close')"
    >
        <div
            class="relative w-full max-w-[500px] rounded-md transition-all duration-700"
            @click.stop=""
        >
            <div
                class="h-full max-h-[calc(100svh-50px)] w-full overflow-auto bg-white p-4 transition-all"
                ref="slotContainer"
            >
                <slot />
            </div>
            <div>
                <slot name="modal-bottom" />
            </div>

            <div
                class="absolute top-2 right-2 cursor-pointer rounded-full p-2 transition-all duration-300"
                @click="emit('close')"
            >
                <Button title="X" />
            </div>
        </div>
    </div>
</template>
