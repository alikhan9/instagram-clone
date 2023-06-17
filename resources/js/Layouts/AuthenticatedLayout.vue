<script setup>
import MenuComponent from '@/Components/MenuComponent.vue';
import { ref } from "vue"
import CreatePost from '@/Pages/CreatePost.vue';
import { vOnClickOutside } from '@vueuse/components'
import {
    mdiHome, mdiMagnify, mdiCompassOutline, mdiYoutube, mdiNearMe, mdiHeartOutline, mdiPlusBoxOutline, mdiAccount, mdiInstagram
} from '@mdi/js';
import Search from '@/Pages/Search.vue';

const showCreatePost = ref(false);
const showSearch = ref(false);
const timerToCloseSearch = ref();

const closeSearch = () => {
    if (timerToCloseSearch.value)
        clearTimeout(timerToCloseSearch.value);
    showSearch.value = false;
}

const changeSearchState = () => {
    timerToCloseSearch.value = setTimeout(() => {
        showSearch.value = !showSearch.value;
        timerToCloseSearch.value = null;
    })
}

</script>
<!-- v-motion-slide-left -->
<template>
    <div>
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
            <Search class="z-50" v-if="showSearch" v-on-click-outside="closeSearch" />
        </Transition>
        <div class="bg-black min-h-screen w-screen flex ">
            <div :class="{
                    'min-w-[335px] fixed p-4 z-30 h-screen border-[#262626] text-[#E0F1FF] flex flex-col gap-3': true,
                    'border-r': !showSearch
                }">
                <div class="mb-6">
                    <h1 v-if="!showSearch" class="text-4xl p-6 ">Instagram</h1>
                    <div v-else>
                        <MenuComponent v-motion-pop :mini="showSearch" :path="mdiInstagram"></MenuComponent>
                        <div class="p-5"></div>
                    </div>
                </div>
                <MenuComponent :path="mdiHome" url="/" :mini="showSearch">
                    <span v-if="!showSearch">Accueil</span>
                </MenuComponent>
                <MenuComponent :path="mdiMagnify" @click="changeSearchState" :mini="showSearch">
                    <span v-if="!showSearch">Recherche</span>
                </MenuComponent>
                <MenuComponent :path="mdiCompassOutline" :mini="showSearch">
                    <span v-if="!showSearch">Découvrir</span>
                </MenuComponent>
                <MenuComponent :path="mdiYoutube" :mini="showSearch">
                    <span v-if="!showSearch">Reels</span>
                </MenuComponent>
                <MenuComponent :path="mdiNearMe" :mini="showSearch">
                    <span v-if="!showSearch">Messages</span>
                </MenuComponent>
                <MenuComponent :path="mdiHeartOutline" :mini="showSearch">
                    <span v-if="!showSearch">Notifications</span>
                </MenuComponent>
                <MenuComponent @click="showCreatePost = !showCreatePost" :path="mdiPlusBoxOutline" :mini="showSearch">
                    <span v-if="!showSearch">Créer </span>
                </MenuComponent>
                <MenuComponent :path="mdiAccount" :mini="showSearch">
                    <span v-if="!showSearch">Profil</span>
                </MenuComponent>
            </div>
            <div class="">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
