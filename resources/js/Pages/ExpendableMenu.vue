<script setup>
import { mdiChevronDown, mdiChevronUp } from '@mdi/js';
import SvgIcon from '@jamescoyle/vue-icon';
import { ref } from "vue"

const expend = ref(false);

defineProps({
    title: String
});

const closeExpendMenu = () => {
    expend.value = !expend.value;
}

</script>

<template>
    <div class="flex justify-between hover:cursor-pointer" @click="closeExpendMenu">
        <h1 class="text-xl">{{ title }}</h1>
        <svg-icon v-if="expend" type="mdi" size="24" :path="mdiChevronUp" />
        <svg-icon v-else type="mdi" size="24" :path="mdiChevronDown" />
    </div>
    <div class="" v-if="expend">
        <slot></slot>
    </div>
</template>