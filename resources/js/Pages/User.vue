<script setup>
import Sections from './Components/User/Sections.vue'
import UserPosts from './Components/User/UserPosts.vue'
import UserFollowersInfo from './Components/User/UserFollowersInfo.vue'
import { Head, usePage, router } from '@inertiajs/vue3';
import Following from './Following.vue';
import { ref, watch } from 'vue'

const props = defineProps({
    user: Object,
    total_posts: Number,
    posts: Object,
    active: {
        type: Number,
        default: '0'
    },
    isFollowing: Boolean,
    followersCount: Number,
    followingCount: Number,
    openFollowers: Boolean,
    openFollowing: Boolean,
    followers: Object,
    following: Object,
});

const showFollowing = ref(props.openFollowing);
const showFollowers = ref(props.openFollowers);

const toggleShowFollowing = () => {
    showFollowing.value = !showFollowing.value;
}
const toggleShowFollowers = () => {
    showFollowers.value = !showFollowers.value;
}
</script>

<template>
    <Head :title="usePage().props.auth.user.name" />
    <div class="h-full">
        <Following v-if="showFollowing" :following="following" @closeFollowing="toggleShowFollowing" />
        <Following v-if="showFollowers" :isFollowers="true" :following="followers" @closeFollowing="toggleShowFollowers" />
        <div :class="{ 'overflow-x-hidden': true, 'hidden lg:block': showFollowers || showFollowing }">
            <div class="text-white flex items-center flex-col w-full mt-10 mb-10">
                <UserFollowersInfo :followingCount="followingCount" :followersCount="followersCount"
                    :isFollowing="isFollowing" :total_posts="total_posts" :user="user"
                    @toggleShowFollowers="toggleShowFollowers" @toggleShowFollowing="toggleShowFollowing" />
                <Sections :user="user" :active="active" />
                <UserPosts />
            </div>
        </div>
    </div>
</template>