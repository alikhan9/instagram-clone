<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import Reel from './Reel.vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { usePostStore } from './useStore/usePostStore';
import Carousel from 'primevue/carousel';


const landmark = ref(null);
const posts = usePostStore();
const currentPage = ref(0);
const stopVideo = ref(false);
const urlFollowed = ref('');

const value = ref(usePage().props['posts']);

onMounted(() => {
    if (props.followed)
        urlFollowed.value = '?followed=true';
    setTimeout(() => {
        if (posts.getValue().length > 0)
            window.history.replaceState({}, '', '/reels/' + posts.getValue()[0].id + '/' + urlFollowed.value);
    }, 10)
})

watch(() => usePage().props['posts'], newValue => {
    value.value = newValue;
})

const loadMoreData = () => {
    if (!value.value.next_page_url)
        return;
    router.get(value.value.next_page_url, { notFirst: true }, {
        preserveScroll: true,
        preserveState: true,
        replace: false,
        only: ['posts'],
        onFinish: () => {
            window.history.replaceState({}, '', '/reels/' + posts.getValue()[currentPage.value].id + '/' + urlFollowed.value);
            if (value.value.data.length > 0)
                posts.addPost(...value.value.data);
        }
    });
}

function handleMouseScroll(event) {
    if (posts.getValue().length == 0)
        return;
    const delta = Math.sign(event.deltaY);
    // Adjust the current page based on scroll direction
    if (delta > 0) {
        // Scroll down, move to the next page
        if (currentPage.value != posts.getValue().length - 1) {
            stopVideo.value = !stopVideo.value;
            currentPage.value = currentPage.value + 1;
            window.history.replaceState({}, '', '/reels/' + posts.getValue()[currentPage.value].id + '/' + urlFollowed.value);
            if (currentPage.value == posts.getValue().length - 1) {
                loadMoreData();
            }
        }

    } else if (delta < 0) {
        // Scroll up, move to the previous page
        if (currentPage.value != 0) {
            currentPage.value = currentPage.value - 1;
            window.history.replaceState({}, '', '/reels/' + posts.getValue()[currentPage.value].id + '/' + urlFollowed.value);
            stopVideo.value = !stopVideo.value;
        }
    }
}

const props = defineProps({
    followed: Boolean,
})

posts.setPosts(usePage().props['posts'].data);


</script>

<template>
    <Head title="Home" />
    <div id="reel">
        <div class="flex gap-4 border-b border-[#262626] font-bold mx-20 pt-12 text-lg pb-3">
            <Link href="/reels" :class="{ 'text-white ': !followed, 'text-[#868686]': followed }">
            Suggestions
            </Link>
            <Link href="/reels" :data="{ followed: true }" :class="{ 'text-white': followed, 'text-[#868686]': !followed }">
            Suivi(e)
            </Link>
        </div>
        <div @wheel="handleMouseScroll" class="text-white  h-full overflow-auto max-h-screen">
            <div class="w-full mt-4 flex justify-center">
                <div class="2xl:w-[600px] w-[500px]">
                    <Carousel ref="carousel" :page.sync="currentPage" :showNavigators="false" :value="posts.getValue()"
                        :numVisible="1" :numScroll="1" orientation="vertical" verticalViewPortHeight="88vh"
                       >
                        <template #item="{ data, index }">
                            <Reel :stopVideo="stopVideo" :followed="followed"
                                v-if="data && index > currentPage - 2 && index < currentPage + 1" :post="data" />
                        </template>
                    </Carousel>
                </div>
            </div>
        </div>
    </div>
</template>
