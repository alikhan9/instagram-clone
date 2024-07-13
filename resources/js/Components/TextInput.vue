<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    dark : {
        type: Boolean,
        default: false,
    }
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({
    focus: () => input.value.focus(),
});
</script>

<template>
    <input v-if="dark"
        class="mt-4 block w-full rounded-[3px] border-[#DBDBDB] bg-transparent py-[9px] text-black outline-none focus:ring-0"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
    <input v-else
        class="mt-4 block w-full rounded-[3px] border-none border-[#DBDBDB] bg-transparent py-[9px] text-white outline-none focus:ring-0"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
