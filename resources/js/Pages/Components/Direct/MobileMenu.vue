<script setup>
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiArrowLeft, mdiInformationSlabCircleOutline } from '@mdi/js'

defineProps({
    receiver: Object,
    toggleChat: Function,
    toggleShowDetails: Function,
    group: Object,
})
</script>

<template>
    <div class="w-full lg:hidden">
        <div
            class="flex items-center justify-between border-b border-[#262626] px-4 py-1"
        >
            <div class="flex items-center gap-4">
                <div
                    @click="toggleChat"
                    class="hover:cursor-pointer lg:hidden"
                >
                    <svg-icon
                        type="mdi"
                        :path="mdiArrowLeft"
                        size="28"
                    ></svg-icon>
                </div>
                <a
                    v-if="receiver"
                    class="flex items-center gap-3"
                    :href="'/profile/' + receiver.username"
                    target="_blank"
                >
                    <div class="h-7 w-7 overflow-hidden rounded-full">
                        <img
                            class="h-full w-full"
                            src="https://picsum.photos/seed/picsum/32/32"
                        />
                    </div>
                    <div>
                        <div class="text-[16px] font-semibold">
                            {{ receiver.username }}
                        </div>
                        <div
                            class="text-[12px] font-semibold text-[hsl(0,0%,70%)]"
                        >
                            En ligne il y a 17min
                        </div>
                    </div>
                </a>
                <div v-else>
                    <div
                        v-if="group.name"
                        class="mb-2 font-semibold"
                    >
                        {{ group.name }}
                    </div>
                    <div
                        class="max-w-full"
                        v-else
                    >
                        <span
                            v-for="(member, index) in group.members.slice(0, 4)"
                            :key="index"
                            class="mb-2 mr-1 font-semibold"
                        >
                            <span v-if="index !== 0">, </span
                            >{{ member.user.name }}
                        </span>
                        <span
                            class="font-semibold"
                            v-if="group.members.length > 4"
                        >
                            ...
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <svg-icon
                    @click="toggleShowDetails"
                    class="hover:cursor-pointer"
                    size="26"
                    type="mdi"
                    :path="mdiInformationSlabCircleOutline"
                ></svg-icon>
            </div>
        </div>
    </div>
</template>
