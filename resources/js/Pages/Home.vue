<script setup>
import { ref } from 'vue';
import Post from './Post.vue'
import { Head, Link, usePage } from "@inertiajs/vue3";
import useInfiniteScroll from './Composables/useInfiniteScroll';
import { usePostStore } from './useStore/usePostStore';

const landmark = ref(null);
const posts = usePostStore();
const open = ref(true);

const props = defineProps({
    mostFollowedUsers: Object,
    followed: {
        type: Boolean,
        default: false
    }
});

const user = usePage().props.auth.user;

useInfiniteScroll('posts', landmark, '0px 0px 150px 0px');

const sendFollow = (id) => {
    axios.post('/follow/' + id)
        .then(res => {
            props.mostFollowedUsers.filter(u => u.id == id)[0].followed = res.data;
        });
}

</script>

<template>
    <Head title="Home" />
    <div>
        <div class="text-white xl:grid grid-cols-10 gap-28 xl:pt-24 sm:min-h-screen w-full">
            <div class="col-start-3 flex items-center flex-col col-span-4 ">
                <div class="sm:flex gap-4 hidden border-b border-[#262626] w-full font-bold  text-lg pb-3">
                    <Link href="/reels" :class="{ 'text-white ': !followed, 'text-[#868686]': followed }">
                    Pour vous
                    </Link>
                    <Link href="/reels" :data="{ followed: true }"
                        :class="{ 'text-white': followed, 'text-[#868686]': !followed }">
                    Suivi(e)
                    </Link>
                </div>
                <div v-for="(post, index) in posts.getValue()" :key="index" class="px-4 flex flex-col justify-center mt-6">
                        <Post class="pb-6 border-b border-[#262626]" :post="post" />
                </div>
            </div>
            <div class=" w-[319px] hidden sm:block">
                <div class="flex justify-between items-center gap-3 mb-2">
                    <div class="flex gap-3 mb-4">
                        <div>
                            <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                        </div>
                        <div>
                            <Link class="font-semibold" :href="'/profile/' + user.username">{{ user.username }}</Link>
                            <div class="font-semibold text-[hsl(0,0%,60%)]">{{ user.name }}</div>
                        </div>
                    </div>
                    <div class="text-blue-400">Basculer</div>
                </div>
                <div class="flex justify-between mb-3">
                    <div class="text-[hsl(0,0%,80%)]">Suggestion pour vous</div>
                    <button class="hover:text-[hsl(0,0%,60%)]">Voir tout</button>
                </div>
                <div v-for="(u, index) in mostFollowedUsers" :key="index"
                    class="flex justify-between items-center mb-1">
                    <div class="flex gap-3 mb-4">
                        <Link class="w-11 h-11 rounded-full overflow-hidden" :href="'/profile/' + u.username">
                        <img class="rounded-full" src="https://picsum.photos/seed/picsum/44/44" />
                        </Link>
                        <div>
                            <Link class="font-semibold" :href="'/profile/' + u.username">{{ u.username }}</Link>
                            <div class="font-semibold text-[hsl(0,0%,60%)]">{{ u.name }}</div>
                        </div>
                    </div>
                    <button v-if="!u.followed" @click="sendFollow(u.id)" class="text-blue-400">Suivre</button>
                    <button v-else @click="sendFollow(u.id)" class="text-red-400">Ne plus suivre</button>
                </div>
            </div>
        </div>
        <div ref="landmark"></div>
    </div>
</template>

<style>
.no-scroll {
    overflow: hidden !important;
}
</style>
