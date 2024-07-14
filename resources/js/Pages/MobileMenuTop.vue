<script setup>
import { mdiHeartOutline, mdiArrowLeft } from '@mdi/js';
import MenuComponent from '@/Components/MenuComponent.vue';
import SvgIcon from '@jamescoyle/vue-icon';
import { ref } from 'vue';
import { useDebounceFn } from '@vueuse/core'
import { Link } from '@inertiajs/vue3'
import { usePostStore } from './useStore/usePostStore.js'


const posts = usePostStore();
const showNotifications = ref(false);
const showUsers = ref(false);
const users = ref([]);

const getUsers = useDebounceFn(value => {
    if (value)
        axios.get('/search/' + value)
            .then(response => {
                users.value = response.data;
            })
}, 200)


const toggleShowNotifications = () => {
    showNotifications.value = !showNotifications.value;
    axios.post('/notifications');
}

const toggleShowUsers = () => {
    showUsers.value = !showUsers.value;
}

</script>



<template>
    <div
        :class="{ 'sm:hidden ': true, 'fixed inset-0 top-0 left-0 z-[99] bg-black flex flex-col': showNotifications || showUsers }">
        <div v-if="!showNotifications"
            class="border-[#262626] p-4 border-b flex w-full max-w-full text-white items-center shrink-0">
            <div v-if="!showUsers" class="flex-shrink-[2] w-full min-w-0 text-3xl truncate font-bold">
                Pour vous
            </div>
            <input type="text" @click="() => showUsers = true" @input="getUsers($event.target.value)"
                placeholder="Rechercher"
                :class="{ 'bg-[hsl(0,0%,15%)] shrink rounded-md w-[400px] outline-none border-none py-3 px-4 focus:ring-0': true, 'w-full': showNotifications || showUsers }" />
            <div v-if="!showNotifications && !showUsers" class="flex items-center shrink-0 pl-3">
                <MenuComponent :path="mdiHeartOutline" :mini="true" @click="toggleShowNotifications" :isLink="false">
                </MenuComponent>
            </div>
            <div v-if="showUsers" class="ml-4" @click="() => showUsers = false">Annuler</div>
        </div>
        <div v-if="showNotifications"
            class="flex justify-between text-white items-center px-4 py-2 border-[#262626] border-b">
            <div>
                <svg-icon size="30" @click="() => showNotifications = false" class="hover:cursor-pointer" type="mdi"
                    :path="mdiArrowLeft"></svg-icon>
            </div>
            <div class="text-xl">Notifications</div>
            <div></div>
        </div>
        <div v-if="showUsers" class="flex-1 text-white h-full overflow-auto w-full">
            <Link :href="'/profile/' + user.username" class="flex items-center gap-4 px-4 py-2 hover:bg-[hsl(0,0%,15%)]"
                v-for="(user, index) in users" v-if="users" :key="index" :onFinish="() => showUsers = false">
            <div class="w-11 h-11 rounded-full overflow-hidden">
                <img class="w-full h-full" :src="user.avatar" />
            </div>
            <div>
                <div class="font-semibold">{{ user.username }}</div>
                <div class="flex text-[#858585] text-lg">
                    <div>{{ user.name }}</div>
                    <div class="mx-2">.</div>
                    <div>{{ user.followersCount }} followers</div>
                </div>
            </div>
            </Link>
        </div>
        <div v-if="showNotifications" :class="showNotifications" class="flex-1 text-white h-full overflow-auto w-full">
            <div v-if="posts.isNotificationEmpty()" class="flex flex-col mt-14 gap-4 items-center">
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
            <div v-if="posts.isNotificationEmpty()" class="mt-14 text-lg px-4">
                <div class="font-semibold">
                    Suggestions pour vous
                </div>
            </div>
        </div>
    </div>
</template>