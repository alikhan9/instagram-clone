<script setup>
import SvgIcon from '@jamescoyle/vue-icon'
import { ref } from "vue"
import { Link } from '@inertiajs/vue3';
defineProps({
    path: String,
    mini: {
        type: Boolean,
        default: false
    },
    url: {
        type: String,
        default: '#'
    },
    isLink: {
        type: Boolean,
        default: true
    },
    dontHideContent: {
        type: Boolean,
        default: false
    },
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
    <Link v-if="isLink" :href="url" @mouseover="startAnimate" :preserve-state="false" @mouseleave="stopAnimate" :class="{
        'flex shrink min-w-0 items-center hover:bg-[hsl(0,0%,10%)] gap-4 p-2 text-lg font-bold  transition ease-in rounded-lg duration-300 hover:cursor-pointer': true, 'w-[3.25rem]': mini
    }">
    <div :class="{ 'scale-[1.05] transition duration-75': animateIcon }">
        <svg-icon size="30" type="mdi" :path="path" />
    </div>
    <p :class="{ 'hidden  lg:block': !dontHideContent, 'lg:hidden': mini && !dontHideContent }">
        <slot></slot>
    </p>
    </Link>
    <div v-else :href="url" @mouseover="startAnimate" @mouseleave="stopAnimate" :class="{
        'flex min-w-0 hover:bg-[#262626] items-center gap-4 p-2 text-lg font-bold  transition ease-in rounded-lg duration-200 hover:cursor-pointer': true, 'w-[3.25rem]': mini
    }">
        <div :class="{ 'scale-[1.05] transition duration-75': animateIcon }">
            <svg-icon size="30" type="mdi" :path="path" />
        </div>
        <p :class="{ 'hidden  lg:block': true, 'lg:hidden': mini }">
            <slot></slot>
        </p>
    </div>
</template>