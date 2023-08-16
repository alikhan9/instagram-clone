<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { Link } from '@inertiajs/vue3';

defineProps({
    path: String,
    title: String,
    active: String,
    whenActive: String,
    value: {
        type: String,
        default: 'posts'
    }
});


</script>

<template>
    <Link :href="$page.props.ziggy.location" :data="{ value }" :only="['posts','active','total_posts ']" :class="{
        'py-5 flex items-center hover:cursor-pointer': true,
        'border-t': active == whenActive,
        'text-[#A8A8A8]': active != whenActive
    }">
    <svg-icon class="mr-2" type="mdi" size="13" :path="path"></svg-icon>
    <p>{{ title }}</p>
    </Link>
</template>