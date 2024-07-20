<script setup>
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiHeart } from "@mdi/js";
import { ref, onMounted } from "vue";
import { useDebounceFn } from "@vueuse/core";
import axios from "axios";
import { Link } from "@inertiajs/vue3";

const emit = defineEmits(["sendResponseComment"]);
const props = defineProps({
  comment: Object,
  postId: Number,
  commentId: Number,
});

const likeComment = ref(false);
const oldComment = ref(false);
const sendLike = useDebounceFn(() => {
  if (oldComment.value == likeComment.value) return;
  if (props.comment.hasOwnProperty("post_id"))
    axios.post(`/post/comment/${props.comment.id}/like`, {
      comment_like_id: null,
      postId: props.postId,
    });
  else
    axios.post(`/post/comment/response/${props.comment.id}/like`, {
      postId: props.postId,
      commentId: props.commentId,
    });
  oldComment.value = !oldComment.value;
}, 500);

onMounted(() => {
  likeComment.value = props.comment.user_liked.length > 0;
  oldComment.value = likeComment.value;
});

const likeUnlikeComment = () => {
  likeComment.value = !likeComment.value;
  sendLike();
};
const sendName = () => {
  emit("sendResponseComment", props.comment);
};
</script>

<template>
  <div class="flex gap-5 h-full mb-5">
    <div>
      <div class="w-9 h-9 rounded-full overflow-hidden">
        <img class="h-full w-full" :src="comment.user.avatar" />
      </div>
    </div>
    <div class="w-full">
      <div class="flex flex-col gap-1 mb-2 w-full text-white">
        <Link :href="'/profile/' + comment.user.username" class="shrink-0 font-bold flex-wrap">
          {{ comment.user.username }}
        </Link>
        <div class="flex flex-fill break-words h-full flex-wrap gap-1 w-full">
          <div v-for="(text, index) in comment.content.split(' ')" :key="index">
            <Link v-if="text[0] == '@'" :href="'/profile/' + text.substring(1)">{{
              text
            }}</Link>
            <span v-else>{{ text }}</span>
          </div>
        </div>
      </div>
      <div class="flex gap-4 text-sm text-[hsl(0,0%,60%)]">
        <div>
          {{ comment.created_at }}
        </div>
        <div v-if="comment.likes.length > 0">{{ comment?.likes?.length }} J'aimes</div>
        <div @click="sendName" class="hover:cursor-pointer">Répondre</div>
      </div>
      <slot></slot>
    </div>
    <svg-icon
      v-if="likeComment"
      class="w-4 h-4 hover:cursor-pointer self-start animate-heart"
      type="mdi"
      color="red"
      @click="likeUnlikeComment(comment.id)"
      :path="mdiHeart"
    />
    <div v-else class="h-4 self-start" @click="likeUnlikeComment(comment.id)">
      <unicon class="w-4 h-4 hover:cursor-pointer" name="heart" fill="white" />
    </div>
  </div>
</template>
