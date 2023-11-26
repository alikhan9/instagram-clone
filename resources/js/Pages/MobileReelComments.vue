<script setup>
import { onClickOutside } from "@vueuse/core";
import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiHeart, mdiBookmark, mdiEmoticonHappyOutline, mdiClose } from "@mdi/js";
import EmojiPicker from "vue3-emoji-picker";
import { onUnmounted, onMounted } from "vue";
import CommentContent from "./CommentContent.vue";
import { usePostStore } from "./useStore/usePostStore";
import axios from "axios";
import useInfiniteScroll from './Composables/useInfiniteScroll';


const emit = defineEmits(["toggleComments"]);
const props = defineProps({
    post: Object,
    like: Boolean,
    bookmark: Boolean,
    likeUnlikePost: Function,
    bookmarkPost: Function,
});

const posts = usePostStore();
const showEmojiPicker = ref(false);
const mobileCommentReel = ref(null);
const currentComment = ref("");
const inputRef = ref(null);
const responseTo = ref(null);
const reelCommentsMobile = ref(null);
onClickOutside(mobileCommentReel, () => close());

useInfiniteScroll('comments', reelCommentsMobile, '0px 0px 150px 0px', ['comments']);

onMounted(() => {
    window.Echo.channel("post-" + props.post.id).listen(".comments", (e) => {
        if (!e[1]) posts.addComment(e[0]);
        else posts.addCommentResponse(e[0]);
    })
        .listen(".likes", (e) => {
            if (e.add) {
                if (!e.isResponse)
                    posts.addLikeToComment(e.like);
                else
                    posts.addLikeToResponse(e.like, e.commentId);
            } else {
                if (!e.isResponse)
                    posts.removeLikeFromComment(e.like);
                else
                    posts.removeLikeFromResponse(e.like, e.commentId);
            }

        });
});

onUnmounted(() => {
    window.Echo.leave("post-" + props.post.id);
});

const onSelectEmoji = (emoji) => {
    currentComment.value += emoji.i;
    showEmojiPicker.value = false;
};

const close = () => {
    emit("toggleComments");
};

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
            post_id: props.post.id,
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
</script>

<template>
    <div class="fixed left-0 bottom-0 z-[99]">
        <div ref="mobileCommentReel"
            class="flex flex-col mt-[20vh] h-[80vh] w-screen left-0 border-[#262626] bg-[#262626] rounded-t-lg">
            <div class="text-white text-center w-full relative flex justify-center py-5 border-[#262626]">
                <div class="px-6 absolute self-start left-0 ">
                    <svg-icon class="hover:cursor-pointer " @click="close" type="mdi" :path="mdiClose"></svg-icon>
                </div>
                <div class="text-center text-xl">
                    Commentaires
                </div>
            </div>
            <div class="border-b py-5 flex-1 h-full overflow-auto border-[#262626]">
                <div class="mx-6 mb-8" v-for="(comment, index) in posts.comments" :key="index">
                    <CommentContent @sendResponseComment="addResponseComment" :postId="post.id" :comment="comment" />
                </div>
                <div ref="reelCommentsMobile"></div>
            </div>
            <div class="flex items shrink-0 text-white items-center gap-4 mb-5 pt-4 px-6">
                <div class="border border-[#999999] rounded-full
                     w-full">
                    <input @keydown.enter="publishComment" ref="inputRef"
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

