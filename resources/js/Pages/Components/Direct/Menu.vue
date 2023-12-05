<script setup>
import { Link, router } from '@inertiajs/vue3'
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiPencilBoxOutline, mdiArrowLeft } from '@mdi/js'
import { useMessageStore } from '@/Pages/useStore/useMessageStore'
import CreateNewGroup from './CreateNewGroup.vue'
import { ref } from 'vue'

const props = defineProps({
    showChat: Boolean,
    user: Object,
    contacts: Array,
    receiver: Object,
    toggleChat: Function,
    groups: Object,
    group: Object,
    isMessageReady: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['update:currentContact', 'update:currentGroup'])

const openChat = contact => {
    router.get(
        '/direct/t/' +
            (contact.receiver.hasOwnProperty('id')
                ? contact.receiver.id
                : contact.initiator.id),
        {},
        {
            preserveState: true,
            only: ['receiver', 'messages', 'group'],
            onFinish: () => {
                emit('update:currentContact', contact)
                props.toggleChat()
            },
        }
    )
}
const openChatGroup = group => {
    router.get(
        '/direct/g/' + group.id,
        {},
        {
            preserveState: true,
            only: ['receiver', 'messages', 'group'],
            onFinish: () => {
                props.toggleChat()
            },
        }
    )
}

const messages = useMessageStore()
const showCreateNewGroup = ref(false)
</script>

<template>
    <div
        :class="{
            'w-full shrink-0 overflow-y-auto border-[#262626] lg:w-[400px] lg:border-r [&>div]:px-[24px]': true,
            'hidden lg:block': showChat,
        }"
    >
        <CreateNewGroup
            v-if="showCreateNewGroup"
            v-model:showCreateNewGroup="showCreateNewGroup"
        />
        <div
            class="flex items-center justify-between py-2 lg:pb-[12px] lg:pt-[36px]"
        >
            <Link
                class="lg:hidden"
                href="/"
            >
                <svg-icon
                    type="mdi"
                    :path="mdiArrowLeft"
                    size="28"
                ></svg-icon>
            </Link>
            <div class="text-2xl font-semibold">
                {{ user.username }}
            </div>
            <div>
                <svg-icon
                    @click="() => (showCreateNewGroup = true)"
                    class="hover:cursor-pointer"
                    type="mdi"
                    size="30"
                    :path="mdiPencilBoxOutline"
                ></svg-icon>
            </div>
        </div>
        <div class="flex justify-between py-4">
            <div class="text-lg font-semibold">Messages</div>
            <div
                class="font-semibold text-[rgb(0,149,246)] lg:font-normal lg:text-[hsl(0,0%,70%)]"
            >
                <!-- TODO:Link for blocked or not followed people -->
                Demandes
            </div>
        </div>
        <div
            @click="() => openChat(contact)"
            v-for="(contact, index) in contacts"
            :key="index"
            :class="{
                'flex w-full items-center justify-between py-[8px] hover:cursor-pointer': true,
                'lg:bg-[hsl(0,0%,15%)]':
                    receiver?.hasOwnProperty('id') &&
                    (receiver.id === contact.receiver ||
                        receiver.id === contact.initiator),
                'lg:hover:bg-[hsl(0,0%,8%)]':
                    receiver?.hasOwnProperty('id') &&
                    (receiver.id !== contact.receiver ||
                        receiver.id !== contact.initiator),
            }"
        >
            <div class="flex gap-[12px]">
                <div class="h-[54px] w-[54px] overflow-hidden rounded-full">
                    <img src="https://picsum.photos/seed/picsum/54/54" />
                </div>
                <div>
                    <div class="mb-2 font-semibold">
                        {{ contact?.receiver.username
                        }}{{ contact?.initiator.username }}
                    </div>
                    <!-- TODO:Last message -->
                    <div class="text-sm font-semibold text-[hsl(0,0%,70%)]">
                        Dernier message
                    </div>
                </div>
            </div>
            <div
                :class="{
                    'h-[8px] w-[8px] rounded-full bg-blue-500': true,
                    hidden:
                        isMessageReady &&
                        messages.getUnreadNotificationsForUser(
                            contact.receiver.hasOwnProperty('id')
                                ? contact.receiver.id
                                : contact.initiator.id
                        ) === 0,
                }"
            ></div>
        </div>
        <div
            @click="() => openChatGroup(gr)"
            v-for="(gr, index) in groups"
            :key="index"
            :class="{
                'flex w-full items-center justify-between py-[8px] hover:cursor-pointer': true,
                'lg:bg-[hsl(0,0%,15%)]': gr.id === group?.id,
                'lg:hover:bg-[hsl(0,0%,8%)]': gr.id !== group?.id,
            }"
        >
            <div class="flex gap-[12px]">
                <div
                    class="h-[54px] w-[54px] shrink-0 overflow-hidden rounded-full"
                >
                    <img src="https://picsum.photos/seed/picsum/54/54" />
                </div>
                <div>
                    <div
                        v-if="gr.name !== null"
                        class="mb-2 font-semibold"
                    >
                        {{ gr.name }}
                    </div>
                    <div
                        class="max-w-full"
                        v-else
                    >
                        <span
                            v-for="(member, index) in gr.members.slice(0, 4)"
                            :key="index"
                            class="mb-2 mr-1 font-semibold"
                        >
                            <span v-if="index !== 0">, </span
                            >{{ member.user.name }}
                        </span>
                        <span
                            class="font-semibold"
                            v-if="gr.members.length > 4"
                        >
                            ...
                        </span>
                    </div>
                    <!-- TODO:Last message -->
                    <div class="text-sm font-semibold text-[hsl(0,0%,70%)]">
                        Dernier message
                    </div>
                </div>
            </div>
            <div
                :class="{
                    'h-[8px] w-[8px] rounded-full bg-blue-500': true,
                    hidden:
                        isMessageReady &&
                        messages.getUnreadGroupNotifications(gr.id) === 0,
                }"
            ></div>
        </div>
    </div>
</template>
