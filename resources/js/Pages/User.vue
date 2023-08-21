<script setup>
import Sections from './Components/User/Sections.vue'
import UserPosts from './Components/User/UserPosts.vue'
import UserFollowersInfo from './Components/User/UserFollowersInfo.vue'
import { Head, usePage } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    total_posts: Number,
    posts: Object,
    active: {
        type: Number,
        default: '0'
    }
});

</script>

<template>
    <Head :title="usePage().props.auth.user.name" />
    <div class="overflow-x-hidden">
        <div class="text-white grid grid-cols-11 mt-10">
            <UserFollowersInfo :total_posts="total_posts" :user="user" />
            <Sections :active="active" />
            <UserPosts />
        </div>
    </div>
</template>