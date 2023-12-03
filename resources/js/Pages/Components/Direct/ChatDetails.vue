<script setup>
import SvgIcon from '@jamescoyle/vue-icon'
import {
    mdiPencilBoxOutline,
    mdiArrowLeft,
    mdiInformationSlabCircleOutline,
} from '@mdi/js'
const props = defineProps({
    receiver: Object,
    group: Object,
})

const emit = defineEmits(['update:showDetails'])

const close = () => emit('update:showDetails', false)
</script>

<template>
    <div
        class="fixed left-0 top-0 z-50 flex h-screen w-screen flex-col border-[#262626] bg-black lg:relative lg:h-full lg:w-[400px] lg:min-w-[250px] lg:shrink lg:border-l"
    >
        <div class="border-b border-[#262626] py-10 px-[16px]">
            <div class="mb-6 flex items-center gap-3">
                <div
                    class="h-full hover:cursor-pointer lg:hidden"
                    @click="close"
                >
                    <svg-icon
                        type="mdi"
                        :path="mdiArrowLeft"
                        size="28"
                    ></svg-icon>
                </div>
                <div class="text-2xl font-semibold lg:text-xl">Détails</div>
            </div>
            <div class="flex justify-between">
                <div>Mettre en sourdine les messages</div>
                <label
                    class="switch shrink-0"
                    for="checkbox"
                >
                    <input
                        type="checkbox"
                        id="checkbox"
                    />
                    <div class="slider round"></div>
                </label>
            </div>
        </div>
        <div
            class="mb-10 h-full w-full flex-1 overflow-auto border-b border-[#262626]"
        >
            <div class="text-xl font-semibold px-[16px]">Membres</div>
            <div class="mt-4 flex items-center gap-4">
                <a
                    v-if="receiver"
                    class="hover:bg flex items-center gap-3 px-[16px]"
                    :href="'/profile/' + receiver.username"
                    target="_blank"
                >
                    <div class="h-11 w-11 overflow-hidden rounded-full">
                        <img
                            class="h-full w-full"
                            src="https://picsum.photos/seed/picsum/44/44"
                        />
                    </div>
                    <div>
                        <div class="text-[16px] font-semibold">
                            {{ receiver.name }}
                        </div>
                        <div
                            class="text-[12px] font-semibold text-[hsl(0,0%,70%)]"
                        >
                            {{ receiver.username }}
                        </div>
                    </div>
                </a>
                <div class="w-full" v-else>
                    <a
                        v-for="(member, index) in group.members"
                        :key="index"
                        class="hover:bg py-4 hover:bg-[hsl(0,0%,15%)] px-[16px] w-full flex items-center gap-3"
                        :href="'/profile/' + member.user.username"
                        target="_blank"
                    >
                        <div class="h-11 w-11 overflow-hidden rounded-full">
                            <img
                                class="h-full w-full"
                                src="https://picsum.photos/seed/picsum/44/44"
                            />
                        </div>
                        <div>
                            <div class="text-[16px] font-semibold">
                                {{ member.user.name }}
                            </div>
                            <div
                                class="text-[12px] font-semibold text-[hsl(0,0%,70%)]"
                            >
                                {{ member.user.username }}
                            </div>
                        </div>
                    </a>
                    
                </div>
            </div>
        </div>
        <div
            class="flex  px-[16px] w-full shrink-0 flex-col gap-10 border-b border-[#262626] pb-10 text-[#ed4a58]"
        >
            <div class="hover:cursor-pointer">Signaler</div>
            <div class="hover:cursor-pointer">Bloquer</div>
            <div class="hover:cursor-pointer">Supprimer la discussion</div>
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
    transition: 0.4s;
}

.slider:before {
    background-color: hsl(0, 3%, 6%);
    bottom: 2px;
    content: '';
    height: 19px;
    left: 4px;
    position: absolute;
    transition: 0.4s;
    width: 19px;
}

input:checked + .slider {
    background-color: #ffffff;
}

input:checked + .slider:before {
    transform: translateX(14px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}
</style>
