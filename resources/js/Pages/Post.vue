<script setup>
import { router, usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark, mdiEmoticonHappyOutline } from '@mdi/js';
import { useImage, useFullscreen } from '@vueuse/core';
import { ref, watch, onMounted, watchEffect } from 'vue';
import Comments from './Comments.vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3'
import EmojiPicker from 'vue3-emoji-picker'
import { onClickOutside } from '@vueuse/core'
import { usePostStore } from './useStore/usePostStore';
import { useDebounceFn } from '@vueuse/core'


const props = defineProps({
    post: Object,
})

const posts = usePostStore();
const emojiRef = ref(null);
const currentComment = ref('');
const showEmojiPicker = ref(false);
const like = ref();
const bookmark = ref(false);
const { isLoading } = useImage({ src: 'http://127.0.0.1:8000' + props.post.image })
const imgRef = ref(null);
const showComments = ref(false);

const sendLike = useDebounceFn((id, value) => {
    axios.post(`/post/${id}/like`, { value })
}, 500);

const { isFullscreen, enter, exit, toggle } = useFullscreen(imgRef)

onMounted(() => {
    like.value = props.post.user_liked.length !== 0;
});

watch(() => props.post, newValue => {
    like.value = newValue.user_liked.length !== 0;
})

onClickOutside(emojiRef, () => {
    if (showEmojiPicker.value)
        showEmojiPicker.value = false
});

const onSelectEmoji = emoji => {
    currentComment.value += emoji.i;
    showEmojiPicker.value = false;
}

const resize = e => {
    e.target.style.height = 'auto';
    e.target.style.height = `${e.target.scrollHeight - 20}px`;
}


const publishComment = event => {
    if (event.shiftKey)
        return;
    axios.post('/post/comment', { post_id: props.post.id, content: currentComment.value })
        .then(response => {
            posts.addCommentToPost(response.data);
            currentComment.value = '';
        })
}

const likeUnlikePost = id => {
    like.value = !like.value;
    sendLike(id, like.value);
}
const bookmarkPost = () => {
    bookmark.value = !bookmark.value;
}

const toggleComments = () => {
    showComments.value = !showComments.value;
}

</script>

<template>
    <div class="w-[468px] h-[800px]">
        <div v-if="showComments">
            <Transition enter-from-class="opacity-0" enter-leave-class="opacity-100"
                enter-active-class="transition-opacity ease-in duration-100" leave-to-class="opacity-0"
                leave-active-class="transition duration-200 ease-in">
                <Comments class="z-[999]" v-model:showComments="showComments" v-model:bookmark="bookmark"
                    v-model:like="like" :likeUnlikePost="likeUnlikePost" :bookmarkPost="bookmarkPost" :post="post" />
            </Transition>
        </div>
        <div v-show="!showComments">
            <div class="flex justify-between items-center gap-3 mb-3">
                <div class="flex gap-3 mb-3">
                    <div>
                        <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                    </div>
                    <div>
                        <Link class="font-semibold" :href="'/profile/' + post.user.name">{{ post.user.name }}</Link>
                        <p>{{ post.location }}</p>
                    </div>
                </div>
                <unicon class="hover:cursor-pointer" name="ellipsis-h" fill="white"></unicon>
            </div>
            <div @click="toggle" ref="imgRef"
                class="h-[550px] hover:cursor-pointer flex items-center justify-center backdrop-blur-lg">
                <span v-if="isLoading">Chargement...</span>
                <img v-else
                    :class="{ 'max-h-[550px]': !isFullscreen, 'absolute max-w-screen max-h-screen bg-black': isFullscreen }"
                    :src="isFullscreen ? usePage().props.ziggy.url + post.image.replace('medium', 'big') : usePage().props.ziggy.url + post.image" />
            </div>
            <div class="mt-3 mb-3 flex flex-row justify-between">
                <div class="flex gap-3">
                    <svg-icon v-if="like" class="w-7 h-7 hover:cursor-pointer animate-heart " type="mdi" color="red"
                        @click="likeUnlikePost(post.id)" :path="mdiHeart" />
                    <div v-else class="h-7" @click="likeUnlikePost(post.id)">
                        <unicon class="w-7 h-7 hover:cursor-pointer" name="heart" fill="white" />
                    </div>
                    <div class="inline" @click="toggleComments">
                        <unicon class="w-7 h-7 hover:cursor-pointer" name="comment" fill="white"></unicon>
                    </div>
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
            <div class="leading-8">
                <div v-if="post.enable_likes">
                    {{ post.likes.length }} J'aime
                </div>
                <div v-if="post.description">
                    <Link class="font-semibold" :href="'/profile/' + post.user.name">{{ post.user.name }}</Link> {{
                        post.description }}
                </div>
                <div v-if="post.enable_comments">
                    <div class="hover:cursor-pointer" @click="toggleComments">Affichier les {{ post.comments.length }}
                        commentaires</div>
                    <div class="flex items-center justify-end gap-4 mt-1 h-[7%] relative">
                        <textarea @keydown.enter="publishComment" @input="resize($event)"
                            class="bg-transparent overflow-auto p-0 no-scrollbar resize-none h-8 max-h-16 w-[93%] border-none focus:ring-0"
                            placeholder="Ajouter un commentaire..." type="text" v-model="currentComment"></textarea>
                        <button :class="{
                            'text-[hsl(204,90%,49%)] text-xl hover:text-white': currentComment.length > 0,
                            'text-gray-600 text-xl hover:cursor-default': currentComment.length === 0
                        }" @click="publishComment" v-show="currentComment.length !== 0"
                            :disabled="currentComment.length === 0">Publier</button>
                        <svg-icon class="hover:cursor-pointer min-w-[20px] float-right" type="mdi" size="14"
                            @click="showEmojiPicker = !showEmojiPicker" :path="mdiEmoticonHappyOutline" />
                        <EmojiPicker ref="emojiRef" class="absolute bottom-[25%] right-[-285px] z-10" v-if="showEmojiPicker"
                            :native="true" @select="onSelectEmoji" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.animate-heart {
    animation: growAndShrink 0.3s;
}

@keyframes growAndShrink {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.2);
    }

    100% {
        transform: scale(1);
    }
}
</style>