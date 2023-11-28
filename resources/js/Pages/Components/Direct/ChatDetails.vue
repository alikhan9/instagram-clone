<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiPencilBoxOutline, mdiArrowLeft, mdiInformationSlabCircleOutline } from '@mdi/js';
const props = defineProps({
    receiver: Object
})

const emit = defineEmits(['update:showDetails']);

const close = () => emit('update:showDetails', false);

</script>


<template>
    <div
        class="bg-black lg:border-l z-50 border-[#262626] fixed lg:relative top-0 left-0 h-screen w-screen lg:w-[400px] lg:shrink lg:min-w-[250px] lg:h-full flex [&>div]:px-[16px] flex-col">
        <div class="border-b border-[#262626] py-10">
            <div class="flex items-center mb-6 gap-3">
                <div class="lg:hidden h-full hover:cursor-pointer" @click="close">
                    <svg-icon type="mdi" :path="mdiArrowLeft" size="28"></svg-icon>
                </div>
                <div class="font-semibold text-2xl lg:text-xl">
                    Détails
                </div>
            </div>
            <div class="flex justify-between">
                <div>
                    Mettre en sourdine les messages
                </div>
                <label class="switch shrink-0" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <div class="slider round"></div>
                </label>
            </div>
        </div>
        <div class="flex-1 h-full w-full overflow-auto border-b mb-10 border-[#262626]">
            <div class="text-xl font-semibold">Membres</div>
            <div class="flex items-center mt-4 gap-4">
                <a class="flex gap-3 items-center hover:bg" :href="'/profile/' + receiver.username" target="_blank">
                    <div class="w-11 h-11 rounded-full overflow-hidden">
                        <img class="w-full h-full" src="https://picsum.photos/seed/picsum/44/44" />
                    </div>
                    <div>
                        <div class="font-semibold text-[16px]"> {{ receiver.name }}
                        </div>
                        <div class="font-semibold text-[12px] text-[hsl(0,0%,70%)]">{{ receiver.username }}</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="shrink-0 text-[#ed4a58] flex flex-col gap-10 w-full border-b pb-10 border-[#262626]">
            <div class="hover:cursor-pointer">
                Signaler
            </div>
            <div class="hover:cursor-pointer">
                Bloquer
            </div>
            <div class="hover:cursor-pointer">
                Supprimer la discussion
            </div>
        </div>
    </div>
</template>

<style>
.switch {
    display: inline-block;
    height: 24px;
    position: relative;
    width: 40px;
}

.switch input {
    display: none;
}

.slider {
    background-color: hsl(0, 3%, 12%);
    bottom: 0;
    cursor: pointer;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    transition: .4s;
}

.slider:before {
    background-color: hsl(0, 3%, 6%);
    bottom: 2px;
    content: "";
    height: 19px;
    left: 4px;
    position: absolute;
    transition: .4s;
    width: 19px;
}

input:checked+.slider {
    background-color: #ffffff;
}

input:checked+.slider:before {
    transform: translateX(14px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}
</style>