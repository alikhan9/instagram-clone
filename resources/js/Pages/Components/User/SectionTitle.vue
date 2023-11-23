<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { Link } from '@inertiajs/vue3';

defineProps({
    path: String,
    title: String,
    active: Number,
    whenActive: String,
    value: {
        type: String,
        default: 'posts'
    }
});


</script>

<template>
    <Link :href="$page.props.ziggy.location" :data="{ value }" :only="['posts','active','total_posts ']" :class="{
        'py-5 shrink flex items-center hover:cursor-pointer': true,
        'border-t': active == whenActive,
        'text-[#A8A8A8]': active != whenActive
    }">
    <div>
        <svg-icon class="mr-2 w-16 sm:w-8" type="mdi" :path="path"></svg-icon>
    </div>
    <p class="hidden sm:block">{{ title }}</p>
    </Link>
</template>