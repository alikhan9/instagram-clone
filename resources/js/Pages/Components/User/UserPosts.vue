<script setup>
import { useVirtualList } from '@vueuse/core';
import useInfiniteScroll from '../../Composables/useInfiniteScroll.js'
import { usePostStore } from '../../useStore/usePostStore.js'
import { ref } from 'vue'
import Comments from '../../Comments.vue'

const landmark = ref(null);
const posts = usePostStore();
const showComments = ref(false);
const bookmark = ref(false);
const like = ref(false);
const post = ref(null);

useInfiniteScroll('posts', landmark, '0px 0px 150px 0px');
// const { list, containerProps, wrapperProps } = useVirtualList(posts.getValue(), { itemHeight: 309, itemWidth: 927 })


const likeUnlikePost = id => {
    router.post(`/post/${id}/like`, {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            window.history.replaceState({}, '', '/');
            like.value = !like.value;
        }
    });
}
const bookmarkPost = () => {
    bookmark.value = !bookmark.value;
}

const toggleComments = selected_post => {
    post.value = selected_post;
    showComments.value = true;
}

</script>

<template>
    <div class="col-start-5 overflow-x-hidden w-[935px]">
        <div v-if="showComments">
            <Transition enter-from-class="opacity-0" enter-leave-class="opacity-100"
                enter-active-class="transition-opacity ease-in duration-100" leave-to-class="opacity-0"
                leave-active-class="transition duration-200 ease-in">
                <Comments class="z-[999]" v-model:showComments="showComments" v-model:bookmark="bookmark"
                    v-model:like="like" :likeUnlikePost="likeUnlikePost" :bookmarkPost="bookmarkPost" :post="post" />
            </Transition>
        </div>
        <div class="">
            <div class="post-grid">
                <div v-for="(post, index) in posts.getValue()" :key="index">
                    <img class="w-[309px] hover:cursor-pointer h-[309px]" @click="toggleComments(post)" :src="post.image"
                        alt="">
                </div>
            </div>
            <div class="w-[319px]">
            </div>
        </div>
        <div ref="landmark"></div>
    </div>
</template>

<style>
.post-grid {
    display: grid;
    grid-template-columns: repeat(3, 309px);
    grid-template-rows: 309px;
    gap: .3em;
}
</style>