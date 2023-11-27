<script setup>
import { usePage, Link, router } from '@inertiajs/vue3'
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiPencilBoxOutline, mdiArrowLeft } from '@mdi/js';


const props = defineProps({
    contacts: Array,
    receiver: Object,
    active: {
        type: Boolean,
        default: false
    }
})

const user = usePage().props.auth.user;

const openChat = contact => {
    router.get('/direct/t/' + (typeof contact.receiver !== 'string' ? contact.receiver.id : contact.initiator.id));
}

</script>


<template>
    <div class="text-white bg-black fixed top-0 left-0 z-[999] lg:relative w-full flex h-full">
        <div
            :class="{ 'lg:w-[400px] w-full lg:border-r border-[#262626] [&>div]:px-[24px] overflow-y-auto': true, 'hidden': receiver }">
            <div class="flex justify-between py-2 items-center lg:pt-[36px] lg:pb-[12px]">
                <Link class="lg:hidden" href="/">
                <svg-icon type="mdi" :path="mdiArrowLeft" size="28"></svg-icon>
                </Link>
                <div class="font-semibold text-2xl">
                    {{ user.username }}
                </div>
                <div>
                    <!-- TODO: Add create new chat page -->
                    <svg-icon class="hover:cursor-pointer" type="mdi" size="30" :path="mdiPencilBoxOutline"></svg-icon>
                </div>
            </div>
            <div class="flex justify-between py-4">
                <div class="font-semibold text-lg">
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
                    <div class="font-semibold mb-2">{{ contact?.receiver.username }}{{ contact?.initiator.username }}
                    </div>
                    <!-- TODO:Last message -->
                    <div class="font-semibold text-sm text-[hsl(0,0%,70%)]">Dernier message</div>
                </div>
            </div>
        </div>
        <div class="h-full w-full justify-center items-center hidden lg:flex">
            <div class="flex flex-col items-center gap-4">
                <div>
                    <svg aria-label="" class="" fill="currentColor" height="96" role="img" viewBox="0 0 96 96" width="96">
                        <title></title>
                        <path
                            d="M48 0C21.532 0 0 21.533 0 48s21.532 48 48 48 48-21.532 48-48S74.468 0 48 0Zm0 94C22.636 94 2 73.364 2 48S22.636 2 48 2s46 20.636 46 46-20.636 46-46 46Zm12.227-53.284-7.257 5.507c-.49.37-1.166.375-1.661.005l-5.373-4.031a3.453 3.453 0 0 0-4.989.921l-6.756 10.718c-.653 1.027.615 2.189 1.582 1.453l7.257-5.507a1.382 1.382 0 0 1 1.661-.005l5.373 4.031a3.453 3.453 0 0 0 4.989-.92l6.756-10.719c.653-1.027-.615-2.189-1.582-1.453ZM48 25c-12.958 0-23 9.492-23 22.31 0 6.706 2.749 12.5 7.224 16.503.375.338.602.806.62 1.31l.125 4.091a1.845 1.845 0 0 0 2.582 1.629l4.563-2.013a1.844 1.844 0 0 1 1.227-.093c2.096.579 4.331.884 6.659.884 12.958 0 23-9.491 23-22.31S60.958 25 48 25Zm0 42.621c-2.114 0-4.175-.273-6.133-.813a3.834 3.834 0 0 0-2.56.192l-4.346 1.917-.118-3.867a3.833 3.833 0 0 0-1.286-2.727C29.33 58.54 27 53.209 27 47.31 27 35.73 36.028 27 48 27s21 8.73 21 20.31-9.028 20.31-21 20.31Z">
                        </path>
                    </svg>
                </div>
                <div class="text-2xl">
                    Vos messages
                </div>
                <div class="text-[hsl(0,0%,70%)]">
                    Envoyez des photos and des messages privés à un(e) ami(e) ou à un groupe
                </div>
                <!-- TODO: Create new chat -->
                <button class="bg-[rgb(0,149,246)] font-semibold rounded-lg px-4 py-2 text-white">Envoyer un
                    message</button>
            </div>
        </div>
    </div>
</template>