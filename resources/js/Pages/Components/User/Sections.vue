<script setup>
import SectionTitle from './SectionTitle.vue'
import { mdiTableLarge, mdiBookmarkOutline, mdiCalendarAccountOutline, mdiYoutube } from '@mdi/js';
import { usePage } from '@inertiajs/vue3'

defineProps({
    active: Number,
    user: Object
})

</script>

<template>
    <div class="col-start-3 w-full md:w-[935px] md:pl-16">
        <div class="flex md:justify-center justify-evenly w-full text-sm md:gap-16 tracking-widest md:mr-[100px]">
            <SectionTitle title="PUBLICATIONS" :path="mdiTableLarge" :active="active" whenActive="0" />
            <SectionTitle title="REELS" value="reels" :path="mdiYoutube" :active="active" whenActive="1" />
            <SectionTitle v-if="user.id == usePage().props.auth.user.id" title="ENREGISTREMENTS" value="bookmarks"
                :path="mdiBookmarkOutline" :active="active" whenActive="2" />
            <SectionTitle title="IDENTIFIÉ(E)" value="mentions" :path="mdiCalendarAccountOutline" :active="active"
                whenActive="3" />
        </div>
    </div>
</template>