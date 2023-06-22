<script setup>
import { ref, watch } from 'vue';
import Post from './Post.vue'
import { useVirtualList } from '@vueuse/core'
import { Head } from "@inertiajs/vue3";
import useInfiniteScroll from './Composables/useInfiniteScroll';
const props = defineProps({
    posts: Object,
});

const landmark = ref(null);

const { data } = useInfiniteScroll('posts', landmark, '0px 0px 150px 0px');
const { list, containerProps, wrapperProps } = useVirtualList(data, { itemHeight: 830 })

watch(() => props.posts, val => {
    console.log(val);
    console.log(data.value);
})

</script>

<template>
    <Head title="Welcome" />
    <div class="overflow-x-hidden" v-bind="containerProps">
        <div class="text-white grid grid-cols-9 mt-24" v-bind="wrapperProps">
            <div class="col-start-4 col-span-4">
                <div v-for="(post, index) in data" :key="index" class="px-4 flex flex-col justify-center mt-6">
                    <Post class="pb-6 border-b border-[#262626]" :post="post" />
                </div>
            </div>
            <div class="w-[319px]">
            </div>
        </div>
        <div ref="landmark"></div>
    </div>
</template>
