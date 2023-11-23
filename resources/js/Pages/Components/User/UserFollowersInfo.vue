<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiAccountPlus, mdiDotsHorizontal } from '@mdi/js';
import { ref } from 'vue';
import { Link, router,usePage } from '@inertiajs/vue3';
const props = defineProps({
    total_posts: Number,
    user: Object,
    isFollowing: Boolean,
    followersCount: Number,
    followingCount: Number
})

const following = ref(props.isFollowing);


const sendFollow = () => {
    axios.post('/follow/' + props.user.id)
        .then(res => {
            following.value = res.data;
        });
}

const loadFollowers = () => {
    router.get("/profile/" + props.user.username + "/followers", {}, {
        preserveState: true,
        only: ['followers', 'openFollowers']
    })
}
const loadFollowing = () => {
    router.get("/profile/" + props.user.username + "/following", {}, {
        preserveState: true,
        only: ['following', 'openFollowing']
    })
}

</script>

<template>
    <div class="col-start-3 w-full md:w-[935px]  md:pb-10 border-b border-[#262626] md:pl-16">
        <div class="flex w-full gap-4 md:justify-between justify-center">
            <div class=" rounded-full shrink-0 overflow-hidden w-[91px] h-[91px] md:w-[168px] md:h-[168px]">
                <img class="w-full h-full" src="https://picsum.photos/seed/picsum/150/150" />
            </div>
            <div class="md:w-[70%] text-lg shrink min-w-0">
                <div class="flex w-full md:flex-row md:justify-start justify-center flex-col md:items-center gap-4">
                    <div class="flex">
                        <div class="mr-10 text-xl font-semibold">{{ user.username }}</div>
                        <div> <svg-icon class="md:hidden" type="mdi" size="32" color="white"
                                :path="mdiDotsHorizontal"></svg-icon></div>
                    </div>
                    <div class="flex gap-4">
                        <div v-if="!following" class="px-6 py-1  bg-[#1877F2] rounded-lg hover:cursor-pointer"
                            @click="sendFollow">Suivre</div>
                        <div v-else class="px-6 py-1  bg-red-500 rounded-lg hover:cursor-pointer" @click="sendFollow">Ne
                            plus
                            suivre</div>
                        <div class="w-[93px] text-center text-black font-semibold py-1 bg-[#DBDBDB] rounded-lg">
                            Contacter
                        </div>
                        <span class="bg-[#DBDBDB] rounded-lg px-2 h-9 flex items-center">
                            <svg-icon type="mdi" size="18" color="black" :path="mdiAccountPlus"></svg-icon>
                        </span>
                        <svg-icon class="hidden md:block" type="mdi" size="32" color="white" :path="mdiDotsHorizontal"></svg-icon>
                    </div>
                </div>
                <div class="hidden md:flex gap-10 mt-10">
                    <div>
                        <span class="font-semibold">{{ total_posts }}</span> publications
                    </div>
                    <div>
                        <button @click="loadFollowers"><span class="font-semibold mr-1">{{ followersCount
                        }} </span>followers</button>
                    </div>
                    <div>
                        <button @click="loadFollowing"><span class="font-semibold mr-1">{{ followingCount
                        }} </span>suivi(e)s</button>
                    </div>
                </div>
                
                <div class="mt-10 text-base font-semibold">{{ user.name }}</div>
            </div>
        </div>
        <div class="flex md:hidden w-full justify-evenly gap-10 mt-10 border-[#262626] border-t border-b py-4">
            <div class="flex flex-col">
                <div class="font-semibold text-center ">{{ total_posts }}</div>
                <div class="text-center text-[hsl(0,0%,70%)]">
                    publications
                </div>
            </div>
            <button @click="loadFollowers" class="flex flex-col">
                <div class="font-semibold w-full text-center">
                    {{ followersCount }}
                </div>
                <div class="text-center text-[hsl(0,0%,70%)]">
                    followers
                </div>
            </button>
            <button @click="loadFollowing" class="flex flex-col">
                <div class="font-semibold w-full text-center">
                    {{ followingCount }}
                </div>
                <div class="text-center text-[hsl(0,0%,70%)]">
                    suivi(e)s
                </div>
            </button>
        </div>
    </div>
</template>