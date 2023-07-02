<script setup>
import { onClickOutside } from '@vueuse/core';
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark, mdiEmoticonHappyOutline } from '@mdi/js';
import EmojiPicker from 'vue3-emoji-picker'
import { onUnmounted, onMounted } from 'vue';
import CommentContent from './CommentContent.vue';
import { usePostStore } from './useStore/usePostStore';
import axios from 'axios';


const emit = defineEmits(['update:showComments']);
const props = defineProps({
    post: Object,
    like: Boolean,
    bookmark: Boolean,
    likeUnlikePost: Function,
    bookmarkPost: Function
});

const posts = usePostStore();
const showEmojiPicker = ref(false);
const target = ref(null);
const currentComment = ref('');
const inputRef = ref(null);
const responseTo = ref(null);
onClickOutside(target, () => emit('update:showComments', false));



onMounted(() => {
    window.Echo.channel('post-' + props.post.id).listen(
        ".comments",
        e => {
            if (!e[1])
                posts.addCommentToPost(e[0])
            else
                posts.addCommentResponse(e[0]);
        }
    );
});

onUnmounted(() => {
    window.Echo.leave('post-' + props.post.id);
})



const onSelectEmoji = emoji => {
    currentComment.value += emoji.i;
    showEmojiPicker.value = false;
}

const close = () => {
    emit('update:showComments', false);
}

const publishComment = event => {
    if (event.shiftKey)
        return;

    // const regex = /(^|\s)@(\w+)/g;
    // const matches = currentComment.value.match(regex);

    // if (matches) {
    //     const names = matches.map(match => match.trim().replace('@', ''));
    //     console.log(names);
    // } else {
    //     console.log('No names found in the text.');
    // }

    if (responseTo.value !== null)
        axios.post('/post/comment/response',
            {
                post_comment_id: responseTo.value.comment_id,
                content: currentComment.value,
                user_id: responseTo.value.id
            })
    else
        axios.post('/post/comment', { post_id: props.post.id, content: currentComment.value });

    currentComment.value = '';
    responseTo.value = null;

}

const resize = e => {
    e.target.style.height = 'auto';
    e.target.style.height = `${e.target.scrollHeight - 32}px`;
}

const addResponseComment = data => {
    currentComment.value = '@' + data.name + ' ';
    responseTo.value = data;
    inputRef.value.focus();
}


</script>

<template>
    <div class="fixed z-50 backdrop-brightness-[0.4] flex justify-center top-0 right-0 items-center  w-screen h-screen">
        <div ref="target">
            <div class="h-[90vh] min-w-[50vw] flex bg-black ">
                <img class="ml-2 max-w-[60%]" :src="usePage().props.ziggy.url + post.image.replace('medium', 'big')">
                <div class="w-[500px] border-l border-[#262626] ">
                    <div class="flex justify-between border-b items-center py-5 border-[#262626]">
                        <div class="flex gap-3 px-6 items-center w-full ">
                            <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                            <div>{{ post.user.name }}</div>
                        </div>
                        <div class="px-6">
                            <unicon class="hover:cursor-pointer" name="ellipsis-h" fill="white"></unicon>
                        </div>
                    </div>
                    <div class="border-b py-5 h-[73%] border-[#262626] overflow-auto no-scrollbar">
                        <div class="mx-6 mb-8"
                            v-for="(comment, index) in post.comments.sort((a, b) => a.created_at - b.created_at)"
                            :key="index">
                            <CommentContent @sendResponseComment="addResponseComment" :comment="comment" />
                        </div>
                    </div>
                    <div class="border-b border-[#262626] py-5">
                        <div class=" px-6 flex flex-row justify-between">
                            <div class="flex gap-3">
                                <svg-icon v-if="like" class="w-7 h-7 hover:cursor-pointer animate-heart " type="mdi"
                                    color="red" @click="likeUnlikePost(post.id)" :path="mdiHeart" />
                                <div v-else class="h-7" @click="likeUnlikePost(post.id)">
                                    <unicon class="w-7 h-7 hover:cursor-pointer" name="heart" fill="white" />
                                </div>
                                <span @click="close">
                                    <unicon class="w-7 h-7 hover:cursor-pointer" name="comment" fill="white"></unicon>
                                </span>
                                <unicon class="w-7 h-7" name="telegram-alt" fill="white"></unicon>
                            </div>
                            <div>
                                <div class="h-7" v-if="!bookmark" @click="bookmarkPost">
                                    <unicon class="w-7 h-7 hover:cursor-pointer" name="bookmark" fill="white"></unicon>
                                </div>
                                <svg-icon v-else="like" class="w-7 h-7 hover:cursor-pointer" type="mdi" color="white"
                                    @click="bookmarkPost" :path="mdiBookmark" />
                            </div>
                        </div>
                        <div class="px-6 mt-5">
                            <p class="text-xl"> {{ post.likes.length }} J'aime</p>
                            <p class="text-sm text-[hsl(0,0%,60%)]">{{ post.updated_created_at }}</p>
                        </div>
                    </div>
                    <div class="flex items items-center gap-4 h-[7%] px-6 py-5">
                        <svg-icon class="hover:cursor-pointer" type="mdi" size="32"
                            @click="showEmojiPicker = !showEmojiPicker" :path="mdiEmoticonHappyOutline" />
                        <EmojiPicker class="absolute bottom-[10%] z-10" v-if="showEmojiPicker" :native="true"
                            @select="onSelectEmoji" />
                        <textarea @keydown.enter="publishComment" @input="resize($event)" ref="inputRef"
                            class="bg-transparent float-left no-scrollbar resize-none h-8 max-h-16 w-[93%] border-none focus:ring-0"
                            placeholder="Ajouter un commentaire..." type="text" v-model="currentComment"></textarea>
                        <button :class="{
                            'text-[hsl(204,90%,49%)] text-xl hover:text-white': currentComment.length > 0,
                            'text-gray-600 text-xl hover:cursor-default': currentComment.length === 0
                        }" @click="publishComment" :disabled="currentComment.length === 0">Publier</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>