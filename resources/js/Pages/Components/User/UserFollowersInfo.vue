<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiAccountPlus, mdiDotsHorizontal } from '@mdi/js';
defineProps({
    total_posts: Number,
    user: Object
})
</script>

<template>
    <div class="col-start-5 w-[935px]  pb-10 border-b border-[#262626] pl-16">
        <div class="flex justify-between">
            <img class="rounded-full" src="https://picsum.photos/seed/picsum/150/150" />
            <div class="w-[70%] text-lg">
                <div class="flex items-center gap-4">
                    <div class="mr-10 text-xl font-semibold">{{ user.name }}</div>
                    <div class="px-6 py-1  bg-[#1877F2] rounded-lg">Suivre</div>
                    <div class="w-[93px] text-center text-black font-semibold py-1 bg-[#DBDBDB] rounded-lg">
                        Contacter
                    </div>
                    <span class="bg-[#DBDBDB] rounded-lg px-2 h-9 flex items-center">
                        <svg-icon type="mdi" size="18" color="black" :path="mdiAccountPlus"></svg-icon>
                    </span>
                    <svg-icon type="mdi" size="32" color="white" :path="mdiDotsHorizontal"></svg-icon>
                </div>
                <div class="flex gap-10 mt-10">
                    <div>
                        <span class="font-semibold">{{ total_posts }}</span> publications
                    </div>
                    <div>
                        <span class="font-semibold">6 M</span> followers
                    </div>
                    <div>
                        <span class="font-semibold">4</span> suivi(e)s
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>