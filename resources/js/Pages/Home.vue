<script setup>
import { ref } from 'vue';
import Post from './Post.vue'
import { useVirtualList } from '@vueuse/core'
import { Head } from "@inertiajs/vue3";
import useInfiniteScroll from './Composables/useInfiniteScroll';
import { usePostStore } from './useStore/usePostStore';


const landmark = ref(null);
const posts = usePostStore();

useInfiniteScroll('posts', landmark, '0px 0px 150px 0px');
const { list, containerProps, wrapperProps } = useVirtualList(posts.getValue(), { itemHeight: 830 })


</script>

<template>
    <Head title="Welcome" />
    <div class="overflow-x-hidden" v-bind="containerProps">
        <div class="text-white grid grid-cols-9 mt-24" v-bind="wrapperProps">
            <div class="col-start-4 col-span-4">
                <div v-for="(post, index) in posts.getValue()" :key="index" class="px-4 flex flex-col justify-center mt-6">
                    <Post class="pb-6 border-b border-[#262626]" :post="post" />
                </div>
            </div>
            <div class="w-[319px]">
            </div>
        </div>
        <div ref="landmark"></div>
    </div>
</template>
