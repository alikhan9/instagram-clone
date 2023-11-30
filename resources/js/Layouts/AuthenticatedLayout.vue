<script setup>
import MenuComponent from "@/Components/MenuComponent.vue";
import PlusMenuComponent from "@/Components/PlusMenuComponent.vue";
import { ref, onMounted, watch } from "vue";
import CreatePost from "@/Pages/CreatePost.vue";
import { vOnClickOutside } from "@vueuse/components";
import {
    mdiHome,
    mdiMagnify,
    mdiCog,
    mdiMenu,
    mdiAvTimer,
    mdiCompassOutline,
    mdiYoutube,
    mdiNearMe,
    mdiHeartOutline,
    mdiPlusBoxOutline,
    mdiAccount,
    mdiInstagram,
    mdiBookmarkOutline,
    mdiWeatherNight,
    mdiCommentAlertOutline,
} from "@mdi/js";
import Search from "@/Pages/Search.vue";
import { usePage } from "@inertiajs/vue3";
import { usePostStore } from "@/Pages/useStore/usePostStore";
import MobileMenuTop from "@/Pages/MobileMenuTop.vue";
import Notifications from "@/Pages/Notifications.vue";
import { useWindowSize } from "@vueuse/core";
import "../../css/app.css";
import { useMessageStore } from "@/Pages/useStore/useMessageStore";

const showCreatePost = ref(false);
const showPlusMenu = ref(false);
const showSearch = ref(false);
const directPage = ref(usePage().props.ziggy.location.includes("direct"));
const showNotifications = ref(false);
const posts = usePostStore();
const messages = useMessageStore();
const { width } = useWindowSize();

watch(
    () => usePage().props.ziggy.location,
    (newValue) => {
        directPage.value = newValue.includes("direct");
    },
);

onMounted(() => {
    posts.setNotifications(
        usePage()
            .props.auth.notifications.sort(
                (a, b) => a.created_at - b.created_at,
            )
            .filter(
                (n) => n.type !== "App\\Notifications\\NewMessageNotification",
            ),
    );
    messages.setNotifications(
        usePage()
            .props.auth.notifications.sort(
                (a, b) => a.created_at - b.created_at,
            )
            .filter(
                (n) => n.type === "App\\Notifications\\NewMessageNotification",
            ),
    );
    messages.updateUnreadNotifications();
    Echo.private(
        "App.Models.User." + usePage().props.auth.user.id,
    ).notification((notification) => {
        if (notification.type == "App\\Notifications\\NewMessageNotification") {
            messages.increaseUnreadNotifications();
            messages.addNotification(notification);
        } else posts.addNotification(notification);
    });
    Echo.private("App.Models.User." + usePage().props.auth.user.id).listen(
        ".message",
        (e) => {
            if (!window.location.href.includes("/direct/t/" + e.message.sender))
                axios.post("/message/notifications/notify", {
                    sender: e.message.sender,
                });
            else messages.addMessage(e.message);
        },
    );
});

const closeSearchOrNotifications = () => {
    showSearch.value = false;
    showNotifications.value = false;
};

const closePlusMenu = () => {
    showPlusMenu.value = false;
};

const togglePlusMenu = () => {
    showPlusMenu.value = !showPlusMenu.value;
};

const changeSearchState = () => {
    showSearch.value = !showSearch.value;
};
const changeNotificationsState = () => {
    showNotifications.value = !showNotifications.value;
    axios.post("/notifications");
};

