<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import ChatDetails from './Components/Direct/ChatDetails.vue'
import Menu from './Components/Direct/Menu.vue';
import MobileMenu from './Components/Direct/MobileMenu.vue';
import ChatContent from './Components/Direct/ChatContent.vue';
import { useMessageStore } from '@/Pages/useStore/useMessageStore';

const props = defineProps({
    contacts: Array,
    receiver: Object,
    messages: Object,
    active: {
        type: Boolean,
        default: false
    }
})

const user = usePage().props.auth.user;
const showChat = ref(!(props.receiver == null))
const showDetails = ref(false);
const currentContact = ref(null);
const messages = useMessageStore();
const isMessageReady = ref(false);

onMounted(() => {
    messages.setMessages(props.messages);
    setTimeout(() => {
        if (props.receiver && messages.getUnreadNotificationsForUser(props.receiver.id) > 0) {
            axios.post('/message/notifications/check', { sender: props.receiver.id }).then(() => {
                messages.removeNotificationsForUser(props.receiver.id);
                messages.updateUnreadNotifications();
            })
        }
        isMessageReady.value = true;

        if (props.receiver) {
            currentContact.value = props.contacts.filter(m => m.receiver.hasOwnProperty('id') ? m.receiver.id == props.receiver.id || m.receiver.id === user.id : m.initiator.id == props.receiver.id || m.initiator.id === user.id)[0];
        }

    }, 10);
});

watch(() => props.receiver, () => {
    isMessageReady.value = false;
    messages.setMessages(props.messages);
    setTimeout(() => {
        if (props.receiver && messages.getUnreadNotificationsForUser(props.receiver.id) > 0) {
            axios.post('/message/notifications/check', { sender: props.receiver.id }).then(() => {
                messages.removeNotificationsForUser(props.receiver.id);
                messages.updateUnreadNotifications();
                isMessageReady.value = true;
            })
        }
        isMessageReady.value = true;
    }, 10);
});


const toggleChat = () => {
    showChat.value = !showChat.value;
}

const toggleShowDetails = () => {
    showDetails.value = !showDetails.value;
}

</script>

<template>
    <div
        class="text-white bg-black lg:h-full inset-0 fixed overflow-x-hidden top-0 left-0 z-[999] lg:z-0 lg:relative w-full flex flex-col lg:flex-row">
        <Menu :isMessageReady="isMessageReady" :receiver="receiver" v-model:currentContact="currentContact"
            :toggleChat="toggleChat" :contacts="contacts" :showChat="showChat" :user="user" />
        <div v-if="!receiver" class="items-center justify-center hidden w-full h-full lg:flex">
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
        <MobileMenu v-if="showChat" :receiver="receiver" :toggleChat="toggleChat" :toggleShowDetails="toggleShowDetails" />
        <ChatContent v-model:currentContact="currentContact" :messages="messages" v-if="receiver" :showChat="showChat"
            :receiver="receiver" :toggleShowDetails="toggleShowDetails" />

        <Transition name="slide-from-right">
            <ChatDetails v-if="showDetails" v-model:showDetails="showDetails" :receiver="receiver" />
        </Transition>
    </div>
</template>

<style>
.slide-from-right-enter-active,
.slide-from-right-leave-active {
    transition: all 0.3s ease-out;
}

.slide-from-right-enter-from,
.slide-from-right-leave-to {
    transform: translateX(100vh);
}
</style>