<script setup>
import { mdiMultimedia, mdiArrowLeft, mdiEmoticonHappyOutline } from "@mdi/js";
import { ref } from "vue";
import EmojiPicker from "vue3-emoji-picker";
import CityPicker from "./Profile/CityPicker.vue";
import SvgIcon from "@jamescoyle/vue-icon";
import ExpendableMenu from "./ExpendableMenu.vue";
import InerMenuCheckbox from "./InerMenuCheckbox.vue";
import {router, usePage} from "@inertiajs/vue3";
import ImageFilterApp from "./ImageFilterApp.vue";

const emit = defineEmits(["toggleShowCreatePost"]);

const step = ref(0);
const url = ref();
const file = ref();
const showEmojiPicker = ref(false);
const city = ref("");
const description = ref("");
const imageDescription = ref("");
const showLikes = ref(false);
const showComments = ref(false);

const onFileChange = (e) => {
  file.value = e.target.files[0];
  url.value = URL.createObjectURL(file.value);
};

const leave = () => {
  step.value = 0;
  emit("toggleShowCreatePost");
};

const back = () => {
  if (step.value === 0) leave();
  else step.value--;
};

function onSelectEmoji(emoji) {
  description.value += emoji.i;
  showEmojiPicker.value = false;
}

const updateShowLikes = (value) => {
  showLikes.value = value;
};
const updateShowComments = (value) => {
  showComments.value = value;
};

const updateCity = (value) => {
  city.value = value;
};

const validatePost = () => {
  router.post("/post", {
    description: description.value,
    location: city.value,
    image: file.value.type.includes("image/") ? file.value : null,
    video: file.value.type.includes("image/") ? null : file.value,
    enable_comments: !showComments.value,
    enable_likes: !showLikes.value,
    image_description: imageDescription.value,
  });
  leave();
};

const videoPlayer = ref(null);
const isPlaying = ref(false);

const togglePlayPause = () => {
  if (videoPlayer.value.paused) {
    videoPlayer.value.play();
    isPlaying.value = true;
  } else {
    videoPlayer.value.pause();
    isPlaying.value = false;
  }
};
</script>

