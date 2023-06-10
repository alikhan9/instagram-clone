<script setup>
import SvgIcon from '@jamescoyle/vue-icon'
import { ref } from "vue"
defineProps({
    path: String,
    mini: Boolean
})

const animateIcon = ref(false);

const startAnimate = () => {
    animateIcon.value = true;
}

const stopAnimate = () => {
    animateIcon.value = false;
}

</script>
<template>
    <div @mouseover="startAnimate" @mouseleave="stopAnimate" :class="{
        'flex  items-center gap-4 p-2 text-lg font-bold  transition ease-in rounded-lg duration-200 hover:cursor-pointer': true,
        'hover:bg-[#262626]': !mini
    }">
        <div :class="{ 'scale-[1.05] transition duration-75': animateIcon }">
            <svg-icon size="30" type="mdi" :path="path" />
        </div>
        <p>
            <slot></slot>
        </p>
    </div>
</template>