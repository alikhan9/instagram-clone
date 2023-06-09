<script setup>
import { mdiMultimedia, mdiArrowLeft, mdiEmoticonHappyOutline, mdiChevronDown, mdiChevronUp } from '@mdi/js';
import { ref } from "vue";
import EmojiPicker from 'vue3-emoji-picker'
import CityPicker from './Profile/CityPicker.vue';
import SvgIcon from '@jamescoyle/vue-icon';
import ExpendableMenu from './ExpendableMenu.vue';
import InerMenuCheckbox from './InerMenuCheckbox.vue';

const emit = defineEmits(['update:showCreatePost']);

const props = defineProps({
    "showCreatePost": Boolean
})

const url = ref();
const step = ref(0);
const input = ref('');
const showEmojiPicker = ref(false);



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


function onSelectEmoji(emoji) {
    input.value += emoji.i;
    showEmojiPicker.value = false;
    /*
      // result
      { 
          i: "😚", 
          n: ["kissing face"], 
          r: "1f61a", // with skin tone
          t: "neutral", // skin tone
          u: "1f61a" // without tone
      }
      */
}

</script>

<template>
    <div class="absolute z-30 flex w-screen h-screen justify-center items-center text-white">
        <div class="text-white backdrop-brightness-[0.4] z-10 w-screen absolute  h-screen flex justify-center items-center"
            @click="leave">
        </div>
        <div :class="{
                'w-[40vw] h-[85vh] z-20 rounded-xl bg-[#262626]': step == 0,
                'w-[58vw] h-[85vh] z-20 rounded-xl bg-[#262626] overflow-hidden': step == 1
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
                    <div class="p-5 border-l-[1px] border-[hsl(0,0%,20%)] flex flex-col h-[81vh]">
                        <div>
                            <div class="flex gap-3 mb-3 items-center">
                                <img class="rounded-full" src="https://picsum.photos/seed/picsum/32/32" />
                                <p>Name</p>
                            </div>
                            <textarea
                                class="bg-[#262626] w-full resize-none overflow-auto border-transparent focus:border-transparent focus:ring-0"
                                placeholder="Ajouter une légende" id="" cols="30" rows="8" maxlength="2200"
                                v-model="input" />
                        </div>
                        <div class="flex justify-between relative">
                            <svg-icon class="hover:cursor-pointer" type="mdi" size="24"
                                @click="showEmojiPicker = !showEmojiPicker" :path="mdiEmoticonHappyOutline" />
                            <EmojiPicker class="absolute top-10 z-10" v-if="showEmojiPicker" :native="true"
                                @select="onSelectEmoji" />
                            <p class="text-gray-500">{{ input?.length }}/2200</p>
                        </div>
                        <div class="flex flex-col gap-8">
                            <CityPicker class="min-w-[85%]" />
                            <ExpendableMenu title="Accessibilité">
                                <p class="text-gray-400 text-sm">
                                    Le texte alternatif décrit vos photos pour les personnes malvoyantes.
                                    Il est généré automatiquement pour vos photos, mais vous pouvez choisir de l'écrire
                                    vous-même.
                                </p>
                                <div class="flex gap-4 mt-3 items-center">
                                    <img class="w-14 h-14" :src="url" />
                                    <input type="text"
                                        class="bg-transparent border-none rounded-sm focus:outline w-full focus:outline-[hsl(0,0%,23%)] focus:border-2 focus:ring-0"
                                        placeholder="Ecrivez un texte alternatif...">
                                </div>
                            </ExpendableMenu>
                            <ExpendableMenu title="Paramètre avancés">
                                <InerMenuCheckbox title="Masquer le nombre de J'aime et de vues sur cette publication">
                                    <p class="text-sm text-gray-400 mb-5">
                                        Vous êtes la seule personne à pouvoir voir le nombre total de J'aime et de vues sur
                                        cette publication.
                                        Vous pourrez modifier ceci plus tard via le menu ⋮ présent en haut de la
                                        publication.
                                        Pour masquer le nombre de J'aime sur les publications d'autres personnes, accédez
                                        aux
                                        paramètres de votre compte. <span
                                            class="text-white hover:cursor-pointer hover:underline">En savoir
                                            plus</span>
                                    </p>
                                </InerMenuCheckbox>

                                <InerMenuCheckbox title="Désactivez les commentaires">
                                    <p class="text-sm text-gray-400">
                                        Vous pourrez modifier ce paramètre plus tard dans le menu ··· en haut de votre
                                        publication.
                                    </p>
                                </InerMenuCheckbox>
                            </ExpendableMenu>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>