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
	commentId: Number
});



const likeComment = ref(false);
const oldComment = ref(false);
const sendLike = useDebounceFn(() => {
	if (oldComment.value == likeComment.value)
		return;
	if (props.comment.hasOwnProperty('post_id'))
		axios.post(`/post/comment/${props.comment.id}/like`, {
			comment_like_id: null,
			postId: props.postId
		});
	else
		axios.post(`/post/comment/response/${props.comment.id}/like`, { postId: props.postId, commentId: props.commentId });
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
	<div class="flex gap-5 mb-5">
		<img class="rounded-full w-9 h-9" src="https://picsum.photos/seed/picsum/32/32" />
		<div class="w-[95%]">
			<div class="flex gap-3 mb-2 text-white">
				<div class="font-bold">
					{{ comment.user.name }}
				</div>
				<div class="flex gap-1">
					<div v-for="(text, index) in  comment.content.split(' ')" :key="index">
						<Link v-if="text[0] == '@'" :href="'/profile/' + text.substring(1)">{{ text }}</Link>
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
		<svg-icon v-if="likeComment" class="w-4 h-4 hover:cursor-pointer self-start animate-heart" type="mdi" color="red"
			@click="likeUnlikeComment(comment.id)" :path="mdiHeart" />
		<div v-else class="h-4 self-start" @click="likeUnlikeComment(comment.id)">
			<unicon class="w-4 h-4 hover:cursor-pointer" name="heart" fill="white" />
		</div>
	</div>
</template>
