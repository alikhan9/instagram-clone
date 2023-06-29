<script setup>
import SvgIcon from '@jamescoyle/vue-icon';

defineProps({
    path: String,
    title: String,
    active: String,
    whenActive: String,
});

</script>

<template>
    <div :class="{
        'py-5 flex items-center hover:cursor-pointer': true,
        'border-t': active == whenActive,
        'text-[#A8A8A8]': active != whenActive
    }">
        <svg-icon class="mr-2" type="mdi" size="13" :path="path"></svg-icon>
        <p>{{ title }}</p>
    </div>
</template>