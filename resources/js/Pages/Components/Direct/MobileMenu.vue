<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiArrowLeft, mdiInformationSlabCircleOutline } from '@mdi/js';


defineProps({
    receiver: Object,
    toggleChat: Function,
    toggleShowDetails: Function,
})

</script>

<template>
    <div class="w-full lg:hidden">
        <div class="justify-between flex px-4 items-center border-b py-1 border-[#262626]">
            <div class="flex items-center gap-4">
                <div @click="toggleChat" class="lg:hidden hover:cursor-pointer">
                    <svg-icon type="mdi" :path="mdiArrowLeft" size="28"></svg-icon>
                </div>
                <a class="flex items-center gap-3" :href="'/profile/' + receiver.username" target="_blank">
                    <div class="overflow-hidden rounded-full w-7 h-7">
                        <img class="w-full h-full" src="https://picsum.photos/seed/picsum/32/32" />
                    </div>
                    <div>
                        <div class="font-semibold text-[16px]"> {{ receiver.username }}
                        </div>
                        <div class="font-semibold text-[12px] text-[hsl(0,0%,70%)]">En ligne il y a 17min</div>
                    </div>
                </a>
            </div>
            <div>
                <svg-icon @click="toggleShowDetails" class="hover:cursor-pointer" size="26" type="mdi"
                    :path="mdiInformationSlabCircleOutline"></svg-icon>
            </div>
        </div>
    </div>
</template>