<script setup>
import { router, usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark, mdiEmoticonHappyOutline } from '@mdi/js';
import { ref, watch, onMounted, watchEffect } from 'vue';
import ReelComments from './ReelComments.vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3'
import EmojiPicker from 'vue3-emoji-picker'
import { onClickOutside } from '@vueuse/core'
import { usePostStore } from './useStore/usePostStore';
import { useDebounceFn } from '@vueuse/core'


const props = defineProps({
    post: Object,
    followed: Boolean,
    stopVideo: {
        type: Boolean,
        default: false,
    },
})

const posts = usePostStore();
const emojiRef = ref(null);
const currentComment = ref('');
const showEmojiPicker = ref(false);
const like = ref(props.post.userLiked);
const bookmark = ref(false);
const showComments = ref(false);
const videoPlayer = ref();
const glowOverlay = ref();

const isPlaying = ref(false);


const following = ref(props.followed);

watch(() => props.stopVideo, (newValue, oldValue) => {
    if (newValue != oldValue)
        videoPlayer.value.pause();
});

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
    videoPlayer.value.addEventListener('loadedmetadata', handleVideoLoaded);

    function handleVideoLoaded() {
        // Listen for the 'play' event to start updating the glow color
        videoPlayer.value?.addEventListener('play', updateGlowColor);
    }

    function updateGlowColor() {
        if(!videoPlayer.value)
            return;
        // Create a canvas to extract the video frame
        const canvas = document.createElement('canvas');
        canvas.width = videoPlayer.value.videoWidth;
        canvas.height = videoPlayer.value.videoHeight;
        const context = canvas.getContext('2d');

        // Draw the current video frame onto the canvas
        context.drawImage(videoPlayer.value, 0, 0, canvas.width, canvas.height);

        // Get the pixel data from the canvas
        const pixelData = context.getImageData(0, 0, canvas.width, canvas.height).data;

        // Calculate the average color of the video frame
        let totalR = 0;
        let totalG = 0;
        let totalB = 0;
        for (let i = 0; i < pixelData.length; i += 4) {
            totalR += pixelData[i];
            totalG += pixelData[i + 1];
            totalB += pixelData[i + 2];
        }
        const avgR = Math.round(totalR / (pixelData.length / 4));
        const avgG = Math.round(totalG / (pixelData.length / 4));
        const avgB = Math.round(totalB / (pixelData.length / 4));

        // Set the glow color based on the average color of the video frame
        const glowColor = `rgba(${avgR},${avgG},${avgB},0.5)`;
        glowOverlay.value.style.boxShadow = `0 0 40px ${glowColor}`;

        // Repeat the process on the next animation frame
        requestAnimationFrame(updateGlowColor);
    }

});

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
    <div>
        <div v-if="showComments">
            <Teleport to="#reel">
                <ReelComments v-model:showComments="showComments" v-model:bookmark="bookmark" v-model:like="like"
                    :likeUnlikePost="likeUnlikePost" :bookmarkPost="bookmarkPost" :post="post" />
            </Teleport>

        </div>
        <div class="flex items-end" ref="postsRef">
            <div class="sm:h-[846px] hover:cursor-pointer flex items-center justify-center backdrop-blur-lg">
                <div class="relative sm:h-[846px] flex ml-6 mt-8 items-center justify-center">
                    <video class="max-h-[846px] w-[476px]" ref="videoPlayer" @click="togglePlayPause">
                        <source :src="post.video" />
                        Your browser does not support the video tag.
                    </video>
                    <div ref="glowOverlay" class="glow-overlay absolute inset-0 rounded-md pointer-events-none"></div>
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
                            <button v-if="!following" @click="sendFollow" class="font-semibold">
                                Suivre
                            </button>
                            <button v-else @click="sendFollow" class="font-semibold">
                                Ne plus suivre
                            </button>
                            {{ post.id }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-6 ml-10">
                <div v-if="like">
                    <div class="flex items-center">
                        <svg-icon class="w-7 h-7 hover:cursor-pointer animate-heart " type="mdi" color="red"
                            @click="likeUnlikePost(post.id)" :path="mdiHeart" />
                    </div>
                    <div class="min-w-full text-center mt-1">
                        {{ post.likes.length }}
                    </div>
                </div>
                <div v-else @click="likeUnlikePost(post.id)">
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
        </div>
    </div>
</template>

<style>
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

