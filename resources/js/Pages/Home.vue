<script setup>
import Post from './Post.vue'
import {
    Head,
} from "@inertiajs/vue3";
defineProps({
    posts: Object
});
</script>

<template>
    <Head title="Welcome" />
    <div class="text-white flex w-full ml-52 justify-evenly">
        <div class="w-[630px] px-4 flex justify-center mt-24">
            <Post />
        </div>
        <div class="w-[319px]">
        </div>
    </div>
</template>
