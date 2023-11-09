<script setup>
import { ref } from 'vue';
import Reel from './Reel.vue'
import { useVirtualList } from '@vueuse/core'
import { Head, Link, usePage } from "@inertiajs/vue3";
import useInfiniteScroll from './Composables/useInfiniteScroll';
import { usePostStore } from './useStore/usePostStore';
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

const landmark = ref(null);
const posts = usePostStore();
const open = ref(true);

const props = defineProps({
    posts: Object,
    followed: Boolean,
})

const items = usePage().props['posts'].data;

// useInfiniteScroll('posts', landmark, '0px 0px 150px 0px');
const { list, containerProps, wrapperProps } = useVirtualList(props.posts.data, { itemHeight: 830 })

</script>

<template>
    <Head title="Home" />
    <div class="overflow-x-hidden overflow-auto" v-bind="containerProps">
        <div class="flex gap-4 border-b border-[#262626] font-bold mx-20 mt-12 text-lg pb-3">
            <Link href="/reels" :class="{ 'text-white ': !followed, 'text-[#868686]': followed }">
            Suggestions
            </Link>
            <Link href="/reels" :data="{ followed: true }" :class="{ 'text-white': followed, 'text-[#868686]': !followed }">
            Suivi(e)
            </Link>
        </div>
        <div class="text-white mt-24" v-bind="wrapperProps">
            <div v-scroll-lock="open">

                <carousel class="bg-white text-black h-screen w-full flex-col" :items-to-show="1.5">
                    <slide class="h-[500px] flex-col" v-for="post in items" :key="post.id">
                        <Reel :followed="followed" v-if="post"
                            class="pb-6 col-start-3 col-span-4 border-b border-[#262626] mr-36" :post="post" />
                    </slide>

                    <template #addons>
                        <navigation />
                        <pagination />
                    </template>
                </carousel>

            </div>
            <div class="w-[319px]">
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
