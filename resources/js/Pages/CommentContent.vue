<script setup>
import { ref, onMounted } from 'vue';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart } from '@mdi/js';
import axios from 'axios';
import { useDebounceFn } from '@vueuse/core'

const emit = defineEmits(['sendResponseComment']);
const props = defineProps({
    comment: Object
})

const likeComment = ref(false);
const showResponses = ref(false);

const sendLike = useDebounceFn(() => {
    axios.post(`/post/comment/${props.comment.id}/like`, { comment_like_id: null });
}, 500);

onMounted(() => {
    likeComment.value = props.comment.user_liked.length > 0;
})

const likeUnlikeComment = () => {
    likeComment.value = !likeComment.value;
    sendLike();
}

const sendName = () => {
    emit('sendResponseComment', { ...props.comment.user, comment_id: props.comment.id });
}

</script>

<template>
    <div>
        <div class="flex justify-between items-center">
            <div class="flex gap-3 w-[95%]">
                <img class="rounded-full w-8 h-8" src="https://picsum.photos/seed/picsum/32/32" />
                <div class="font-bold h-10 ">{{ comment.user.name }}</div>
                <div>{{ comment.content }}</div>
            </div>
            <svg-icon v-if="likeComment" class="w-4 h-4 hover:cursor-pointer self-start animate-heart " type="mdi"
                color="red" @click="likeUnlikeComment(comment.id)" :path="mdiHeart" />
            <div v-else class="h-4 self-start" @click="likeUnlikeComment(comment.id)">
                <unicon class="w-4 h-4 hover:cursor-pointer" name="heart" fill="white" />
            </div>
        </div>
        <div class="flex gap-4 mt-1 text-sm text-[hsl(0,0%,60%)]">
            <div>
                {{ comment.updated_created_at.slice(6, comment.updated_created_at.length) }}
            </div>
            <div>
                {{ comment.likes.length }} J'aimes
            </div>
            <div @click="sendName" class="hover:cursor-pointer">
                Répondre
            </div>
        </div>
        <div class="text-sm text-[hsl(0,0%,60%)] mt-5" v-if="comment.responses.length > 0">
            <div v-if="!showResponses" @click="showResponses = !showResponses" class="hover:cursor-pointer">
                <span class="tracking-tighter mr-5">——</span> Afficher les réponses ({{
                    comment.responses.length }})
            </div>
            <div v-else>
                <div class="hover:cursor-pointer" @click="showResponses = !showResponses">
                    <span class="tracking-tighter mr-5">——</span> Masquer les réponses
                </div>
                <div class="flex gap-4 mt-1 text-sm text-[hsl(0,0%,60%)]">
                    <div v-for="(response, index) in comment.responses" :key="index">
                        <div>
                            {{ response.updated_created_at.slice(6, comment.updated_created_at.length) }}
                        </div>
                        <div>
                            XX J'aimes
                        </div>
                        <div @response="sendName" class="hover:cursor-pointer">
                            Répondre
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>