<script setup>
import MenuComponent from '@/Components/MenuComponent.vue';
import PlusMenuComponent from '@/Components/PlusMenuComponent.vue';
import { ref, onMounted } from "vue"
import CreatePost from '@/Pages/CreatePost.vue';
import { vOnClickOutside } from '@vueuse/components'
import {
    mdiHome, mdiMagnify, mdiCog, mdiMenu, mdiAvTimer, mdiCompassOutline, mdiYoutube, mdiNearMe, mdiHeartOutline, mdiPlusBoxOutline, mdiAccount, mdiInstagram, mdiBookmarkOutline, mdiWeatherNight, mdiCommentAlertOutline
} from '@mdi/js';
import Search from '@/Pages/Search.vue';
import { usePage } from '@inertiajs/vue3';
import { usePostStore } from '@/Pages/useStore/usePostStore';
import Notifications from '@/Pages/Notifications.vue';
import { useWindowSize } from '@vueuse/core'
import '../../css/app.css'

const showCreatePost = ref(false);
const showPlusMenu = ref(false);
const showSearch = ref(false);
const showNotifications = ref(false);
const posts = usePostStore();
const { width, height } = useWindowSize()

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

const closePlusMenu = () => {
    showPlusMenu.value = false;
}

const togglePlusMenu = () => {
    showPlusMenu.value = !showPlusMenu.value;
}

const changeSearchState = () => {
    showSearch.value = !showSearch.value;
}
const changeNotificationsState = () => {
    showNotifications.value = !showNotifications.value;
    axios.post('/notifications');
}

const toggleShowCreatePost = () => {
    showCreatePost.value = !showCreatePost.value;
    document.documentElement.style.overflow = showCreatePost.value ? 'hidden' : '';
}

