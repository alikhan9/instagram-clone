<script setup>
import { onClickOutside } from "@vueuse/core";
import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiHeart, mdiWindowClose, mdiBookmark, mdiEmoticonHappyOutline } from "@mdi/js";
import EmojiPicker from "vue3-emoji-picker";
import { onUnmounted, onMounted } from "vue";
import CommentContent from "./CommentContent.vue";
import { usePostStore } from "./useStore/usePostStore";
import axios from "axios";
import { useDebounceFn } from "@vueuse/core";
import useInfiniteScroll from "./Composables/useInfiniteScroll";

const emit = defineEmits(["close"]);

const post = usePage().props.post;
const like = ref(post?.userLiked);
const bookmark = ref(post.userBookmarked);
const posts = usePostStore();
const showEmojiPicker = ref(false);
const target = ref(null);
const currentComment = ref("");
const inputRef = ref(null);
const responseTo = ref(null);
const landmarkComments = ref(null);
const referencedUsersSearch = ref(null);

const videoPlayer = ref(null);
const isPlaying = ref(false);

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
  axios.post(`/post/${id}/like`, { value });
}, 500);

const sendBookmark = useDebounceFn((id, value) => {
  axios.post(`/bookmark`, { post_id: id, value });
}, 500);

const likeUnlikePost = (id) => {
  like.value = !like.value;
  sendLike(id, like.value);
};
const bookmarkPost = () => {
  bookmark.value = !bookmark.value;
  sendBookmark(post.id, bookmark.value);
};

onMounted(() => {
  window.Echo.channel("post-" + post.id)
    .listen(".comments", (e) => {
      if (!e[1]) posts.addComment(e[0]);
      else posts.addCommentResponse(e[0]);
    })
    .listen(".likes", (e) => {
      if (e.add) {
        if (!e.isResponse) posts.addLikeToComment(e.like);
        else posts.addLikeToResponse(e.like, e.commentId);
      } else {
        if (!e.isResponse) posts.removeLikeFromComment(e.like);
        else posts.removeLikeFromResponse(e.like, e.commentId);
      }
    });
});

onUnmounted(() => {
  window.Echo.leave("post-" + post.id);
});

const onSelectEmoji = (emoji) => {
  currentComment.value += emoji.i;
  showEmojiPicker.value = false;
};

const close = () => {
  emit("close");
};

useInfiniteScroll("comments", landmarkComments, "0px 0px 150px 0px", ["comments"]);

onClickOutside(target, () => close());
const publishComment = (event) => {
  if (event.shiftKey) return;

  if (responseTo.value)
    axios.post("/post/comment/response", {
      post_comment_id: responseTo.value.hasOwnProperty("post_id")
        ? responseTo.value.id
        : responseTo.value.post_comment_id,
      content: currentComment.value,
      user_id: responseTo.value.user_id,
    });
  else
    axios.post("/post/comment", {
      post_id: post.id,
      content: currentComment.value,
    });

  currentComment.value = "";
  responseTo.value = null;
};

const resize = (e) => {
  const wordsArray = e.target.value.split(" ");
  const lastWord = wordsArray[wordsArray.length - 1];
  if (lastWord.includes("@") && lastWord.length > 1) {
    axios.get("/search/" + lastWord.replace("@", "")).then((response) => {
      referencedUsersSearch.value = response.data;
    });
  } else {
    if (referencedUsersSearch.value) referencedUsersSearch.value = null;
  }
  e.target.style.height = "auto";
  e.target.style.height = `${e.target.scrollHeight - 32}px`;
};

const addResponseComment = (data) => {
  currentComment.value = "@" + data.user.name + " ";
  responseTo.value = data;
  inputRef.value.focus();
};
</script>

