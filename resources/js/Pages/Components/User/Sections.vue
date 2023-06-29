<script setup>
import SectionTitle from './SectionTitle.vue'
import { mdiTableLarge, mdiBookmarkOutline, mdiCalendarAccountOutline } from '@mdi/js';
import SvgIcon from '@jamescoyle/vue-icon';

defineProps({
    active: String
})
</script>

<template>
    <div class="col-start-5 w-[935px] pl-16">
        <div class="flex justify-center text-sm gap-16 tracking-widest mr-[100px]">
            <SectionTitle title="PUBLICATIONS" :path="mdiTableLarge" :active="active" whenActive="0" />
            <SectionTitle title="ENREGISTREMENTS" :path="mdiBookmarkOutline" :active="active" whenActive="1" />
            <SectionTitle title="IDENTIFIÉ(E)" :path="mdiCalendarAccountOutline" :active="active" whenActive="2" />
        </div>
    </div>
</template>