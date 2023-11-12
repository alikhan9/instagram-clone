<script setup>
import Sections from './Components/User/Sections.vue'
import UserPosts from './Components/User/UserPosts.vue'
import UserFollowersInfo from './Components/User/UserFollowersInfo.vue'
import { Head, usePage, router } from '@inertiajs/vue3';
import Followers from './Followers.vue';
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

watch(() => props.openFollowers, (newValue, oldValue) => {
    showFollowers.value = newValue;
});

watch(() => props.openFollowing, (newValue, oldValue) => {
    showFollowing.value = newValue;
});

const showFollowers = ref(props.openFollowers);
const showFollowing = ref(props.openFollowing);

const toggleShowFollowers = () => {
    showFollowers.value = !showFollowers.value
    if (!showFollowers.value)
        router.get("/profile/" + props.user.username, {}, {
            preserveState: true,
        });
}
const toggleShowFollowing = () => {
    showFollowing.value = !showFollowing.value;
    if (!showFollowing.value)
        router.get("/profile/" + props.user.username, {}, {
            preserveState: true,
        });
}


</script>

<template>
    <Head :title="usePage().props.auth.user.name" />
    <div class="overflow-x-hidden">
        <div class="text-white flex items-center flex-col w-full mt-10 mb-10">
            <Followers v-if="showFollowers" :followers="followers" @closeFollowers="toggleShowFollowers" />
            <Following v-if="showFollowing" :following="following" @closeFollowing="toggleShowFollowing" />
            <UserFollowersInfo :followingCount="followingCount" :followersCount="followersCount" :isFollowing="isFollowing"
                :total_posts="total_posts" :user="user" />
            <Sections :active="active" />
            <UserPosts />
        </div>
    </div>
</template>