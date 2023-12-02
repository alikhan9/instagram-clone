<script setup>
import { onClickOutside } from '@vueuse/core'
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiClose, mdiCheck } from '@mdi/js'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useDebounceFn } from '@vueuse/core'

const emit = defineEmits(['update:showCreateNewGroup'])
const createNewGroupRef = ref(null)
const users = ref([])
const searchUsers = ref([])

onClickOutside(createNewGroupRef, () => close())

const close = () => emit('update:showCreateNewGroup', false)

const removeUser = user => {
    users.value = users.value.filter(u => u.id != user.id)
}

const addUser = user => {
    users.value.push(user)
}

const getUsers = useDebounceFn(value => {
    if (value)
        axios.get('/search/small/' + value).then(response => {
            searchUsers.value = response.data
        })
    else searchUsers.value = []
}, 200)

const sendCreateNewGroup = () => {
    router.post(
        '/group',
        { users: users.value },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['groups', 'group'],
        }
    )
}
</script>

<template>
    <div
        class="fixed flex h-full w-full items-center justify-center text-white backdrop-brightness-50"
    >
        <div
            ref="createNewGroupRef"
            class="flex h-[647px] w-[548px] flex-col gap-5 rounded-lg border border-[#262626] bg-[hsl(0,0%,15%)] pb-4"
            :class="{}"
        >
            <div
                class="flex items-center justify-between border-b border-[#262626] px-5 py-5"
            >
                <div></div>
                <div>Nouveau message</div>
                <div>
                    <svg-icon
                        @click="close"
                        type="mdi"
                        :path="mdiClose"
                        size="26"
                    ></svg-icon>
                </div>
            </div>
            <div
                class="flex w-full items-center border-b border-[#262626] px-5 py-2"
            >
                <div class="mr-2">À :</div>
                <div
                    class="mr-2 flex flex-wrap items-center gap-2 rounded-[12px] bg-white px-[12px] py-1 text-[rgb(0,149,246)]"
                >
                    <div>Nom Prenom</div>
                    <div>
                        <svg-icon
                            @click="removeUser"
                            type="mdi"
                            :path="mdiClose"
                            size="18"
                        ></svg-icon>
                    </div>
                </div>
                <input
                    @input="getUsers($event.target.value)"
                    type="text"
                    class="border-none bg-transparent ring-0 focus:border-none focus:ring-0"
                    placeholder="Recherchez..."
                />
            </div>
            <div class="h-full flex-1 overflow-auto">
                <div
                    v-if="searchUsers.length == 0"
                    class="text-[hsl(0,0%,70%)] px-4"
                >
                    Aucun compte trouvé.
                </div>

                <div
                    v-else
                    v-for="(user, index) in searchUsers"
                    :key="index"
                    class="flex px-4 pb-4 items-center justify-between hover:bg-[hsl(0,0%,35%)]"
                >
                    <div class="flex gap-3">
                        <div
                            class="h-[44px] w-[44px] overflow-hidden rounded-full"
                        >
                            <img
                                src="https://picsum.photos/seed/picsum/44/44"
                            />
                        </div>
                        <div>
                            <div class="font-semibold">{{ user.name }}</div>
                            <div class="font-semibold text-[hsl(0,0%,60%)]">
                                {{ user.username }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex h-[24px] w-[24px] items-center justify-center overflow-hidden rounded-full border"
                    >
                        <svg-icon
                            v-if="users.includes(user)"
                            type="mdi"
                            size="24"
                            class="bg-[hsl(0,0%,96%)] text-black"
                            :path="mdiCheck"
                        ></svg-icon>
                    </div>
                </div>
            </div>
            <div class="shrink-0 px-4 text-lg">
                <button
                    class="w-full rounded-[8px] bg-[rgb(0,149,246)] px-[20px] py-[10px] text-white"
                >
                    Discuter
                </button>
            </div>
        </div>
    </div>
</template>
