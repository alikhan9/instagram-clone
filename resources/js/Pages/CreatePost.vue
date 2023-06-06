<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiMultimedia, mdiArrowLeft } from '@mdi/js';
import { ref } from "vue";
const emit = defineEmits(['update:showCreatePost']);


const props = defineProps({
    "showCreatePost": Boolean
})

const url = ref();
const step = ref(0);


const onFileChange = (e) => {
    const file = e.target.files[0];
    url.value = URL.createObjectURL(file);
}

const leave = () => {
    step.value = 0;
    emit("update:showCreatePost", false);
}

const back = () => {
    if (step.value == 0)
        leave();
    else
        step.value--;
}

</script>

<template>
    <div class="absolute z-30 flex w-screen h-screen justify-center items-center text-white">
        <div class="text-white backdrop-brightness-[0.4] z-10 w-screen absolute  h-screen flex justify-center items-center"
            @click="leave">
        </div>
        <div :class="{
                'w-[40vw] h-[85vh] z-20 rounded-xl bg-[#262626]': step == 0,
                'w-[58vw] h-[85vh] z-20 rounded-xl bg-[#262626]': step == 1
            }">
            <div v-if="!url" class="border-b border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold">
                Créer une publication
            </div>
            <div v-else>
                <div v-if="step == 0"
                    class="border-b min-w-full flex justify-between border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold px-5">
                    <svg-icon class="hover:cursor-pointer" type="mdi" size="26" @click="back" :path="mdiArrowLeft" />
                    <p>Rogner</p>
                    <button class="text-[hsl(204,90%,49%)] text-base hover:text-white" @click="step++">Suivant</button>
                </div>
                <div v-else
                    class="border-b min-w-full flex justify-between border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold px-5">
                    <svg-icon class="hover:cursor-pointer" type="mdi" size="26" @click="back" :path="mdiArrowLeft" />
                    <p>Créer une publication</p>
                    <button class="text-[hsl(204,90%,49%)] text-base hover:text-white">Partager</button>
                </div>

            </div>
            <div class="flex flex-col gap-4 h-full items-center justify-center text-2xl " v-if="!url">
                <svg-icon class="w-64" type="mdi" size="64" :path="mdiMultimedia"></svg-icon>
                <p> Faites glisser les photos et les vidéos ici</p>
                <label for="dropzone-file">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <p
                            class="bg-[hsl(204,97%,49%)] hover:bg-[hsl(204,97%,39%)] hover:cursor-pointer py-2 px-4 text-base rounded-lg font-semibold">
                            Sélectioner sur l'ordinateur
                        </p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" @change="onFileChange" />

                </label>
            </div>
            <div v-else>
                <img v-if="step == 0" :src="url" />
                <div v-else class="grid grid-cols-3 ">
                    <img class="col-span-2" :src="url" />
                    <div class="p-5">
                        <div class="flex gap-3 mb-3 items-center">
                            <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                            <p>Name</p>
                        </div>
                        <textarea name=""
                            class="bg-[#262626] w-full resize-none overflow-auto border-transparent focus:border-transparent focus:ring-0"
                            placeholder="Ajouter une légende" id="" cols="30" rows="10"></textarea>
                    </div>
                    <div class="relative inline-block">
                        <textarea class="px-2 py-4 rounded border outline-none focus:shadow-outline w-128 h-48"
                            v-model="input"></textarea>

                        <emoji-picker>
                            <!-- <div class="absolute pin-t pin-r p-2 cursor-pointer emoji-invoker outline-none"
                                slot="emoji-invoker" slot-scope="{ events: { click: clickEvent } }" @click="clickEvent">
                                <button class="focus:outline-none h-6 w-6 focus:shadow-outline rounded-full">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 fill-current text-grey">
                                        <path d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                                    </svg>
                                </button>
                            </div>
                            <div slot="emoji-picker" slot-scope="{ emojis }">
                                <div
                                    class="absolute z-10 border w-64 h-96 overflow-scroll p-4 rounded bg-white shadow t-4 -r-64">
                                    <div class="flex">
                                        <input
                                            class="rounded-full border py-2 px-4 outline-none focus:shadow-outline w-full"
                                            type="text" v-model="search" v-focus>
                                    </div>
                                    <div>
                                        <div v-for="(emojiGroup, category) in emojis" :key="category">
                                            <h5 class="text-grey-darker uppercase text-sm cursor-default mb-2 mt-4">{{
                                                category }}</h5>
                                            <div class="flex flex-wrap justify-between emojis">
                                                <button
                                                    class="p-1 cursor-pointer rounded hover:bg-grey-light focus:outline-none focus:shadow-outline flex items-center justify-center h-6 w-6"
                                                    v-for="(emoji, emojiName) in emojiGroup" :key="emojiName"
                                                    @click="append(emoji)" :title="emojiName">{{ emoji }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </emoji-picker>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>