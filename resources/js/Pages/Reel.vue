<script setup>
import { router, usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark, mdiEmoticonHappyOutline } from '@mdi/js';
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
    followed: Boolean
})

const posts = usePostStore();
const emojiRef = ref(null);
const currentComment = ref('');
const showEmojiPicker = ref(false);
const like = ref();
const bookmark = ref(false);
const showComments = ref(false);

const videoPlayer = ref(null);
const isPlaying = ref(false);

const following = ref(props.followed);

const togglePlayPause = () => {
    if (videoPlayer.value.paused) {
        videoPlayer.value.play();
        isPlaying.value = true;
    } else {
        videoPlayer.value.pause();
        isPlaying.value = false;
    }
};

const sendLike = useDebounceFn((id, value) => {
    axios.post(`/post/${id}/like`, { value })
}, 500);

const sendBookmark = useDebounceFn((id, value) => {
    axios.post(`/bookmark`, { post_id: id, value })
}, 500);

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
    sendBookmark(props.post.id, bookmark.value);
}

const toggleComments = () => {
    showComments.value = !showComments.value;
}

const sendFollow = () => {
    axios.post('/follow/' + props.post.user.id)
        .then(res => {
            following.value = res.data;
        });
}

</script>

<template>
    <div class="w-[530px]">
        <div v-if="showComments">
            <Comments v-model:showComments="showComments" v-model:bookmark="bookmark" v-model:like="like"
                :likeUnlikePost="likeUnlikePost" :bookmarkPost="bookmarkPost" :post="post" />
        </div>
        <div class="flex items-end" v-show="!showComments" ref="postsRef">
            <div class="h-[846px] hover:cursor-pointer flex items-center justify-center backdrop-blur-lg">
                <div class="relative h-[846px] flex items-center justify-center">
                    <video class="max-h-[846px] w-[476px]" ref="videoPlayer" @click="togglePlayPause">
                        <source :src="post.video" />
                        Your browser does not support the video tag.
                    </video>
                    <div class="play-button" v-if="!isPlaying" @click="togglePlayPause"></div>
                    <div class="flex min-w-full absolute bottom-0 justify-between items-center gap-3 mb-3">
                        <div class="flex px-4 items-center gap-2 mb-3">
                            <div class="pr-2">
                                <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                            </div>
                            <div>
                                <Link class="font-semibold" :href="'/profile/' + post.user.name">{{ post.user.name }}</Link>
                                <!-- <p>{{ post.location }}</p> -->
                            </div>
                            <div class="text-xl">
                                •
                            </div>
                            <!-- To do / Suscribe -->
                            <button v-if="!following" @click="sendFollow" class="font-semibold">
                                Suivre
                            </button>
                            <button v-else @click="sendFollow" class="font-semibold">
                                Ne plus suivre
                            </button>
                        </div>
                        <!-- <unicon class="hover:cursor-pointer" name="ellipsis-h" fill="white"></unicon> -->
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-6 ml-10">
                <div  v-if="like">
                    <div class="flex items-center">
                        <svg-icon class="w-7 h-7 hover:cursor-pointer animate-heart " type="mdi" color="red"
                            @click="likeUnlikePost(post.id)" :path="mdiHeart" />
                    </div>
                    <div class="min-w-full text-center mt-1">
                        {{ post.likes.length }}
                    </div>
                </div>
                <div v-else  @click="likeUnlikePost(post.id)">
                    <unicon class="w-7 h-7 hover:cursor-pointer" name="heart" fill="white" />
                    <div class="min-w-full text-center mt-1">
                        {{ post.likes.length }}
                    </div>
                </div>
                <div class="inline text-center" @click="toggleComments">
                    <unicon class="w-7 h-7 hover:cursor-pointer" name="comment" fill="white"></unicon>
                    <div class="min-w-full">
                        {{ post.comments.length }}
                    </div>
                </div>
                <unicon class="w-7 h-7" name="telegram-alt" fill="white"></unicon>
                <div class="h-7" v-if="!bookmark" @click="bookmarkPost">
                    <unicon class="w-7 h-7 hover:cursor-pointer" name="bookmark" fill="white"></unicon>
                </div>
                <svg-icon v-else="like" class="w-7 h-7 hover:cursor-pointer" type="mdi" color="white" @click="bookmarkPost"
                    :path="mdiBookmark" />
                <div>
                    <unicon class="hover:cursor-pointer" name="ellipsis-h" fill="white"></unicon>
                </div>
            </div>

            <!-- <div class="leading-8">
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
            </div> -->
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
