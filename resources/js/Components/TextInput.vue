<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    isInvalid: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 border-2 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-700 dark:focus:ring-indigo-700 rounded-md shadow-sm"
        :class="{
            'border-red-500': isInvalid,
            'cursor-not-allowed': disabled,
            'opacity-50': disabled,
        }"
        :disabled="disabled"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
