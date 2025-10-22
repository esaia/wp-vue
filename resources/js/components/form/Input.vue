<script setup lang="ts">
import { computed, ref } from "vue";
import EyeIcon from "@/components/icons/EyeIcon.vue";
import EyeCloseIcon from "@/components/icons/EyeCloseIcon.vue";

const props = withDefaults(
    defineProps<{
        modelValue: string;
        placeholder?: string;
        type?: string;
        label?: string;
        desc?: string;
        required?: boolean;
        isFloat?: boolean;
        disabled?: boolean;
        error?: string;
    }>(),
    {
        placeholder: "",
        type: "text",
        label: "",
    },
);

const emit = defineEmits<{
    (e: "update:modelValue", params: typeof props.modelValue): void;
}>();

const showPassword = ref(false);

const inputModel = computed({
    get() {
        return props.modelValue;
    },
    set(newValue) {
        emit("update:modelValue", newValue);
    },
});

const inputType = computed(() => {
    return props.type === "password"
        ? showPassword.value
            ? "text"
            : "password"
        : props.type;
});
</script>

<template>
    <div class="w-full cursor-auto!">
        <p v-if="label" class="mb-2 text-sm">
            {{ label }} <span v-if="required" class="text-red-600">*</span>
        </p>

        <div
            class="input-wrapper flex items-center rounded-md ring-1 ring-gray-700 transition-all focus-within:ring-2 focus-within:ring-black/70! hover:ring-gray-500"
            :class="{
                'ring-red-500! focus-within:ring-red-500!': error,
                'ring-gray-400!': disabled,
            }"
        >
            <input
                v-model="inputModel"
                class="h-full max-h-max w-full! border-none! bg-transparent px-2 py-3 text-sm outline-none placeholder:text-gray-500 focus:border-none! focus:shadow-none! disabled:text-gray-400"
                :placeholder="placeholder"
                :type="inputType"
                :name="placeholder"
                :required="required"
                :step="isFloat ? 0.01 : 1"
                :disabled="disabled"
            />

            <div
                v-if="type === 'password'"
                class="mr-2"
                @click="showPassword = !showPassword"
            >
                <EyeIcon
                    v-if="showPassword"
                    class="size-6 cursor-pointer [&_path]:stroke-gray-400"
                />
                <EyeCloseIcon
                    v-else
                    class="size-6 cursor-pointer [&_path]:stroke-gray-400"
                />
            </div>
        </div>

        <p v-if="desc" class="mt-2 text-xs">
            {{ desc }}
        </p>

        <p v-if="error" class="mt-1 text-sm text-red-500">
            {{ error }}
        </p>
    </div>
</template>
