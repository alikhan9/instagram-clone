<script setup>
import { router, usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark } from '@mdi/js';
import axios from 'axios';
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
    post: Object,

})

const like = ref(false);
const bookmark = ref(false);
const initialUrl = usePage().url;


onMounted(() => {
    if (props.post.likes.filter(l => l.active && l.user_id == usePage().props.auth.user.id).length > 0)
        like.value = true;
});

watch(() => props.post, newValue => {
    console.log('nani');
    if (newValue.likes.filter(l => l.user_id == usePage().props.auth.user.id).length > 0)
        like.value = true;
    window.history.replaceState({}, '', initialUrl);

})

const likeUnlikePost = id => {
    router.post('/likePost', { post_id: id }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            window.history.replaceState({}, '', initialUrl);
        }
    });
    // .then(response => {
    //     console.log(usePage());
    //     if (response.data.active)
    //         props.post.likes.push(response.data);
    //     else
    //         props.post.likes.filter(l => l.id !== response.data.id);
    // })
    // .catch(error => {
    //     console.log(error);
    // });
    like.value = !like.value;
}
const bookmarkPost = () => {
    bookmark.value = !bookmark.value;
}

</script>

<template>
    <div class="min-w-[468px]">
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
        <img class="max-w-[468px] max-h-[650px]" :src="'http://127.0.0.1:8000' + post.image" />
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