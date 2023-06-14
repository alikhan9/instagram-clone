<script setup>
import { onMounted, ref } from 'vue';
import Post from './Post.vue'
import { useInfiniteScroll, useVirtualList } from '@vueuse/core'
import {
    Head
} from "@inertiajs/vue3";
import axios from 'axios';
const props = defineProps({
    posts: Array
});


const data = ref(props.posts);
const offset = ref(4);
const loading = ref(false);
const { list, containerProps, wrapperProps, scrollTo } = useVirtualList(data, {
    itemHeight: 650
}
)

onMounted(() => {
    console.log(props.posts);
})

useInfiniteScroll(
    containerProps.ref,
    () => {
        if (loading.value)
            return;
        loading.value = true;
        axios.get('/loadMore/' + offset.value)
            .then(response => {
                if (!response.data)
                    return;
                data.value.push(...response.data);
                offset.value += 2;
                loading.value = false;
            })
    },
    { distance: 10 },
)

</script>

<template>
    <Head title="Welcome" />
    <div class="h-screen w-screen " v-bind="containerProps">
        <div class="text-white grid grid-cols-7 mt-24" v-bind="wrapperProps">
            <div class="col-start-3 col-span-3">
                <div v-for="(post, index) in data" :key="index" class="w-[630px] px-4 flex flex-col justify-center mt-6">
                    <Post class="pb-6 border-b border-[#262626]" :post="post" />
                </div>
            </div>
            <div class="w-[319px] bg-white">
            </div>
        </div>
    </div>
</template>
