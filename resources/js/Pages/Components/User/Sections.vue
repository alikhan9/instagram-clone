<script setup>
import SectionTitle from './SectionTitle.vue'
import { mdiTableLarge, mdiBookmarkOutline, mdiCalendarAccountOutline,mdiYoutube  } from '@mdi/js';

defineProps({
    active: Number
})
</script>

<template>
    <div class="col-start-5 w-[935px] pl-16">
        <div class="flex justify-center text-sm gap-16 tracking-widest mr-[100px]">
            <SectionTitle title="PUBLICATIONS" :path="mdiTableLarge" :active="active" whenActive="0" />
            <SectionTitle title="REELS" :path="mdiYoutube" :active="active" whenActive="1" />
            <SectionTitle title="ENREGISTREMENTS" value="bookmarks" :path="mdiBookmarkOutline" :active="active" whenActive="2" />
            <SectionTitle title="IDENTIFIÉ(E)" :path="mdiCalendarAccountOutline" :active="active" whenActive="3" />
        </div>
    </div>
</template>