<template>
  <div
    class="lg:fixed bg-opacity-40 lg:z-[99] overflow-auto xl:overflow-hidden lg:flex h-full w-screen lg:h-screen justify-center items-center text-white"
  >
    <div
      class="text-white lg:backdrop-brightness-[0.4] xl:overflow-hidden z-10 w-screen absolute hidden h-screen lg:flex justify-center items-center"
      @click="leave"
    ></div>
    <div
      :class="{
        'lg:w-[40vw] lg:h-[85vh] h-full  shrink-0 xl:overflow-hidden overflow-auto lg:z-20 lg:rounded-xl lg:bg-[#262626]':
          step === 0,
        'lg:w-[58vw] lg:max-w-[1200px] lg:max-h-[85vh] lg:z-20 shrink-0 grow rounded-xl lg:bg-[#262626] overflow-auto xl:overflow-hidden ':
          step === 1 || step === 2,
      }"
    >
      <div
        v-if="!url"
        class="border-b border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold"
      >
        Créer une publication
      </div>
      <div v-else>
        <div
          v-if="step === 0"
          class="border-b min-w-full flex justify-between border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold px-5"
        >
          <div>
            <svg-icon
              class="hover:cursor-pointer"
              type="mdi"
              size="26"
              @click="back"
              :path="mdiArrowLeft"
            />
          </div>
          <p>Sélectioner</p>
          <button
            class="text-[hsl(204,90%,49%)] text-base hover:text-white"
            @click="() => step++"
          >
            Suivant
          </button>
        </div>
        <div
          v-if="step === 1"
          class="border-b min-w-full flex justify-between border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold px-5"
        >
          <div>
            <svg-icon
              class="hover:cursor-pointer"
              type="mdi"
              size="26"
              @click="back"
              :path="mdiArrowLeft"
            />
          </div>
          <p>Filtrer</p>
          <button
            class="text-[hsl(204,90%,49%)] text-base hover:text-white"
            @click="() => step++"
          >
            Suivant
          </button>
        </div>
        <div
          v-else-if="step === 2"
          class="border-b min-w-full flex justify-between border-[hsl(0,0%,20%)] py-3 text-center text-lg font-semibold px-5"
        >
          <svg-icon
            class="hover:cursor-pointer"
            type="mdi"
            size="26"
            @click="back"
            :path="mdiArrowLeft"
          />
          <p>Créer une publication</p>
          <button
            class="text-[hsl(204,90%,49%)] text-base hover:text-white"
            @click="validatePost"
          >
            Partager
          </button>
        </div>
      </div>
      <div
        class="flex flex-col gap-4 h-full items-center justify-center text-2xl"
        v-if="!url"
      >
        <svg-icon class="w-64" type="mdi" size="64" :path="mdiMultimedia"></svg-icon>
        <p>Faites glisser les photos et les vidéos ici</p>
        <label for="dropzone-file">
          <div class="flex flex-col items-center justify-center pt-5 pb-6">
            <p
              class="bg-[hsl(204,97%,49%)] hover:bg-[hsl(204,97%,39%)] hover:cursor-pointer py-2 px-4 text-base rounded-lg font-semibold"
            >
              Sélectioner sur l'ordinateur
            </p>
          </div>
          <input id="dropzone-file" type="file" class="hidden" @change="onFileChange" />
        </label>
      </div>
      <div v-else>
        <div class="flex justify-center" v-if="step === 0">
          <img alt="image" v-if="file.type.includes('image/')" class="max-h-[80.5vh]" :src="url" />
          <div v-else class="">
            <video ref="videoPlayer" class="object-cover" @click="togglePlayPause">
              <source :src="url" type="video/mp4" />
              Your browser does not support the video tag.
            </video>
            <div class="play-button" v-if="!isPlaying" @click="togglePlayPause"></div>
          </div>
        </div>
        <div v-else>
          <div v-if="step === 1">
            <div class="col-span-2 flex justify-center">
              <ImageFilterApp
                v-if="file.type.includes('image/')"
                :url="url"
                class="lg:bg-[#262626]"
              />
              <div class="video-container" v-else>
                <video ref="videoPlayer" @click="togglePlayPause">
                  <source :src="url" type="video/mp4" />
                  Your browser does not support the video tag.
                </video>
                <div class="play-button" v-if="!isPlaying" @click="togglePlayPause"></div>
              </div>
            </div>
          </div>
          <Transition
            v-else
            enter-from-class="scale-x-0"
            enter-leave-class="scale-x-100"
            enter-active-class="transition duration-1000 origin-left grid grid-cols-3"
          >
            <div
              v-if="step === 2"
              class="lg:border-l-[1px] border-[hsl(0,0%,20%)] flex flex-col gap-4 lg:flex-row lg:h-[81vh]"
            >
              <img
                class="lg:max-w-[70%]"
                v-if="file.type.includes('image/')"
                :src="url"
                alt="image"
              />
              <div class="video-container" v-else>
                <video ref="videoPlayer" class="object-cover" @click="togglePlayPause">
                  <source :src="url" type="video/mp4" />
                  Your browser does not support the video tag.
                </video>
                <div class="play-button" v-if="!isPlaying" @click="togglePlayPause"></div>
              </div>
              <div
                class="flex w-full lg:mt-4 flex-col px-4 lg:mx-0 mb-4 lg:mb-0 h-full gap-6"
              >
                <div>
                  <div class="text-lg lg:text-base flex gap-3 mb-3 items-center">
                    <div class="h-12 w-12 rounded-full overflow-hidden">
                        <img
                            alt="image"
                            class="rounded-full"
                            :src="usePage().props.auth.user.avatar"
                        />
                    </div>
                    <p>{{ usePage().props.auth.user.username }}</p>
                  </div>
                  <textarea
                    class="bg-[#262626] w-full resize-none overflow-auto border-transparent focus:border-transparent focus:ring-0"
                    placeholder="Ajouter une légende"
                    id=""
                    cols="30"
                    rows="8"
                    maxlength="2200"
                    v-model="description"
                  />
                </div>
                <div class="flex justify-between relative">
                  <svg-icon
                    class="hover:cursor-pointer"
                    type="mdi"
                    size="24"
                    @click="showEmojiPicker = !showEmojiPicker"
                    :path="mdiEmoticonHappyOutline"
                  />
                  <EmojiPicker
                    class="absolute left-10 lg:left-0 top-0 lg:top-10 z-50"
                    v-if="showEmojiPicker"
                    :native="true"
                    @select="onSelectEmoji"
                  />
                  <p class="text-gray-500">{{ description?.length }}/2200</p>
                </div>
                <CityPicker @updateCity="updateCity" class="min-w-[85%] lg:z-20" />
                <ExpendableMenu title="Accessibilité">
                  <p class="text-gray-400 text-lg">
                    Le texte alternatif décrit vos photos pour les personnes malvoyantes.
                    Il est généré automatiquement pour vos photos, mais vous pouvez
                    choisir de l'écrire vous-même.
                  </p>
                  <div class="flex gap-4 mt-3 items-center">
                    <img alt="image" class="w-14 h-14" :src="url" />
                    <input
                      type="text"
                      class="bg-transparent border-none rounded-lg focus:outline w-full focus:outline-[hsl(0,0%,23%)] focus:border-2 focus:ring-0"
                      placeholder="Ecrivez un texte alternatif..."
                      v-model="imageDescription"
                    />
                  </div>
                </ExpendableMenu>
                <ExpendableMenu title="Paramètre avancés">
                  <InerMenuCheckbox
                    @updateValue="updateShowLikes"
                    title="Masquer le nombre de J'aime et de vues sur cette publication"
                  >
                    <p class="text-lg text-gray-400 mb-5">
                      Vous êtes la seule personne à pouvoir voir le nombre total de J'aime
                      et de vues sur cette publication. Vous pourrez modifier ceci plus
                      tard via le menu ⋮ présent en haut de la publication. Pour masquer
                      le nombre de J'aime sur les publications d'autres personnes, accédez
                      aux paramètres de votre compte.
                      <span class="text-white hover:cursor-pointer hover:underline">
                        En savoir plus
                      </span>
                    </p>
                  </InerMenuCheckbox>

                  <InerMenuCheckbox
                    @updateValue="updateShowComments"
                    title="Désactivez les commentaires"
                  >
                    <p class="text-lg text-gray-400 lg:mb-4 xl:mb-0">
                      Vous pourrez modifier ce paramètre plus tard dans ··· en haut de
                      votre publication.
                    </p>
                  </InerMenuCheckbox>
                </ExpendableMenu>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.video-container {
  position: relative;
  width: 100%;
  height: 100%;
}

.video-container video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.play-button {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 80px;
  height: 80px;
  background-color: rgba(0, 0, 0, 0.7);
  border-radius: 50%;
  cursor: pointer;
}

.play-button::before {
  content: "";
  position: absolute;
  top: calc(50% - 0.5em);
  left: calc(50% - 0.5em);
  width: 0.5em;
  height: 0.5em;
  border-style: solid;
  border-width: 0.5em 0.5em 0.5em 0;
}
</style>