const toggleShowCreatePost = () => {
    showCreatePost.value = !showCreatePost.value;
    document.documentElement.style.overflow = showCreatePost.value
        ? "hidden"
        : "";
};
</script>
<template>
    <div class="fixed inset-0 overflow-hidden bg-black">
        <div class="h-full">
            <Transition
                enter-from-class="scale-x-0"
                enter-leave-class="scale-x-100 "
                enter-active-class="transition duration-300 ease-out origin-left"
                leave-from-class="translate-x-[-20px]"
                leave-to-class="translate-x-[-130%]"
                leave-active-class="transition duration-200 ease-in"
            >
                <Search
                    class="z-50"
                    v-if="showSearch"
                    v-on-click-outside="closeSearchOrNotifications"
                />
            </Transition>
            <Transition
                enter-from-class="scale-x-0"
                enter-leave-class="scale-x-100 "
                enter-active-class="transition duration-300 ease-out origin-left"
                leave-from-class="translate-x-[-20px]"
                leave-to-class="translate-x-[-130%]"
                leave-active-class="transition duration-200 ease-in"
            >
                <Notifications
                    class="z-50"
                    v-if="showNotifications"
                    v-on-click-outside="closeSearchOrNotifications"
                />
            </Transition>
            <div class="flex h-full flex-col sm:flex-row">
                <div
                    :class="{
                        'z-20 hidden h-screen w-[70px] shrink-0 border-r border-[#262626] p-4 sm:block lg:w-[200px] xl:w-[335px]': true,
                        'border-r lg:w-[70px] xl:w-[70px]':
                            showSearch || showNotifications || directPage,
                    }"
                >
                    <div
                        :class="{ 'flex flex-col gap-3 text-[#E0F1FF]': true }"
                    >
                        <div class="hidden h-[130px] lg:block">
                            <h1
                                v-if="
                                    !showSearch &&
                                    !showNotifications &&
                                    !directPage
                                "
                                class="p-6 text-4xl"
                            >
                                Instagram
                            </h1>
                            <div v-else>
                                <MenuComponent
                                    v-motion-pop
                                    :mini="true"
                                    :path="mdiInstagram"
                                >
                                </MenuComponent>
                            </div>
                        </div>
                        <div class="h-[130px] lg:hidden">
                            <MenuComponent
                                v-motion-pop
                                :path="mdiInstagram"
                            ></MenuComponent>
                        </div>
                        <MenuComponent
                            :path="mdiHome"
                            url="/"
                            :mini="
                                showSearch || showNotifications || directPage
                            "
                        >
                            <span>Accueil</span>
                        </MenuComponent>
                        <MenuComponent
                            :path="mdiMagnify"
                            @click="changeSearchState"
                            :mini="
                                showSearch || showNotifications || directPage
                            "
                            :isLink="false"
                        >
                            <span>Recherche</span>
                        </MenuComponent>
                        <MenuComponent
                            :path="mdiCompassOutline"
                            :mini="
                                showSearch || showNotifications || directPage
                            "
                        >
                            <span>Découvrir</span>
                        </MenuComponent>
                        <MenuComponent
                            :path="mdiYoutube"
                            url="/reels"
                            :mini="
                                showSearch || showNotifications || directPage
                            "
                        >
                            <span>Reels</span>
                        </MenuComponent>
                        <MenuComponent
                            class="relative"
                            :dontHideContent="true"
                            :path="mdiNearMe"
                            url="/direct"
                            :mini="
                                showSearch || showNotifications || directPage
                            "
                        >
                            <span
                                class="absolute left-7 top-1 flex h-5 w-5 items-center justify-center rounded-full border border-[#262626] bg-red-500 text-sm text-white" :class="{'hidden':messages.unreadNotifications == 0}"
                                >{{ messages.unreadNotifications }}
                            </span>
                            <span
                                :class="{
                                    'hidden lg:block': true,
                                    'lg:hidden': directPage,
                                }"
                            >
                                Messages
                            </span>
                        </MenuComponent>
                        <MenuComponent
                            :path="mdiHeartOutline"
                            :mini="
                                showSearch || showNotifications || directPage
                            "
                            @click="changeNotificationsState"
                            :isLink="false"
                        >
                            <span>Notifications</span>
                        </MenuComponent>
                        <MenuComponent
                            @click="toggleShowCreatePost"
                            :path="mdiPlusBoxOutline"
                            :isLink="false"
                            :mini="
                                showSearch || showNotifications || directPage
                            "
                        >
                            <span>Créer </span>
                        </MenuComponent>
                        <MenuComponent
                            :path="mdiAccount"
                            :url="
                                '/profile/' + usePage().props.auth.user.username
                            "
                            :mini="
                                showSearch || showNotifications || directPage
                            "
                        >
                            <span>Profil</span>
                        </MenuComponent>
                    </div>
                    <div
                        class="absolute bottom-0 mb-5 w-[70px] pr-8 text-white lg:w-[170px] xl:w-[335px]"
                        v-on-click-outside="closePlusMenu"
                    >
                        <MenuComponent
                            @click="togglePlusMenu"
                            :path="mdiMenu"
                            :is-link="false"
                            :mini="
                                showSearch || showNotifications || directPage
                            "
                        >
                            <span class="w-full">Plus</span>
                        </MenuComponent>
                        <div
                            v-if="showPlusMenu"
                            class="absolute top-[-390px] rounded-2xl bg-[#262626] p-1"
                        >
                            <div
                                class="rounded-b-none border-b-[6px] border-[#353535] p-2"
                            >
                                <PlusMenuComponent
                                    :path="mdiCog"
                                    :is-link="true"
                                >
                                    <span
                                        v-if="!showSearch && !showNotifications"
                                        >Paramètres</span
                                    >
                                </PlusMenuComponent>
                                <PlusMenuComponent
                                    :path="mdiAvTimer"
                                    :is-link="true"
                                >
                                    <span
                                        v-if="!showSearch && !showNotifications"
                                        >Votre activité</span
                                    >
                                </PlusMenuComponent>
                                <PlusMenuComponent
                                    :path="mdiBookmarkOutline"
                                    :is-link="true"
                                >
                                    <span
                                        v-if="!showSearch && !showNotifications"
                                        >Enregistrements</span
                                    >
                                </PlusMenuComponent>
                                <PlusMenuComponent
                                    :path="mdiWeatherNight"
                                    :is-link="true"
                                >
                                    <span
                                        v-if="!showSearch && !showNotifications"
                                        >Changer l'apparence</span
                                    >
                                </PlusMenuComponent>
                                <PlusMenuComponent
                                    :path="mdiCommentAlertOutline"
                                    :is-link="true"
                                >
                                    <span
                                        v-if="!showSearch && !showNotifications"
                                        >Signaler un problème</span
                                    >
                                </PlusMenuComponent>
                            </div>
                            <div
                                class="rounded-b-none border-b-[2px] border-[#353535] p-2"
                            >
                                <PlusMenuComponent
                                    :allow-icon="false"
                                    :is-link="true"
                                >
                                    <span
                                        v-if="!showSearch && !showNotifications"
                                        >Changer de compte</span
                                    >
                                </PlusMenuComponent>
                            </div>
                            <div class="p-2">
                                <PlusMenuComponent
                                    :allow-icon="false"
                                    url="/logout"
                                    as="button"
                                    method="post"
                                    :is-link="true"
                                >
                                    <span
                                        v-if="!showSearch && !showNotifications"
                                        >Déconnexion</span
                                    >
                                </PlusMenuComponent>
                            </div>
                        </div>
                    </div>
                </div>
                <MobileMenuTop
                    v-if="width < 1023 && !usePage().url.includes('reels')"
                />
                <Transition
                    enter-from-class="opacity-0 "
                    enter-leave-class="opacity-100"
                    enter-active-class="transition-opacity ease-in duration-400"
                    leave-to-class="opacity-0"
                    leave-active-class="transition duration-200 ease-in"
                >
                    <create-post
                        @toggleShowCreatePost="toggleShowCreatePost"
                        v-model:showCreatePost="showCreatePost"
                        v-if="showCreatePost"
                    ></create-post>
                </Transition>
                <div
                    id="main-content"
                    :class="{
                        'sm:lock-scroll relative h-full w-full flex-1 overflow-auto scrollbar-hide lg:scrollbar-default': true,
                        'hidden sm:block': showCreatePost,
                    }"
                >
                    <slot></slot>
                </div>
                <div
                    :class="{
                        'z-50 flex h-[46px] shrink-0 items-center border-t border-[#262626] p-4 sm:hidden': true,
                    }"
                >
                    <div
                        :class="{
                            'grid w-full grid-cols-6 justify-evenly gap-3 text-[#E0F1FF]': true,
                        }"
                    >
                        <MenuComponent :path="mdiHome" url="/" :mini="true">
                            <span>Accueil</span>
                        </MenuComponent>
                        <MenuComponent :path="mdiCompassOutline" :mini="true">
                            <span>Découvrir</span>
                        </MenuComponent>
                        <MenuComponent
                            :path="mdiYoutube"
                            url="/reels"
                            :mini="true"
                        >
                            <span>Reels</span>
                        </MenuComponent>
                        <MenuComponent
                            :path="mdiNearMe"
                            url="/direct"
                            :mini="true"
                        >
                            <span>Messages</span>
                        </MenuComponent>
                        <MenuComponent
                            @click="toggleShowCreatePost"
                            :path="mdiPlusBoxOutline"
                            :isLink="false"
                            :mini="true"
                        >
                            <span>Créer </span>
                        </MenuComponent>
                        <MenuComponent
                            :path="mdiAccount"
                            :url="
                                '/profile/' + usePage().props.auth.user.username
                            "
                            :mini="true"
                        >
                            <span>Profil</span>
                        </MenuComponent>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
@/Pages/useStore/useMessageStore
