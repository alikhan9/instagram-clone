<script setup>
import MenuComponent from '@/Components/MenuComponent.vue';
import { ref, onMounted } from "vue"
import CreatePost from '@/Pages/CreatePost.vue';
import { vOnClickOutside } from '@vueuse/components'
import {
    mdiHome, mdiMagnify, mdiCompassOutline, mdiYoutube, mdiNearMe, mdiHeartOutline, mdiPlusBoxOutline, mdiAccount, mdiInstagram
} from '@mdi/js';
import Search from '@/Pages/Search.vue';
import { usePage } from '@inertiajs/vue3';
import { usePostStore } from '@/Pages/useStore/usePostStore';
import Notifications from '@/Pages/Notifications.vue';

const showCreatePost = ref(false);
const showSearch = ref(false);
const showNotifications = ref(false);
const posts = usePostStore();

onMounted(() => {
    posts.setNotifications(usePage().props.auth.notifications.sort((a, b) => a.created_at - b.created_at));
    Echo.private('App.Models.User.' + usePage().props.auth.user.id)
        .notification((notification) => {
            posts.addNotification(notification);
        });
});

const closeSearchOrNotifications = () => {
    showSearch.value = false;
    showNotifications.value = false;
}

const changeSearchState = () => {
    showSearch.value = !showSearch.value;
}
const changeNotificationsState = () => {
    showNotifications.value = !showNotifications.value;
}

</script>
<template>
    <div class="bg-black">
        <div class="absolute bg-opacity-40 z-50">
            <Transition enter-from-class="opacity-0" enter-leave-class="opacity-100"
                enter-active-class="transition-opacity ease-in duration-400" leave-to-class="opacity-0"
                leave-active-class="transition duration-200 ease-in">
                <create-post class="absolute" v-model:showCreatePost="showCreatePost" v-if="showCreatePost"></create-post>
            </Transition>
        </div>
        <Transition enter-from-class="scale-x-0" enter-leave-class="scale-x-100 "
            enter-active-class="transition ease-out duration-300 origin-left" leave-from-class="translate-x-[-20px]"
            leave-to-class="translate-x-[-130%]" leave-active-class="transition duration-200 ease-in">
            <Search class="z-50" v-if="showSearch" v-on-click-outside="closeSearchOrNotifications" />
        </Transition>
        <Transition enter-from-class="scale-x-0" enter-leave-class="scale-x-100 "
            enter-active-class="transition ease-out duration-300 origin-left" leave-from-class="translate-x-[-20px]"
            leave-to-class="translate-x-[-130%]" leave-active-class="transition duration-200 ease-in">
            <Notifications class="z-50" v-if="showNotifications" v-on-click-outside="closeSearchOrNotifications" />
        </Transition>
        <div class="min-h-screen w-screen flex ">
            <div :class="{
                'min-w-[335px] fixed p-4 z-30 h-screen border-[#262626] text-[#E0F1FF] flex flex-col gap-3': true,
                'border-r': !showSearch && !showNotifications
            }">
                <div class="mb-6">
                    <h1 v-if="!showSearch && !showNotifications" class="text-4xl p-6 ">Instagram</h1>
                    <div v-else>
                        <MenuComponent v-motion-pop :mini="showSearch || showNotifications" :path="mdiInstagram">
                        </MenuComponent>
                        <div class="p-5"></div>
                    </div>
                </div>
                <MenuComponent :path="mdiHome" url="/" :mini="showSearch || showNotifications">
                    <span v-if="!showSearch && !showNotifications">Accueil</span>
                </MenuComponent>
                <MenuComponent :path="mdiMagnify" @click="changeSearchState" :mini="showSearch || showNotifications"
                    :isLink="false">
                    <span v-if="!showSearch && !showNotifications">Recherche</span>
                </MenuComponent>
                <MenuComponent :path="mdiCompassOutline" :mini="showSearch || showNotifications">
                    <span v-if="!showSearch && !showNotifications">Découvrir</span>
                </MenuComponent>
                <MenuComponent :path="mdiYoutube" :mini="showSearch || showNotifications">
                    <span v-if="!showSearch && !showNotifications">Reels</span>
                </MenuComponent>
                <MenuComponent :path="mdiNearMe" :mini="showSearch || showNotifications">
                    <span v-if="!showSearch && !showNotifications">Messages</span>
                </MenuComponent>
                <MenuComponent :path="mdiHeartOutline" :mini="showSearch || showNotifications"
                    @click="changeNotificationsState" :isLink="false">
                    <span v-if="!showSearch && !showNotifications">Notifications</span>
                </MenuComponent>
                <MenuComponent @click="showCreatePost = !showCreatePost" :path="mdiPlusBoxOutline" :isLink="false"
                    :mini="showSearch || showNotifications">
                    <span v-if="!showSearch && !showNotifications">Créer </span>
                </MenuComponent>
                <MenuComponent :path="mdiAccount" :url="'/profile/' + usePage().props.auth.user.name"
                    :mini="showSearch || showNotifications">
                    <span v-if="!showSearch && !showNotifications">Profil</span>
                </MenuComponent>
            </div>
            <div class="">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
