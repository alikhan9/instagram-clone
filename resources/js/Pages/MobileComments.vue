<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { usePostStore } from './useStore/usePostStore';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiArrowLeft } from '@mdi/js';
import CommentContent from './CommentContent.vue';
import useInfiniteScroll from './Composables/useInfiniteScroll';
import { ref, onMounted, onUnmounted } from 'vue'
import { mdiEmoticonHappyOutline } from "@mdi/js";
import EmojiPicker from "vue3-emoji-picker";

defineProps({
    scrollPosition: Number
})

const post = usePage().props.post;
const posts = usePostStore()
const emit = defineEmits(['toggleComments'])
const landmarkMobileComments = ref(null);
const showEmojiPicker = ref(false);
const currentComment = ref('');
const responseTo = ref(null);
const inputRef = ref(null);

const close = () => {
    emit('toggleComments');
}

useInfiniteScroll('comments', landmarkMobileComments, '0px 0px 150px 0px', ['comments']);


onMounted(() => {
    window.Echo.channel("post-" + post.id).listen(".comments", (e) => {
        if (!e[1]) posts.addComment(e[0]);
        else posts.addCommentResponse(e[0]);
    })
        .listen(".likes", (e) => {
            if (e.add) {
                if (!e.isResponse)
                    posts.addLikeToComment(e.like, e.postId);
                else
                    posts.addLikeToResponse(e.like, e.postId, e.commentId);
            } else {
                if (!e.isResponse)
                    posts.removeLikeFromComment(e.like, e.postId);
                else
                    posts.removeLikeFromResponse(e.like, e.postId, e.commentId);
            }
        });
});


const publishComment = (event) => {
    if (event.shiftKey) return;

    if (responseTo.value)
        axios.post("/post/comment/response", {
            post_comment_id: responseTo.value.hasOwnProperty('post_id') ? responseTo.value.id : responseTo.value.post_comment_id,
            content: currentComment.value,
            user_id: responseTo.value.user_id,
        });
    else
        axios.post("/post/comment", {
            post_id: post.id,
            content: currentComment.value,
        });

    currentComment.value = "";
    responseTo.value = null;
};

const resize = (e) => {
    e.target.style.height = "auto";
    e.target.style.height = `${e.target.scrollHeight - 32}px`;
};

const addResponseComment = (data) => {
    currentComment.value = "@" + data.user.name + " ";
    responseTo.value = data;
    inputRef.value.focus();
};

onUnmounted(() => {
    window.Echo.leave("post-" + post.id);
});


</script>

<template>
    <div class="text-white bg-black inset-0 fixed z-[999]">
        <div class="mt-5 flex flex-col h-full">
            <div class="flex justify-between px-4 pb-4 border-[#262626] border-b">
                <div>
                    <svg-icon size="30" @click="close" class="hover:cursor-pointer" type="mdi"
                        :path="mdiArrowLeft"></svg-icon>
                </div>
                <div class="text-xl">Commentaires</div>
                <div></div>
            </div>
            <div
                class="border-b flex-1 h-full py-5 lg:h-[73%] border-[#262626] overflow-auto scrollbar-hide sm:scrollbar-default">
                <div class="mx-6 mb-8" v-for="(comment, index) in posts.comments" :key="index">
                    <CommentContent @sendResponseComment="addResponseComment" :postId="post.id" :comment="comment" />
                </div>
                <div ref="landmarkMobileComments"></div>
            </div>
            <div class="flex items shrink-0 items-center gap-4 h-[100px] px-6">
                <svg-icon class="hover:cursor-pointer" type="mdi" size="32" @click="showEmojiPicker = !showEmojiPicker"
                    :path="mdiEmoticonHappyOutline" />
                <EmojiPicker class="absolute bottom-[10%] z-10" v-if="showEmojiPicker" :native="true"
                    @select="onSelectEmoji" />
                <div class="border border-[#262626] rounded-full py-2
                     w-full">
                    <input @keydown.enter="publishComment" @input="resize($event)" ref="inputRef"
                        class="bg-transparent float-left no-scrollbar resize-none  w-[93%] border-none focus:ring-0"
                        placeholder="Ajouter un commentaire..." type="text" v-model="currentComment" />
                </div>
                <button :class="{
                    'text-[hsl(204,90%,49%)] text-xl hover:text-white':
                        currentComment.length > 0,
                    'text-gray-600 text-xl hover:cursor-default': currentComment.length === 0,
                }" @click="publishComment" :disabled="currentComment.length === 0">
                    Publier
                </button>
            </div>
        </div>
    </div>
</template>