<script setup>
import { onClickOutside } from '@vueuse/core';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiHeart, mdiBookmark } from '@mdi/js';

const emit = defineEmits(['update:showComments']);
const props = defineProps({
    post: Object,
    like: Boolean,
    bookmark: Boolean,
    likeUnlikePost: Function,
    bookmarkPost: Function
});


const target = ref(null);
onClickOutside(target, () => emit('update:showComments', false));

</script>

<template>
    <div class="fixed  backdrop-brightness-[0.4] flex justify-center top-0 right-0 items-center  w-screen h-screen">
        <div ref="target">
            <div class="h-[90vh] min-w-[50vw] flex bg-black ">
                <img class="max-w-[800px] ml-2" :src="usePage().props.ziggy.url + post.image">
                <div class="w-[500px] border-l border-[#262626] ">
                    <div class="flex justify-between border-b items-center py-5 border-[#262626]">
                        <div class="flex gap-3 px-6 items-center w-full ">
                            <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                            <div>{{ post.user.name }}</div>
                        </div>
                        <div class="px-6">
                            <unicon class="hover:cursor-pointer" name="ellipsis-h" fill="white"></unicon>
                        </div>
                    </div>
                    <div class="flex justify-between border-b items-center py-5 h-[80%] border-[#262626]">
                        <div class="flex gap-3" v-for="(comment, index) in post.comments" :key="index">
                            <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                            <div>{{ comment.user.name }}</div>
                            <div>{{ comment.content }}</div>
                        </div>
                    </div>
                    <div class="border-b border-[#262626] py-5">
                        <div class=" px-6 flex flex-row justify-between">
                            <div class="flex gap-3">
                                <svg-icon v-if="like" class="w-7 h-7 hover:cursor-pointer animate-heart " type="mdi"
                                    color="red" @click="likeUnlikePost(post.id)" :path="mdiHeart" />
                                <div v-else class="h-7" @click="likeUnlikePost(post.id)">
                                    <unicon class="w-7 h-7 hover:cursor-pointer" name="heart" fill="white" />
                                </div>
                                <unicon class="w-7 h-7" name="comment" fill="white"></unicon>
                                <unicon class="w-7 h-7" name="telegram-alt" fill="white"></unicon>
                            </div>
                            <div>
                                <div class="h-7" v-if="!bookmark" @click="bookmarkPost">
                                    <unicon class="w-7 h-7 hover:cursor-pointer" name="bookmark" fill="white"></unicon>
                                </div>
                                <svg-icon v-else="like" class="w-7 h-7 hover:cursor-pointer" type="mdi" color="white"
                                    @click="bookmarkPost" :path="mdiBookmark" />
                            </div>
                        </div>
                        <div class="px-6 mt-5">
                            <p class="text-xl"> {{ post.likes.length }} J'aime</p>
                            <p class="text-sm text-[hsl(0,0%,30%)]">{{ post.created_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>