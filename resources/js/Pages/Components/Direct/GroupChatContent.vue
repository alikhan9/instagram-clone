<script setup>
import DesktopHeader from './DesktopHeader.vue'
import EmojiPicker from 'vue3-emoji-picker'
import { mdiEmoticonHappyOutline } from '@mdi/js'
import { ref, onMounted, watch } from 'vue'
import SvgIcon from '@jamescoyle/vue-icon'
import { useMessageStore } from '@/Pages/useStore/useMessageStore'
import { usePage } from '@inertiajs/vue3'
import MobileMenu from './MobileMenu.vue'

const props = defineProps({
    group: Object,
    showChat: Boolean,
    toggleShowDetails: Function,
    toggleChat: Function,
})

const user = usePage().props.auth.user
const showEmojiPicker = ref(false)
const currentMessage = ref('')
const messages = useMessageStore()
let sentMessageIndex = 0
let receivedMessageIndex = 0

watch(
    () => props.group,
    (newGroup, oldGroup) => {
        Echo.leave('App.Models.Group.' + oldGroup.id)
        Echo.private('App.Models.Group.' + newGroup.id).listen(
            '.group-message',
            e => {
                messages.addMessage(e.message)
            }
        )
    }
)

onMounted(() => {
    Echo.private('App.Models.Group.' + props.group.id).listen(
        '.group-message',
        e => {
            messages.addMessage(e.message)
        }
    )
})

const incrementSentMessageIndex = index => {
    if (receivedMessageIndex > 0) receivedMessageIndex = 0
    if (
        sentMessageIndex > 0 &&
        ((index < messages.getMessages().length - 1 &&
            messages.getMessages()[index + 1].sender !== user.id) ||
            (sentMessageIndex > 0 &&
                index === messages.getMessages().length - 1))
    ) {
        sentMessageIndex = 3
        return
    }

    if (
        messages.getMessages().length - 1 > index &&
        messages.getMessages()[index + 1].sender == user.id
    )
        if (sentMessageIndex < 2) sentMessageIndex++
}

const incrementReceivedMessageIndex = index => {
    if (sentMessageIndex > 0) sentMessageIndex = 0
    if (
        receivedMessageIndex > 0 &&
        ((index < messages.getMessages().length - 1 &&
            messages.getMessages()[index + 1].user_id !== user.id) ||
            (receivedMessageIndex > 0 &&
                index === messages.getMessages().length - 1))
    ) {
        receivedMessageIndex = 3
        return
    }
    if (
        messages.getMessages().length - 1 > index &&
        messages.getMessages()[index + 1].user_id == user.id
    )
        if (receivedMessageIndex < 2) receivedMessageIndex++
}

const sendMessage = () => {
    if (currentMessage.value.length === 0) return
    const messageToSend = currentMessage.value
    currentMessage.value = ''
    axios.post('/group/' + props.group.id + '/message', {
        message: messageToSend,
    })
}

const onSelectEmoji = emoji => {
    currentMessage.value += emoji.i
    showEmojiPicker.value = false
}

const resize = e => {
    e.target.style.height = 'auto'
    e.target.style.height = `${e.target.scrollHeight - 32}px`
}
</script>

<template>
    <div class="flex h-full w-full flex-col">
        <DesktopHeader
            :toggleShowDetails="toggleShowDetails"
            :group="group"
        />
        <MobileMenu
            v-if="showChat"
            :group="group"
            :toggleChat="toggleChat"
            :toggleShowDetails="toggleShowDetails"
        />
        <div
            class="h-full w-full overflow-auto"
            :class="{ 'hidden lg:block': !showChat }"
        >
            <div class="mt-10 h-full">
                <div
                    v-for="(message, index) in messages.getMessages()"
                    :key="index"
                    class="flex w-full flex-col gap-1 overflow-auto px-2 text-white lg:px-4"
                >
                    {{
                        message.user_id === user.id
                            ? incrementSentMessageIndex(index)
                            : incrementReceivedMessageIndex(index)
                    }}
                    <span
                        v-if="message.user_id === user.id"
                        class="mb-2 max-w-[clamp(50%,564px,60%)] self-end bg-[#3897f0] px-2 py-[8px]"
                        :class="{
                            'rounded-[18px]': sentMessageIndex == 0,
                            'rounded-[18px] rounded-br-[4px]':
                                sentMessageIndex === 1,
                            'rounded-bl-[18px] rounded-br-[4px] rounded-tl-[18px] rounded-tr-[4px]':
                                sentMessageIndex === 2,
                            'rounded-[18px] rounded-tr-[4px]':
                                sentMessageIndex === 3,
                        }"
                    >
                        <span>
                            {{ message.message }}
                        </span>
                    </span>
                    <span
                        v-else
                        class="mb-1 flex max-w-[clamp(50%,564px,60%)] gap-1 self-start bg-[#262626] px-2 py-[8px]"
                        :class="{
                            'rounded-[18px]': receivedMessageIndex == 0,
                            'rounded-[18px] rounded-bl-[4px]':
                                receivedMessageIndex === 1,
                            'rounded-bl-[4px] rounded-br-[18px] rounded-tl-[4px] rounded-tr-[18px]':
                                receivedMessageIndex === 2,
                            'rounded-[18px] rounded-tl-[4px]':
                                receivedMessageIndex === 3,
                        }"
                    >
                        <div class="shrink-0">
                            {{ message.user.username }} :
                        </div>
                        <div>
                            {{ message.message }}
                        </div>
                    </span>
                </div>
            </div>
        </div>
        <div
            class="z-50 bg-black px-4 pb-1 pt-2 lg:pb-3"
            :class="{ 'hidden lg:block': !showChat }"
        >
            <div
                class="items mb-1 flex min-h-[44px] items-center gap-4 rounded-full border border-[#262626] px-6"
            >
                <svg-icon
                    class="hover:cursor-pointer"
                    type="mdi"
                    size="26"
                    @click="showEmojiPicker = !showEmojiPicker"
                    :path="mdiEmoticonHappyOutline"
                />
                <EmojiPicker
                    class="absolute bottom-[10%] z-10"
                    v-if="showEmojiPicker"
                    :native="true"
                    @select="onSelectEmoji"
                />
                <textarea
                    @keydown.enter="sendMessage"
                    @input="resize($event)"
                    ref="inputRef"
                    class="no-scrollbar float-left mb-1 h-9 max-h-16 w-[93%] resize-none border-none bg-transparent focus:ring-0"
                    placeholder="Votre message..."
                    type="text"
                    v-model="currentMessage"
                ></textarea>
            </div>
        </div>
    </div>
</template>
