<script setup>
import { ref, watch } from "vue";

defineProps({
    title: String,
});

const emit = defineEmits(['updateValue']);
const checkValue = ref(false);

watch(checkValue, (newV, oldV) => {
    emit('updateValue', newV);
});


</script>

<template>
    <div class="flex justify-between hover:cursor-pointer">
        <h1 class="text-xl max-w-[83%]">{{ title }}</h1>
        <label class="relative inline-flex items-center cursor-pointer h-6">
            <input type="checkbox" v-model="checkValue" class="sr-only peer" checked>
            <div
                class="w-[44px] h-8 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
            </div>
            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"></span>
        </label>
    </div>
    <div class="mt-5">
        <slot></slot>
    </div>
</template>