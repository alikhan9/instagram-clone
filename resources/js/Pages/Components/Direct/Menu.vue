<script setup>
import { usePage, Link, router } from '@inertiajs/vue3'
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiPencilBoxOutline, mdiArrowLeft, mdiInformationSlabCircleOutline } from '@mdi/js';
const props = defineProps({
    showChat: Boolean,
    user: Object,
    contacts: Array,
    receiver: Object,
    toggleChat: Function,
})

const openChat = contact => {
    router.get('/direct/t/' + (contact.receiver.hasOwnProperty('id') ? contact.receiver.id : contact.initiator.id), {}, {
        preserveState: true,
        only: ['receiver', 'messages'],
        onFinish: () => {
            props.toggleChat();
        }
    });
}

</script>

<template>
    <div
        :class="{ 'lg:w-[400px] shrink-0 w-full lg:border-r border-[#262626] [&>div]:px-[24px] overflow-y-auto': true, 'hidden lg:block': showChat }">
        <div class="flex justify-between py-2 items-center lg:pt-[36px] lg:pb-[12px]">
            <Link class="lg:hidden" href="/">
            <svg-icon type="mdi" :path="mdiArrowLeft" size="28"></svg-icon>
            </Link>
            <div class="text-2xl font-semibold">
                {{ user.username }}
            </div>
            <div>
                <!-- TODO: Add create new chat page -->
                <svg-icon class="hover:cursor-pointer" type="mdi" size="30" :path="mdiPencilBoxOutline"></svg-icon>
            </div>
        </div>
        <div class="flex justify-between py-4">
            <div class="text-lg font-semibold">
                Messages
            </div>
            <div class="lg:text-[hsl(0,0%,70%)] font-semibold lg:font-normal text-[rgb(0,149,246)]">
                <!-- TODO:Link for blocked or not followed people -->
                Demandes
            </div>
        </div>
        <div @click="() => openChat(contact)" v-for="(contact, index) in contacts" :key="index" :class="{
            'flex w-full py-[8px] gap-[12px] hover:cursor-pointer': true,
            'lg:bg-[hsl(0,0%,15%)]': receiver?.hasOwnProperty('id') && (receiver.id === contact.receiver || receiver.id === contact.initiator),
            'lg:hover:bg-[hsl(0,0%,8%)]': receiver?.hasOwnProperty('id') && (receiver.id !== contact.receiver || receiver.id !== contact.initiator)
        }">
            <div class="w-[54px] h-[54px] rounded-full overflow-hidden">
                <img src="https://picsum.photos/seed/picsum/54/54" />
            </div>
            <div>
                <div class="mb-2 font-semibold">{{ contact?.receiver.username }}{{ contact?.initiator.username }}
                </div>
                <!-- TODO:Last message -->
                <div class="font-semibold text-sm text-[hsl(0,0%,70%)]">Dernier message</div>
            </div>
        </div>
    </div>
</template>