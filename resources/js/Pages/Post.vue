<script setup>
import { router, usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark } from '@mdi/js';
import { useImage, useFullscreen } from '@vueuse/core';
import { ref, watch, onMounted, watchEffect } from 'vue';

const props = defineProps({
    post: Object,
})



const like = ref(false);
const bookmark = ref(false);
const initialUrl = usePage().path;
const { isLoading } = useImage({ src: 'http://127.0.0.1:8000' + props.post.image })
const imgRef = ref(null);

const { isFullscreen, enter, exit, toggle } = useFullscreen(imgRef)

watchEffect(() => {
    window.history.replaceState({}, '', initialUrl);
});

onMounted(() => {
    if (props.post.likes.filter(l => l.id == usePage().props.auth.user_id).length > 0)
        like.value = true;
});

watch(() => props.post, newValue => {
    if (newValue.likes.filter(l => l.user_id == usePage().props.auth.user_id).length > 0)
        like.value = true;
})


const likeUnlikePost = id => {
    router.post(`/post/${id}/like`, {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            window.history.replaceState({}, '', '/');
            like.value = !like.value;
        }
    });
}
const bookmarkPost = () => {
    bookmark.value = !bookmark.value;
}


</script>

<template>
    <div class="w-[468px] h-[800px]">
        <div class="flex justify-between items-center gap-3 mb-3">
            <div class="flex gap-3 mb-3">
                <div>
                    <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                </div>
                <div>
                    <p>{{ post.user.name }}</p>
                    <p>{{ post.location }}</p>
                    <p>{{ post.description }}</p>
                </div>
            </div>
            <unicon class="hover:cursor-pointer" name="ellipsis-h" fill="white"></unicon>
        </div>
        <div @click="toggle" v-auto-animate ref="imgRef"
            class="h-[550px] hover:cursor-pointer flex items-center justify-center backdrop-blur-lg">
            <span v-if="isLoading">Chargement...</span>
            <img v-else
                :class="{ 'max-h-[550px]': !isFullscreen, 'absolute max-w-screen max-h-screen bg-black': isFullscreen }"
                v-lazy="'http://127.0.0.1:8000' + post.image" />
        </div>
        <div class="mt-3 mb-3 flex flex-row justify-between">
            <div class="flex gap-3">
                <svg-icon v-if="like" class="w-7 h-7 hover:cursor-pointer animate-heart " type="mdi" color="red"
                    @click="likeUnlikePost(post.id)" :path="mdiHeart" />
                <div v-else class="h-7" @click="likeUnlikePost(post.id)">
                    <unicon class="w-7 h-7 hover:cursor-pointer" name="heart" fill="white" />
                </div>
                <unicon class="w-7 h-7" name="comment" fill="white"></unicon>
                <unicon class="w-7 h-7" name="telegram-alt" fill="white"></unicon>
            </div>
            <div>
                <div class="h-7" v-if="!bookmark" @click="bookmarkPost">
                    <unicon class="w-7 h-7 hover:cursor-pointer" name="bookmark" fill="white"></unicon>
                </div>
                <svg-icon v-else="like" class="w-7 h-7 hover:cursor-pointer" type="mdi" color="white" @click="bookmarkPost"
                    :path="mdiBookmark" />
            </div>
        </div>
        <div class="leading-8">
            <div v-if="post.enable_likes">
                {{ post.likes.length }} J'aime
            </div>
            <div>
                (lien vers profil)muftimenkofficial Morning Qur'aan Recitation
            </div>
            <div v-if="post.enable_comments">
                <p>Affichier les x commentaires</p>
                <p>2 commentaire afficher ici</p>
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