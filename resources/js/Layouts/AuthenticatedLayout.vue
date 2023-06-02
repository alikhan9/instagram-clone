<script setup>
import MenuComponent from '@/Components/MenuComponent.vue';
import { ref } from "vue"
import CreatePost from '@/Pages/CreatePost.vue';
import {
    mdiHome, mdiMagnify, mdiCompassOutline, mdiYoutube, mdiNearMe, mdiHeartOutline, mdiPlusBoxOutline, mdiAccount
} from '@mdi/js';

const showCreatePost = ref(false);

</script>

<template>
    <create-post class="absolute" :showCreatePost="showCreatePost" v-if="showCreatePost"></create-post>
    <div class="bg-black h-screen w-screen flex ">
        <div class="min-w-[335px] p-4 border-r border-[#262626] text-[#E0F1FF] flex flex-col gap-3">
            <h1 class="text-4xl p-6 mb-6">Instagram</h1>
            <MenuComponent :path="mdiHome">Accueil</MenuComponent>
            <MenuComponent :path="mdiMagnify">Recherche</MenuComponent>
            <MenuComponent :path="mdiCompassOutline">Découvrir</MenuComponent>
            <MenuComponent :path="mdiYoutube">Reels</MenuComponent>
            <MenuComponent :path="mdiNearMe">Messages</MenuComponent>
            <MenuComponent :path="mdiHeartOutline">Notifications</MenuComponent>
            <MenuComponent @click="showCreatePost = !showCreatePost" :path="mdiPlusBoxOutline">Créer</MenuComponent>
            <MenuComponent :path="mdiAccount">Profil</MenuComponent>
        </div>
        <slot></slot>
    </div>
</template>
