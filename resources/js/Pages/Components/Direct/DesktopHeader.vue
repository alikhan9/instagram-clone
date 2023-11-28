<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiInformationSlabCircleOutline } from '@mdi/js';

defineProps({
    receiver: Object,
    toggleShowDetails: Function,
})

</script>

<template>
    <div class="justify-between hidden lg:flex px-4 items-center border-b py-5 border-[#262626]">
        <div class="flex items-center gap-4">
            <a class="flex items-center gap-3" :href="'/profile/' + receiver.username" target="_blank">
                <div class="overflow-hidden rounded-full w-11 h-11">
                    <img class="w-full h-full" src="https://picsum.photos/seed/picsum/44/44" />
                </div>
                <div>
                    <div class="font-semibold text-[16px]"> {{ receiver.username }}
                    </div>
                    <div class="font-semibold text-[12px] text-[hsl(0,0%,70%)]">En ligne il y a 17min</div>
                </div>
            </a>
        </div>
        <div>
            <svg-icon size="28" @click="toggleShowDetails" class="hover:cursor-pointer" type="mdi"
                :path="mdiInformationSlabCircleOutline"></svg-icon>
        </div>
    </div>
</template>