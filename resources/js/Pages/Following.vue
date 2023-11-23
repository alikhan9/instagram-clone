<script setup>

import { onClickOutside } from '@vueuse/core';
import { ref } from 'vue'
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiClose, mdiArrowLeft } from "@mdi/js";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    following: Object,
    isFollowers: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['closeFollowing']);
const target = ref();

const closeFollowers = () => {
    emit('closeFollowing');
}

onClickOutside(target, (event) => closeFollowers());

const sendFollow = (id) => {
    axios.post('/follow/' + id)
        .then(res => {
            props.following.filter(u => u.id == id)[0].followedByUser = res.data;
        });
}

</script>

<template>
    <div class="w-full h-full z-[99] backdrop-brightness-50 absolute lg:flex justify-center items-center">
        <div class="lg:w-[440px] lg:h-[440px] h-full w-full lg:p-8">
            <div ref="target" class="bg-[hsl(0,0%,15%)] flex flex-col lg:block h-full lg:rounded-xl">
                <div
                    class="text-white shrink-0 text-center relative flex justify-between px-4 border-b gap-8 py-5 border-[hsl(0,0%,25%)]">
                    <div class=" hidden lg:block">
                        <svg-icon class="hover:cursor-pointer" size="28" @click="closeFollowers" type="mdi"
                            :path="mdiClose"></svg-icon>
                    </div>
                    <div class="lg:hidden">
                        <svg-icon size="30" @click="closeFollowers" class="hover:cursor-pointer" type="mdi"
                            :path="mdiArrowLeft"></svg-icon>
                    </div>
                    <div class="text-center font-semibold text-lg">
                        {{ isFollowers ? 'Followers' : 'Following' }}
                    </div>
                    <div></div>
                </div>
                <div class="p-4 flex-1 overflow-auto h-full lg:max-h-[380px]">
                    <div v-for="(u, index) in following" :key="index" class="flex justify-between text-white">
                        <div class="flex gap-3 mb-4">
                            <div>
                                <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                            </div>
                            <div>
                                <Link class="font-semibold" :href="'/profile/' + u.username">{{ u.username }}</Link>
                                <div class="font-semibold text-[hsl(0,0%,60%)]">{{ u.name }}</div>
                            </div>
                        </div>
                        <button v-if="!u.followedByUser" @click="sendFollow(u.id)"
                            class="bg-blue-400  w-36 h-10 rounded-xl">Suivre</button>
                        <button v-else @click="sendFollow(u.id)" class="bg-red-400 w-36 h-10 rounded-xl">Ne plus
                            suivre</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>