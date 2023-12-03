<script setup>
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiInformationSlabCircleOutline } from '@mdi/js'

defineProps({
    group: Object,
    receiver: Object,
    toggleShowDetails: Function,
})
</script>

<template>
    <div
        class="hidden items-center justify-between border-b border-[#262626] px-4 py-5 lg:flex"
    >
        <div class="flex items-center gap-4">
            <a
                v-if="receiver"
                class="flex items-center gap-3"
                :href="'/profile/' + receiver.username"
                target="_blank"
            >
                <div class="h-11 w-11 overflow-hidden rounded-full">
                    <img
                        class="h-full w-full"
                        src="https://picsum.photos/seed/picsum/44/44"
                    />
                </div>
                <div>
                    <div class="text-[16px] font-semibold">
                        {{ receiver.username }}
                    </div>
                    <div class="text-[12px] font-semibold text-[hsl(0,0%,70%)]">
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
                        <span v-if="index !== 0">, </span>{{ member.user.name }}
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
                size="28"
                @click="toggleShowDetails"
                class="hover:cursor-pointer"
                type="mdi"
                :path="mdiInformationSlabCircleOutline"
            ></svg-icon>
        </div>
    </div>
</template>
