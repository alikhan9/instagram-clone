<script setup>
import RecipientInfo from './RecipientInfo.vue';
import DesktopHeader from './DesktopHeader.vue';
import EmojiPicker from "vue3-emoji-picker";
import { mdiEmoticonHappyOutline } from "@mdi/js";
import { ref } from 'vue'
import SvgIcon from "@jamescoyle/vue-icon";


defineProps({
    receiver: Object,
    showChat: Boolean,
    toggleShowDetails: Function,
    messages: Array,
})

const showEmojiPicker = ref(false);
const currentMessage = ref('');

const before = index => {
    if (index == 0 && message.lenght == 1)
        return 'solo';
    if (index == 0 && message.lenght == 2)
        return 'first';
    if (index == message.lenght)
        return 'last';
    return 'in between';
}

const onSelectEmoji = (emoji) => {
    currentMessage.value += emoji.i;
    showEmojiPicker.value = false;
};

const resize = (e) => {
    e.target.style.height = "auto";
    e.target.style.height = `${e.target.scrollHeight - 32}px`;
};

</script>

<template>
    <div :class="{ 'w-full lg:max-h-screen overflow-auto h-full relative flex-col': true, 'hidden lg:block ': !showChat }">
        <DesktopHeader :toggleShowDetails="toggleShowDetails" :receiver="receiver" />
        <RecipientInfo :receiver="receiver" />
        <!-- FIXME: Hauteure max dépasse  -->
        <div v-for="(item, index) in 10" :key="index" class="flex flex-col flex-1 w-full overflow-auto gap-1 px-2 mt-10 lg:px-4">
            <span class="bg-[#3897f0] self-end rounded-lg px-2 py-[8px] max-w-[clamp(50%,564px,60%)]">
                1 comment
            </span>
            <span class="bg-[#3897f0] self-end rounded-[18px] rounded-br-[4px] px-2 py-[8px] max-w-[clamp(50%,564px,60%)]">
                1er comment
            </span>
            <span
                class="bg-[#3897f0] break-words self-end rounded-2xl rouded-bl-[18px] rounded-br-[4px] rouded-tl-[18px] rounded-tr-[4px] px-2 py-[8px] max-w-[clamp(50%,564px,60%)]">
                milieu
            </span>
            <span class="bg-[#3897f0] self-end rounded-[18px] rounded-tr-[4px] px-2 py-[8px] max-w-[clamp(50%,564px,60%)]">
                Dernier
            </span>
        </div>
        <div class="px-4 lg:pb-3 pb-1 sticky bg-black pt-2 z-50 bottom-[0svh] lg:pt-2 left-0 w-full shrink-0">
            <div class="flex items-center min-h-[44px] gap-4 px-6 border-[#262626] mb-1 border rounded-full items">
                <svg-icon class="hover:cursor-pointer" type="mdi" size="26" @click="showEmojiPicker = !showEmojiPicker"
                    :path="mdiEmoticonHappyOutline" />
                <EmojiPicker class="absolute bottom-[10%] z-10" v-if="showEmojiPicker" :native="true"
                    @select="onSelectEmoji" />
                <textarea @keydown.enter="sendMessage" @input="resize($event)" ref="inputRef"
                    class="bg-transparent mb-1 float-left no-scrollbar resize-none h-9 max-h-16 w-[93%] border-none focus:ring-0"
                    placeholder="Votre message..." type="text" v-model="currentMessage"></textarea>
            </div>
        </div>
    </div>
</template>