</script>
<template>
    <div class="bg-black inset-0 fixed">
        <div class="h-full">
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
            <div class="flex h-full flex-col sm:flex-row">
                <div
                    :class="{ 'lg:w-[200px] w-[70px] xl:w-[335px] shrink-0 hidden sm:block z-0 p-4 border-[#262626] border-r h-screen': true, 'border-r': !showSearch && !showNotifications }">
                    <div :class="{ 'text-[#E0F1FF] flex flex-col gap-3': true }">
                        <div class="mb-12 hidden lg:block">
                            <h1 v-if="!showSearch && !showNotifications" class="text-4xl p-6 ">Instagram</h1>
                            <div v-else>
                                <MenuComponent v-motion-pop :mini="true" :path="mdiInstagram">
                                </MenuComponent>
                            </div>
                        </div>
                        <div class="mb-12 lg:hidden">
                            <MenuComponent v-motion-pop :path="mdiInstagram"></MenuComponent>
                        </div>
                        <MenuComponent :path="mdiHome" url="/" :mini="true">
                            <span v-if="!showSearch && !showNotifications">Accueil</span>
                        </MenuComponent>
                        <MenuComponent :path="mdiMagnify" @click="changeSearchState" :mini="true" :isLink="false">
                            <span v-if="!showSearch && !showNotifications">Recherche</span>
                        </MenuComponent>
                        <MenuComponent :path="mdiCompassOutline" :mini="true">
                            <span v-if="!showSearch && !showNotifications">Découvrir</span>
                        </MenuComponent>
                        <MenuComponent :path="mdiYoutube" url="/reels" :mini="true">
                            <span v-if="!showSearch && !showNotifications">Reels</span>
                        </MenuComponent>
                        <MenuComponent :path="mdiNearMe" :mini="true">
                            <span v-if="!showSearch && !showNotifications">Messages</span>
                        </MenuComponent>
                        <MenuComponent :path="mdiHeartOutline" :mini="true" @click="changeNotificationsState"
                            :isLink="false">
                            <span v-if="!showSearch && !showNotifications">Notifications</span>
                        </MenuComponent>
                        <MenuComponent @click="toggleShowCreatePost" :path="mdiPlusBoxOutline" :isLink="false" :mini="true">
                            <span v-if="!showSearch && !showNotifications">Créer </span>
                        </MenuComponent>
                        <MenuComponent :path="mdiAccount" :url="'/profile/' + usePage().props.auth.user.username"
                            :mini="true">
                            <span v-if="!showSearch && !showNotifications">Profil</span>
                        </MenuComponent>
                    </div>
                    <div class="text-white lg:w-[170px] w-[70px] xl:w-[335px] pr-8 absolute  bottom-0 mb-5"
                        v-on-click-outside="closePlusMenu">
                        <MenuComponent @click="togglePlusMenu" :path="mdiMenu" :is-link="false" :mini="true">
                            <span class="w-full" v-if="!showSearch && !showNotifications">Plus</span>
                        </MenuComponent>
                        <div v-if="showPlusMenu" class="bg-[#262626] absolute top-[-390px] rounded-2xl p-1">
                            <div class="border-b-[6px] rounded-b-none border-[#353535] p-2">
                                <PlusMenuComponent :path="mdiCog" :is-link="true" :mini="true">
                                    <span v-if="!showSearch && !showNotifications">Paramètres</span>
                                </PlusMenuComponent>
                                <PlusMenuComponent :path="mdiAvTimer" :is-link="true" :mini="true">
                                    <span v-if="!showSearch && !showNotifications">Votre activité</span>
                                </PlusMenuComponent>
                                <PlusMenuComponent :path="mdiBookmarkOutline" :is-link="true" :mini="true">
                                    <span v-if="!showSearch && !showNotifications">Enregistrements</span>
                                </PlusMenuComponent>
                                <PlusMenuComponent :path="mdiWeatherNight" :is-link="true" :mini="true">
                                    <span v-if="!showSearch && !showNotifications">Changer l'apparence</span>
                                </PlusMenuComponent>
                                <PlusMenuComponent :path="mdiCommentAlertOutline" :is-link="true" :mini="true">
                                    <span v-if="!showSearch && !showNotifications">Signaler un problème</span>
                                </PlusMenuComponent>
                            </div>
                            <div class="border-b-[2px] rounded-b-none border-[#353535] p-2">
                                <PlusMenuComponent :allow-icon="false" :is-link="true" :mini="true">
                                    <span v-if="!showSearch && !showNotifications">Changer de compte</span>
                                </PlusMenuComponent>
                            </div>
                            <div class="p-2">
                                <PlusMenuComponent :allow-icon="false" url="/logout" as="button" method="post"
                                    :is-link="true" :mini="true">
                                    <span v-if="!showSearch && !showNotifications">Déconnexion</span>
                                </PlusMenuComponent>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="width < 1023 && !usePage().url.includes('reels')"
                    :class="{ 'sm:hidden border-[#262626] p-4 border-b flex w-full max-w-full text-white items-center': true }">
                    <div class="flex-shrink-[2] w-full min-w-0 text-3xl truncate font-bold">Pour vous</div>
                    <input type="text" @input="getUsers($event.target.value)" placeholder="Rechercher"
                        class=" bg-[hsl(0,0%,15%)] shrink rounded-md w-[400px] outline-none border-none py-3 px-4 focus:ring-0" />
                    <div class="flex items-center shrink-0 pl-3">
                        <MenuComponent :path="mdiHeartOutline" :mini="true" @click="changeNotificationsState"
                            :isLink="false">
                            <span v-if="!showSearch && !showNotifications">Notifications</span>
                        </MenuComponent>
                    </div>
                </div>
                <Transition enter-from-class="opacity-0" enter-leave-class="opacity-100"
                    enter-active-class="transition-opacity ease-in duration-400" leave-to-class="opacity-0"
                    leave-active-class="transition duration-200 ease-in">
                    <create-post @toggleShowCreatePost="toggleShowCreatePost" v-model:showCreatePost="showCreatePost"
                        v-if="showCreatePost"></create-post>
                </Transition>
                <div id="main-content"
                    :class="{ 'flex-1 overflow-auto w-full h-full scrollbar-hide lg:scrollbar-default relative sm:lock-scroll': true, 'hidden sm:block': showCreatePost }">
                    <slot></slot>
                </div>
                <div :class="{ 'shrink-0 sm:hidden h-[46px] flex items-center z-50 p-4 border-[#262626] border-t': true }">
                    <div :class="{ 'text-[#E0F1FF] grid grid-cols-6 w-full gap-3 justify-evenly': true }">
                        <MenuComponent :path="mdiHome" url="/" :mini="true">
                            <span>Accueil</span>
                        </MenuComponent>
                        <MenuComponent :path="mdiCompassOutline" :mini="true">
                            <span>Découvrir</span>
                        </MenuComponent>
                        <MenuComponent :path="mdiYoutube" url="/reels" :mini="true">
                            <span>Reels</span>
                        </MenuComponent>
                        <MenuComponent :path="mdiNearMe" :mini="true">
                            <span>Messages</span>
                        </MenuComponent>
                        <MenuComponent @click="toggleShowCreatePost" :path="mdiPlusBoxOutline" :isLink="false" :mini="true">
                            <span>Créer </span>
                        </MenuComponent>
                        <MenuComponent :path="mdiAccount" :url="'/profile/' + usePage().props.auth.user.username"
                            :mini="true">
                            <span>Profil</span>
                        </MenuComponent>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
