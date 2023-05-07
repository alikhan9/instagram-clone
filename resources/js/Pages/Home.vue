<script setup>
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
        <div class="w-[630px] bg-gray-500">Nani</div>
        <div class="w-[319px] bg-gray-100"></div>
    </div>
</template>
