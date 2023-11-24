<script setup>
import { router, usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark, mdiEmoticonHappyOutline } from '@mdi/js';
import { useImage } from '@vueuse/core';
import { ref, watch } from 'vue';
import Comments from './Comments.vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3'
import EmojiPicker from 'vue3-emoji-picker'
import { onClickOutside } from '@vueuse/core'
import { usePostStore } from './useStore/usePostStore';
import { useDebounceFn } from '@vueuse/core'

const props = defineProps({
    post: Object,
    showComments: Boolean,
})


const emit = defineEmits(['update:showFullPost']);

const posts = usePostStore();
const emojiRef = ref(null);
const currentComment = ref('');
const showEmojiPicker = ref(false);
const like = ref(props.post.userLiked);
const bookmark = ref(false);
const { isLoading } = useImage({ src: usePage().props.ziggy.url + props.post.image })
const showComments = ref(false);

const videoPlayer = ref(null);
const isPlaying = ref(false);

const togglePlayPause = () => {
    if (videoPlayer.value.paused) {
        videoPlayer.value.play();
        isPlaying.value = true;
    } else {
        videoPlayer.value.pause();
        isPlaying.value = false;
    }
};


const getComments = () => {
    router.get('/', { pid: props.post.id }, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        only: ['post', 'comments']
    });
}


const sendLike = useDebounceFn((id, value) => {
    axios.post(`/post/${id}/like`, { value })
}, 500);

const sendBookmark = useDebounceFn((id, value) => {
    axios.post(`/bookmark`, { post_id: id, value })
}, 500);

watch(() => props.post, newValue => {
    like.value = newValue.userLiked;
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
    sendBookmark(props.post.id, bookmark.value);
}


</script>

<template>
    <div v-if="isLoading" class="w-full sm:w-[570px] 2xl:w-[470px] h-[470px] mb-16">
        <div class="flex gap-4 mb-4">
            <div class="rounded-full bg-[hsl(0,0%,30%)] animate-pulse-bg w-10 h-10"></div>
            <div>
                <div class="w-[200px] animate-pulse-bg h-4 rounded-md bg-[hsl(0,0%,30%)]"></div>
                <div class="w-[130px] animate-pulse-bg mt-2 h-4 rounded-md bg-[hsl(0,0%,30%)]"></div>
            </div>
        </div>
        <div class="min-w-full animate-pulse-bg bg-[hsl(0,0%,30%)] rounded min-h-full">
        </div>
    </div>

    <div v-else class="xl:w-[470px]  sm:pr-0 w-full grow">
        <div>
            <div class="flex justify-between items-center gap-3 mb-3 px-3 sm:px-0">
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
            <div
                class="xl:h-[585px] xl:w-[470px] hover:cursor-pointer w-full border border-[#262626] rounded flex items-center justify-center backdrop-blur-lg">
                <div>
                    <img v-if="post.image !== null" class="" :src="usePage().props.ziggy.url + post.image" />
                    <div class="max-h-[550px] overflow-hidden w-full sm:w-[550px] xl:max-w-[470px]  border border-[#262626] rounded flex items-center justify-center"
                        v-else>
                        <video class="object-fill" ref="videoPlayer" @click="togglePlayPause">
                            <source :src="post.video" />
                            Your browser does not support the video tag.
                        </video>
                        <div class="play-button" v-if="!isPlaying" @click="togglePlayPause"></div>
                    </div>
                </div>
            </div>
            <div class="mt-3 mb-3 px-3 sm:px-0 flex flex-row justify-between">
                <div class="flex gap-3">
                    <svg-icon v-if="like" class="w-7 h-7 hover:cursor-pointer animate-heart " type="mdi" color="red"
                        @click="likeUnlikePost(post.id)" :path="mdiHeart" />
                    <div v-else class="h-7" @click="likeUnlikePost(post.id)">
                        <unicon class="w-7 h-7 hover:cursor-pointer" name="heart" fill="white" />
                    </div>
                    <div class="inline" @click="getComments">
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
            <div class="leading-8 mb-3 px-3 sm:px-0">
                <div v-if="post.enable_likes">
                    {{ post.numberOfLikes }} J'aime
                </div>
                <div v-if="post.description">
                    <Link class="font-semibold" :href="'/profile/' + post.user.name">{{ post.user.name }}</Link> {{
                        post.description }}
                </div>
                <div v-if="post.enable_comments">
                    <div class="hover:cursor-pointer text-[hsl(0,0%,45%)]" @click="getComments">Affichier les {{
                        post.numberOfComments }}
                        commentaires</div>
                    <div class=" items-center hidden sm:flex justify-end gap-4 mt-1 h-[7%] relative">
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
                    {{ post.id }}

                </div>
            </div>
        </div>
    </div>
</template>

<style>
.animate-heart {
    animation: growAndShrink 0.3s;
}

.animate-pulse-bg {
    animation: pulse-bg 1s infinite alternate ease-in-out;
}


@keyframes pulse-bg {
    from {
        background-color: hsl(0, 0%, 30%);
    }

    to {
        background-color: hsl(0, 0%, 22%);
    }
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

.video-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%;
}

.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background-color: rgba(0, 0, 0, .7);
    border-radius: 50%;
    cursor: pointer;
}


.play-button::before {
    content: "";
    position: absolute;
    top: calc(50% - .5em);
    left: calc(50% - .5em);
    width: .5em;
    height: .5em;
    border-style: solid;
    border-width: .5em .5em .5em 0;
}
</style>

