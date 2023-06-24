<script setup>
import { onClickOutside } from '@vueuse/core';
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark, mdiEmoticonHappyOutline } from '@mdi/js';
import EmojiPicker from 'vue3-emoji-picker'
import { onUnmounted, onMounted } from 'vue';
const emit = defineEmits(['update:showComments']);
const props = defineProps({
    post: Object,
    like: Boolean,
    bookmark: Boolean,
    likeUnlikePost: Function,
    bookmarkPost: Function
});

onMounted(() => {
    window.Echo.channel('post-' + props.post.id).listen(
        "comments",
        (e) => {
            console.log('nani');
            console.log(e);
        }
    );
});

onUnmounted(() => {
    window.Echo.leaveChannel('post-' + props.post.id);
})


const showEmojiPicker = ref(false);
const target = ref(null);
const currentComment = ref('');
const likeComment = ref(false);
onClickOutside(target, () => emit('update:showComments', false));


const onSelectEmoji = emoji => {
    currentComment.value += emoji.i;
    showEmojiPicker.value = false;
}

const publishComment = () => {
    router.post('/post/comment', { post_id: props.post.id, content: currentComment.value },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                window.history.replaceState({}, '', '/');
                currentComment.value = '';
            }
        });
}

const resize = e => {
    e.target.style.height = 'auto';
    e.target.style.height = `${e.target.scrollHeight}px`;
}

const likeUnlikeComment = () => {
    likeComment.value = !likeComment.value;
}

const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" }
    return new Date(dateString).toLocaleDateString(undefined, options)
}

</script>

<template>
    <div class="fixed  backdrop-brightness-[0.4] flex justify-center top-0 right-0 items-center  w-screen h-screen">
        <div ref="target">
            <div class="h-[90vh] min-w-[50vw] flex bg-black ">
                <img class="max-w-[800px] ml-2" :src="usePage().props.ziggy.url + post.image">
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
                        <div class="mx-6 mb-8" v-for="(comment, index) in post.comments" :key="index">
                            <div class="flex justify-between items-center">
                                <div class="flex gap-3">
                                    <img class="rounded-full w-8 h-8" src="https://picsum.photos/seed/picsum/32/32" />
                                    <div class="font-bold h-10 ">{{ comment.user.name }}</div>
                                    <div>{{ comment.content }}</div>
                                </div>
                                <svg-icon v-if="likeComment" class="w-4 h-4 hover:cursor-pointer animate-heart " type="mdi"
                                    color="red" @click="likeUnlikeComment(comment.id)" :path="mdiHeart" />
                                <div v-else class="h-4" @click="likeUnlikeComment(comment.id)">
                                    <unicon class="w-4 h-4 hover:cursor-pointer" name="heart" fill="white" />
                                </div>
                            </div>
                            <div class="flex gap-4 text-sm text-[hsl(0,0%,60%)]">
                                <div>
                                    {{ comment.updated_created_at }}
                                </div>
                                <div>
                                    X J'aimes
                                </div>
                            </div>
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
                                <unicon class="w-7 h-7" name="comment" fill="white"></unicon>
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
                        <textarea @input="resize($event)"
                            class="bg-transparent overflow-auto resize-none	 h-8 max-h-16 w-[90%] border-none focus:ring-0"
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