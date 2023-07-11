<script setup>
import SvgIcon from '@jamescoyle/vue-icon'
import { ref } from "vue"
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    path: String,
    as: {
        type:String,
        default: 'link'
    },
    method: {
        type: String,
        default: 'get'
    },
    mini: Boolean,
    url: {
        type: String,
        default: '#',
    },
    isLink: {
        type: Boolean,
        default: true,
    },
    animateIcon: {
        type: Boolean,
        default: true,
    },
    allowIcon: {
        type: Boolean,
        default: true,
    }
});

const animateIcon = ref(false);

const startAnimate = () => {
    if (!props.animateIcon)
        return;
    animateIcon.value = true;
}

const stopAnimate = () => {
    animateIcon.value = false;
}

</script>
<template>
    <Link v-if="isLink" :href="url" :as="as" :method="method" @mouseover="startAnimate" @mouseleave="stopAnimate" :class="{
        'flex items-center gap-4 p-4 text-base w-[250px] font-normal transition rounded-lg hover:cursor-pointer': true,
        'hover:bg-[hsl(0,0%,25%)]': !mini
    }">
    <div v-if="allowIcon" :class="{ 'scale-[1.05] transition duration-75': animateIcon }">
        <svg-icon size="20" type="mdi" :path="path" />
    </div>
    <p>
        <slot></slot>
    </p>
    </Link>
    <div v-else :href="url" @mouseover="startAnimate" @mouseleave="stopAnimate" :class="{
        'flex  items-center gap-4 p-2 text-lg font-bold  transition ease-in rounded-lg duration-200 hover:cursor-pointer': true,
        'hover:bg-[#262626]': !mini
    }">
        <div v-if="allowIcon" :class="{ 'scale-[1.05] transition duration-75': animateIcon }">
            <svg-icon size="30" type="mdi" :path="path" />
        </div>
        <p>
            <slot></slot>
        </p>
    </div>
</template>