<template>
  <div
    class="fixed z-50 backdrop-brightness-[0.4] inset-0 top-0 left-0 w-screen h-screen"
  >
    <div class="absolute top-10 right-10 hover:cursor-pointer" @click="close">
      <svg-icon size="28" class="text-white" type="mdi" :path="mdiWindowClose"></svg-icon>
    </div>
    <div class="flex items-center justify-center w-full h-full">
      <div ref="target" class="h-[90vh] min-w-[50vw] flex bg-black justify-center">
        <div class="max-w-[60%] flex items-center relative">
          <img
            v-if="post.image !== null"
            class="w-full h-full object-contain"
            :src="usePage().props.ziggy.url + post.image.replace('medium', 'big')"
          />
          <div v-else>
            <video
              class="object-fill w-full h-full mx-auto"
              width="100%"
              ref="videoPlayer"
              @click="togglePlayPause"
            >
              <source :src="post.video" />
              Your browser does not support the video tag.
            </video>
            <div class="play-button" v-if="!isPlaying" @click="togglePlayPause"></div>
          </div>
        </div>

        <div class="w-[500px] text-white border-l border-[#262626]">
          <div class="flex justify-between border-b items-center py-5 border-[#262626]">
            <div class="flex items-center w-full gap-3 px-6">
              <div class="w-9 h-9 rounded-full overflow-hidden">
                <img class="w-full h-full" :src="post.user.avatar" />
              </div>
              <div>{{ post.user.name }}</div>
            </div>
            <div class="px-6">
              <unicon
                class="hover:cursor-pointer"
                name="ellipsis-h"
                fill="white"
              ></unicon>
            </div>
          </div>
          <div class="border-b py-5 h-[73%] border-[#262626] overflow-auto no-scrollbar">
            <div
              class="mx-6 mb-8"
              v-for="(comment, index) in posts.comments"
              :key="index"
            >
              <CommentContent
                @sendResponseComment="addResponseComment"
                :postId="post.id"
                :comment="comment"
              />
            </div>
            <div ref="landmarkComments"></div>
          </div>
          <div class="border-b border-[#262626] py-5">
            <div class="flex flex-row justify-between px-6">
              <div class="flex gap-3">
                <svg-icon
                  v-if="like"
                  class="w-7 h-7 hover:cursor-pointer animate-heart"
                  type="mdi"
                  color="red"
                  @click="likeUnlikePost(post.id)"
                  :path="mdiHeart"
                />
                <div v-else class="h-7" @click="likeUnlikePost(post.id)">
                  <unicon
                    class="w-7 h-7 hover:cursor-pointer"
                    name="heart"
                    fill="white"
                  />
                </div>
                <span @click="close">
                  <unicon
                    class="w-7 h-7 hover:cursor-pointer"
                    name="comment"
                    fill="white"
                  ></unicon>
                </span>
                <unicon class="w-7 h-7" name="telegram-alt" fill="white"></unicon>
              </div>
              <div>
                <div class="h-7" v-if="!bookmark" @click="bookmarkPost">
                  <unicon
                    class="w-7 h-7 hover:cursor-pointer"
                    name="bookmark"
                    fill="white"
                  ></unicon>
                </div>
                <svg-icon
                  v-else="like"
                  class="w-7 h-7 hover:cursor-pointer"
                  type="mdi"
                  color="white"
                  @click="bookmarkPost"
                  :path="mdiBookmark"
                />
              </div>
            </div>
            <div class="px-6 mt-5">
              <p class="text-xl">{{ post.likes }} J'aime</p>
              <p class="text-sm text-[hsl(0,0%,60%)]">{{ post.created_at }}</p>
            </div>
          </div>
          <div class="flex relative items items-center gap-4 h-[7%] px-6 py-5">
            <div
              v-if="referencedUsersSearch"
              class="absolute top-0 translate-y-[-200px] left-0 h-[200px] w-[309px] bg-black overflow-auto"
            >
              <div
                v-for="user in referencedUsersSearch"
                class="h-[50px] flex gap-5 px-4 items-center hover:cursor-pointer border-b border-[#262626]"
              >
                <div class="h-8 w-8 rounded-full overflow-hidden">
                  <img class="h-full w-full" :src="user.avatar" alt="avatar" />
                </div>
                <div>
                  <div>{{ user.username }}</div>
                  <div>{{ user.name }}</div>
                </div>
              </div>
            </div>
            <svg-icon
              class="hover:cursor-pointer"
              type="mdi"
              size="32"
              @click="showEmojiPicker = !showEmojiPicker"
              :path="mdiEmoticonHappyOutline"
            />
            <EmojiPicker
              class="absolute bottom-[10%] z-10"
              v-if="showEmojiPicker"
              :native="true"
              @select="onSelectEmoji"
            />
            <textarea
              @keydown.enter="publishComment"
              @input="resize($event)"
              ref="inputRef"
              class="bg-transparent float-left no-scrollbar resize-none h-8 max-h-16 w-[93%] border-none focus:ring-0"
              placeholder="Ajouter un commentaire..."
              type="text"
              v-model="currentComment"
            ></textarea>
            <button
              :class="{
                'text-[hsl(204,90%,49%)] text-xl hover:text-white':
                  currentComment.length > 0,
                'text-gray-600 text-xl hover:cursor-default': currentComment.length === 0,
              }"
              @click="publishComment"
              :disabled="currentComment.length === 0"
            >
              Publier
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
