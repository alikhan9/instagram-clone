<script setup>
import { ref, watch, onMounted } from "vue";
import Post from "./Post.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import useInfiniteScroll from "./Composables/useInfiniteScroll";
import { usePostStore } from "./useStore/usePostStore";
import MobileComments from "./MobileComments.vue";
import Comments from "./Comments.vue";
import { useWindowSize } from "@vueuse/core";

const props = defineProps({
  mostFollowedUsers: Object,
  followed: {
    type: Boolean,
    default: false,
  },
  comments: Object,
});

const showComments = ref(null);

const landmark = ref(null);
const posts = usePostStore();
const { width, height } = useWindowSize();

const user = usePage().props.auth.user;

watch(
  () => usePage().props.post,
  (newValue, oldValue) => {
    toggleComments();
  }
);

onMounted(() => {
  if (usePage().props.post) toggleComments();
});

useInfiniteScroll("posts", landmark, "0px 0px 150px 0px", ["posts"]);

const sendFollow = (id) => {
  axios.post("/follow/" + id).then((res) => {
    props.mostFollowedUsers.filter((u) => u.id === id)[0].followed = res.data;
  });
};

const toggleComments = () => {
  showComments.value = !showComments.value;
};
</script>

<template>
  <Head title="Home" />
  <div class="relative w-full h-full" @scroll="updateScrollPosition">
    <div class="z-50 h-full w-full" v-if="showComments">
      <Comments v-if="width > 1023" @close="toggleComments" />
      <MobileComments v-else @toggleComments="toggleComments" />
    </div>
    <div id="home" :class="{ 'flex z-0 justify-center w-full': true }">
      <div class="w-full">
        <div
          class="text-white flex justify-center w-full sm:pt-4 gap-16 xl:pt-24 sm:min-h-screen"
        >
          <div
            class="col-start-3 shrink w-full sm:max-w-[500px] 2xl:max-w-[600px] flex items-center flex-col col-span-4"
          >
            <div
              class="sm:flex gap-4 hidden border-b border-[#262626] w-full font-bold text-lg pb-3"
            >
              <Link
                href="/"
                :class="{ 'text-white ': !followed, 'text-[#868686]': followed }"
              >
                Pour vous
              </Link>
              <Link
                href="/"
                :data="{ followed: true }"
                :class="{ 'text-white': followed, 'text-[#868686]': !followed }"
              >
                Suivi(e)
              </Link>
            </div>
            <div
              v-for="(post, index) in posts.getValue()"
              :key="index"
              class="sm:px-4 flex flex-col sm:justify-center mt-6"
            >
              <Post
                class="pb-6 border-b sm:w-full w-screen border-[#262626]"
                :showComments="showComments"
                :post="post"
              />
            </div>
            <div class="h-[10px] w-full" ref="landmark"></div>
          </div>
          <div class="w-[319px] hidden xl:block">
            <div class="flex justify-between items-center gap-3 mb-2">
              <div class="flex gap-3 mb-4">
                <div class="h-11 w-11 overflow-hidden rounded-full">
                  <img class="w-full h-full" :src="user.avatar" />
                </div>
                <div>
                  <Link class="font-semibold" :href="'/profile/' + user.username"
                    >{{ user.username }}
                  </Link>
                  <div class="font-semibold text-[hsl(0,0%,60%)]">{{ user.name }}</div>
                </div>
              </div>
              <div class="text-blue-400">Basculer</div>
            </div>
            <div class="flex justify-between mb-3">
              <div class="text-[hsl(0,0%,80%)]">Suggestion pour vous</div>
              <button class="hover:text-[hsl(0,0%,60%)]">Voir tout</button>
            </div>
            <div
              v-for="(u, index) in mostFollowedUsers"
              :key="index"
              class="flex justify-between items-center mb-1"
            >
              <div class="flex gap-3 mb-4">
                <Link
                  class="w-11 h-11 rounded-full overflow-hidden"
                  :href="'/profile/' + u.username"
                >
                  <img class="w-full h-full" :src="u.avatar" />
                </Link>
                <div>
                  <Link class="font-semibold" :href="'/profile/' + u.username">{{
                    u.username
                  }}</Link>
                  <div class="font-semibold text-[hsl(0,0%,60%)]">{{ u.name }}</div>
                </div>
              </div>
              <button v-if="!u.followed" @click="sendFollow(u.id)" class="text-blue-400">
                Suivre
              </button>
              <button v-else @click="sendFollow(u.id)" class="text-red-400">
                Ne plus suivre
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.no-scroll {
  overflow: hidden !important;
}
</style>
