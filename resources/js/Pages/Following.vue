<script setup>

import { onClickOutside } from '@vueuse/core';
import { ref } from 'vue'
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiClose } from "@mdi/js";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    following: Object,
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
    <Teleport to="body">
        <div class="w-screen h-screen top-0 backdrop-brightness-50 z-[99] absolute flex justify-center items-center">
            <div class="w-[440px] h-[440px] p-8">
                <div ref="target" class="bg-[hsl(0,0%,15%)] rounded-xl">
                    <div
                        class="text-white text-center relative flex justify-center border-b gap-8 py-5 border-[hsl(0,0%,25%)]">
                        <div class="px-6 absolute self-start -right-4 ">
                            <svg-icon class="hover:cursor-pointer" size="28" @click="() => emit('closeFollowers')"
                                type="mdi" :path="mdiClose"></svg-icon>
                        </div>
                        <div class="text-center font-semibold text-lg">
                            Following
                        </div>
                    </div>
                    <div class="p-4">
                        <div v-for="(u, index) in following" :key="index" class="flex justify-between text-white ">
                            <div class="flex gap-3 mb-4">
                                <div>
                                    <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                                </div>
                                <div>
                                    <Link class="font-semibold" :href="'/profile/' + u.username">{{ u.username }}</Link>
                                    <div class="font-semibold text-[hsl(0,0%,60%)]">{{ u.name }}</div>
                                </div>
                            </div>
                            <button v-if="!u.followedByUser" @click="sendFollow(u.id)" class="text-blue-400">Suivre</button>
                            <button v-else @click="sendFollow(u.id)" class="text-red-400">Ne plus suivre</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>