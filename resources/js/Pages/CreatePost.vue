<script setup>
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiMultimedia, mdiArrowLeft } from '@mdi/js';
import { ref } from "vue";
defineEmits(['turnOff'])


const props = defineProps({
    "showCreatePost": Boolean
})

const url = ref();

function onFileChange(e) {
    const file = e.target.files[0];
    url.value = URL.createObjectURL(file);
}
</script>

<template>
    <div class="text-white backdrop-brightness-[0.4] w-screen absolute  h-screen z-10 flex justify-center items-center"
        @click="$emit('turnOff')">
    </div>
    <div class="absolute z-10 flex w-screen h-screen justify-center items-center text-white">
        <div class="w-[815px] h-[815px] rounded-xl bg-[#262626] ">
            <div v-if="!url" class="border-b border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold">
                Créer une publication publication
            </div>
            <div v-else
                class="border-b min-w-full flex justify-between border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold px-5">
                <svg-icon class="hover:cursor-pointer" type="mdi" size="26" :path="mdiArrowLeft" />
                <p>Rogner</p>
                <button class="text-[hsl(204,90%,49%)] text-base hover:text-white">Suivant</button>
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
                <img :src="url" />
            </div>
        </div>
    </div>
</template>