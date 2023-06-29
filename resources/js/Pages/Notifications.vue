<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeartOutline } from '@mdi/js';
import { usePostStore } from './useStore/usePostStore.js'

const posts = usePostStore();
</script>

<template>
    <div class="w-[400px] h-screen bg-black text-white ml-[70px] absolute  border-r border-[hsl(0,0%,23%)]">
        <div class="px-5 py-4">
            <h1 class="text-3xl font-bold">Notifications</h1>
        </div>
        <div v-if="posts.isNotificationEmpty()" class="flex flex-col gap-4 items-center mt-3">
            <div>
                <div class="border inline-block px-3 border-[#3b3b3b] py-3  rounded-full">
                    <svg-icon type="mdi" size="40" color="#3b3b3b" :path="mdiHeartOutline"></svg-icon>
                </div>
            </div>
            <div>
                Activité sur vos publications
            </div>
            <div class="max-w-[80%] text-center">
                Lorsque quelqu’un aime ou commente une de vos publications, vous le voyez ici.
            </div>
        </div>
        <div v-else>
            <div v-for="(notification, index) in posts.getNotifications()" :key="index">
                <div class="px-4 mb-2">
                    {{ notification.data.message }}
                </div>
            </div>
        </div>
        <div class="mt-14 text-lg px-4">
            <div class="font-semibold">
                Suggestions pour vous
            </div>
        </div>

    </div>
</template>