<script setup>
import { ref } from 'vue';
import Post from './Post.vue'
import { Head } from "@inertiajs/vue3";
import useInfiniteScroll from './Composables/useInfiniteScroll';
import { usePostStore } from './useStore/usePostStore';

const landmark = ref(null);
const posts = usePostStore();
const open = ref(true);

useInfiniteScroll('posts', landmark, '0px 0px 150px 0px');

</script>

<template>
    <Head title="Home" />
    <div>
        <div class="text-white xl:grid grid-cols-9 xl:mt-24 w-full">
            <div class="col-start-3 flex items-center flex-col col-span-4 ">
                <div v-for="(post, index) in posts.getValue()" :key="index" class="px-4 flex flex-col justify-center mt-6">
                    <Post class="pb-6 border-b border-[#262626]" :post="post" />
                </div>
            </div>
            <div class="hidden w-[319px]">
            </div>
        </div>
        <div ref="landmark"></div>
    </div>
</template>

<style>
.no-scroll {
    overflow: hidden !important;
}
</style>
