<script setup>
import { ref, onMounted } from 'vue';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart } from '@mdi/js';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    comment: Object
})

const likeComment = ref(false);

onMounted(() => {
    likeComment.value = props.comment.user_liked.length > 0;
})

const likeUnlikeComment = () => {
    axios.post(`/post/comment/${props.comment.id}/like`, { comment_like_id: null });
    likeComment.value = !likeComment.value;
}
</script>

<template>
    <div>
        <div class="flex justify-between items-center">
            <div class="flex gap-3">
                <img class="rounded-full w-8 h-8" src="https://picsum.photos/seed/picsum/32/32" />
                <div class="font-bold h-10 ">{{ comment.user.name }}</div>
                <div>{{ comment.content }}</div>
            </div>
            <svg-icon v-if="likeComment" class="w-4 h-4 hover:cursor-pointer animate-heart " type="mdi" color="red"
                @click="likeUnlikeComment(comment.id)" :path="mdiHeart" />
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
</template>