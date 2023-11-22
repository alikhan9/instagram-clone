<script setup>
import { router, usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark } from '@mdi/js';
import { ref, watch, onMounted } from 'vue';
import MobileReelComments from './MobileReelComments.vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3'
import { useDebounceFn } from '@vueuse/core'


const props = defineProps({
    post: Object,
    followed: Boolean,
    stopVideo: {
        type: Boolean,
        default: false,
    },
})

const like = ref(props.post.userLiked);
const bookmark = ref(props.post.userBookmarked);
const showComments = ref(false);
const videoPlayer = ref();

const isPlaying = ref(false);

const following = ref(props.followed);

watch(() => props.stopVideo, (newValue, oldValue) => {
    if (newValue != oldValue)
        videoPlayer.value.pause();
});

watch(() => usePage().props['showComments'], (newValue, oldValue) => {
    toggleComments();
})

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
    router.get('/reels', { pid: props.post.id, sC: true }, {
        preserveScroll: true,
        preserveState: true,
        only: ['post', 'comments', 'showComments'],
        onFinish: () => {
            if (!showComments.value)
                showComments.value = true;
        }
    });
}

const toggleComments = () => {
    showComments.value = !showComments.value;
    window.history.replaceState({}, '', '');
}

const sendLike = useDebounceFn((id, value) => {
    axios.post(`/post/${id}/like`, { value })
}, 500);

const sendBookmark = useDebounceFn((id, value) => {
    axios.post(`/bookmark`, { post_id: id, value })
}, 500);

onMounted(() => {
    if (usePage().props['showComments'])
        toggleComments();
});

watch(() => props.post, newValue => {
    like.value = newValue.userLiked;
})

const likeUnlikePost = id => {
    like.value = !like.value;
    sendLike(id, like.value);
}
const bookmarkPost = () => {
    bookmark.value = !bookmark.value;
    sendBookmark(props.post.id, bookmark.value);
}

const sendFollow = () => {
    axios.post('/follow/' + props.post.user.id)
        .then(res => {
            following.value = res.data;
        });
}

</script>

<template>
    <div>
        <div>
            <Transition name="slide-vertical" appear>
                <MobileReelComments v-if="showComments" @toggleComments="toggleComments" :post="post" />
            </Transition>
        </div>
        <div :class="{ 'flex h-[96vh] sm:h-screen relative overflow-hidden items-start': true, 'transition-all brightness-75': showComments }"
            ref="postsRef">
            <div class="hover:cursor-pointer h-full backdrop-blur-lg">
                <div class="relative h-full flex items-center w-full">
                    <video class="w-screen  h-full" ref="videoPlayer" @click="togglePlayPause">
                        <source :src="post.video" />
                        Your browser does not support the video tag.
                    </video>
                    <div class="play-button" v-if="!isPlaying" @click="togglePlayPause"></div>
                </div>
            </div>
            <div class="flex absolute bottom-0 justify-between items-center gap-3 mb-3">
                <div class="flex px-4 items-center text-white gap-2 mb-3">
                    <div class="pr-2">
                        <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                    </div>
                    <div>
                        <Link class="font-semibold" :href="'/profile/' + post.user.name">{{ post.user.name }}</Link>
                    </div>
                    <div class="text-xl">
                        •
                    </div>
                    <button v-if="!following" @click="sendFollow" class="font-semibold">
                        Suivre
                    </button>
                    <button v-else @click="sendFollow" class="font-semibold">
                        Ne plus suivre
                    </button>
                </div>
            </div>
            <div class="flex flex-col absolute text-white bottom-4 z-[99]  right-[20px] gap-6 ml-10">
                <div v-if="like">
                    <div class="flex items-center">
                        <svg-icon class="w-7 h-7 hover:cursor-pointer animate-heart  " type="mdi" color="red"
                            @click="likeUnlikePost(post.id)" :path="mdiHeart" />
                    </div>
                    <div class="min-w-full text-white text-center mt-1">
                        {{ post.userLiked ? post.numberOfLikes : post.numberOfLikes + 1 }}
                    </div>
                </div>
                <div v-else @click="likeUnlikePost(post.id)">
                    <unicon class="w-7 h-7 hover:cursor-pointer" name="heart" fill="white" />
                    <div class="min-w-full text-center mt-1">
                        {{ post.userLiked ? post.numberOfLikes - 1 : post.numberOfLikes }}
                    </div>
                </div>
                <div class="inline text-center" @click="getComments">
                    <unicon class="w-7 h-7 hover:cursor-pointer" name="comment" fill="white"></unicon>
                    <div class="min-w-full">
                        {{ post.numberOfComments }}
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
        </div>
    </div>
</template>

<style>
.slide-vertical-enter-active,
.slide-vertical-leave-active {
    transition: all 0.3s ease-in-out;
}

.slide-vertical-enter-from,
.slide-vertical-leave-to {
    transform: translateY(80vh);
}



.animate-heart {
    animation: growAndShrink 0.3s;
}

.overflow-visible-important {
    overflow: visible !important;
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
    overflow: hidden;
}

.glow-overlay {
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    border-radius: 10px;
    pointer-events: none;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
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

