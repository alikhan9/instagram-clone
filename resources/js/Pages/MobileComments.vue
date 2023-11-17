<script setup>
import { Link } from '@inertiajs/vue3';
import { usePostStore } from './useStore/usePostStore';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiArrowLeft } from '@mdi/js';
import CommentContent from './CommentContent.vue';

defineProps({
    scrollPosition: Number
})


const emit = defineEmits(['toggleComments'])
const posts = usePostStore();
const close = () => {
    emit('toggleComments');
}

// useInfiniteScroll('post', landmark, '0px 0px 150px 0px', ['sComments','post']);




</script>

<template>
    <div class="text-white h-screen sticky">
        <div class="mt-5">
            <div class="flex justify-between px-4 pb-4 border-[#262626] border-b">
                <div>
                    <svg-icon size="30" @click="close" class="hover:cursor-pointer" type="mdi"
                        :path="mdiArrowLeft"></svg-icon>
                </div>
                <div class="text-xl">Commentaires</div>
                <div></div>
            </div>
            <div>
                <div class="border-b py-5 h-[73%] border-[#262626] overflow-auto no-scrollbar">
                    <div class="mx-6 mb-8" v-for="(comment, index) in posts.post.comments" :key="index">
                        <CommentContent @sendResponseComment="addResponseComment" :postId="posts.post.id"
                            :comment="comment" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>