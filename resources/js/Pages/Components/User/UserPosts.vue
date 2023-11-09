<script setup>
import useInfiniteScroll from '../../Composables/useInfiniteScroll.js'
import { usePostStore } from '../../useStore/usePostStore.js'
import { ref } from 'vue'
import Comments from '../../Comments.vue'
import axios from 'axios';
import { useDebounceFn } from '@vueuse/core'

const landmark = ref(null);
const posts = usePostStore();
const showComments = ref(false);
const bookmark = ref(false);
const like = ref(false);
const post = ref(null);

const sendLike = useDebounceFn((id, value) => {
    axios.post(`/post/${id}/like`, { value })
}, 500);

useInfiniteScroll('posts', landmark, '0px 0px 150px 0px');


const likeUnlikePost = id => {
    like.value = !like.value;
    sendLike(id, like.value);
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
    <div class="col-start-3 overflow-x-hidden w-[935px]">
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
                    <img v-if="post.image !== null" class="w-[309px] hover:cursor-pointer h-[309px]"
                        @click="toggleComments(post)" :src="post.image.replace('medium', 'small')" alt="">
                    <video v-else class="w-[309px] hover:cursor-pointer h-[309px]" @click="toggleComments(post)">
                        <source :src="post.video" />
                        Your browser does not support the video tag.
                    </video>
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