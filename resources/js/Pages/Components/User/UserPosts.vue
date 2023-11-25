<script setup>
import useInfiniteScroll from '../../Composables/useInfiniteScroll.js'
import { usePostStore } from '../../useStore/usePostStore.js'
import { ref, computed } from 'vue'
import Comments from '../../Comments.vue'
import MobileUserPagePost from '@/Pages/MobileUserPagePost.vue'
import axios from 'axios';
import { useDebounceFn } from '@vueuse/core'
import empty from '@/../images/empty.png'
import { router, usePage } from '@inertiajs/vue3';
import { useWindowSize } from '@vueuse/core'

const landmark = ref(null);
const posts = usePostStore();
const showComments = ref(false);
const bookmark = ref(false);
const like = ref(false);
const post = ref(null);

const { width, height } = useWindowSize()



const getSlicedPosts = n => {
    return computed(() => {
        const startIndex = n * 3
        const endIndex = startIndex + 3;
        const result = posts.value.slice(startIndex, endIndex);
        while (result.length < 3) {
            result.push(null);
        }
        return result;
    });
};

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
    console.log('what?');

    if (!showComments.value)
        router.get(usePage().url, { pid: selected_post.id }, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ['post', 'comments'],
            onFinish: () => {
                showComments.value = true;
            }
        });
    else {
        showComments.value = false;
    }
}


</script>

<template>
    <div class="col-start-3 overflow-x-hidden xl:w-[935px] ">
        <div v-if="showComments">
            <Transition enter-from-class="opacity-0" enter-leave-class="opacity-100"
                enter-active-class="transition-opacity ease-in duration-100" leave-to-class="opacity-0"
                leave-active-class="transition duration-200 ease-in">
                <Comments v-if="width > 1023" class="hidden lg:block z-[999]" @close="toggleComments" />
            </Transition>
            <Transition enter-from-class="opacity-0" enter-leave-class="opacity-100"
                enter-active-class="transition-opacity ease-in duration-100" leave-to-class="opacity-0"
                leave-active-class="transition duration-200 ease-in">
                <MobileUserPagePost v-if="width < 1024" class="lg:hidden z-[999]" @close="toggleComments" />
            </Transition>
        </div>
        <div class="">
            <div class="min-w-full max-h-[309px] flex overflow-hidden"
                v-for="(item, index) in posts.getValue().filter((_, i) => i % 3 === 0)" :key="index">
                <div v-for="(post, indexSmall) in getSlicedPosts(index).value" :key="indexSmall">
                    <img v-if="post && post.image !== null"
                        class="w-[309px] aspect-square hover:cursor-pointer object-cover " @click="toggleComments(post)"
                        :src="post.image.replace('medium', 'small')" alt="">
                    <video v-else-if="post" class="object-cover aspect-square w-[309px]  hover:cursor-pointer "
                        @click="toggleComments(post)">
                        <source :src="post.video" />
                        Your browser does not support the video tag.
                    </video>
                    <img v-else class="w-[309px] aspect-square object-cover " :src="empty" alt="">
                </div>
            </div>
            <div class="w-[319px]">
            </div>
        </div>
        <div ref="landmark"></div>
    </div>
</template>
