<script setup>
import SvgIcon from '@jamescoyle/vue-icon'

defineProps({
    path: String
})
</script>
<template>
    <div
        class="flex items-center gap-4 p-2 text-lg font-bold hover:bg-[#262626] transition ease-in rounded-lg duration-300 hover:cursor-pointer">
        <div>
            <svg-icon size="30" type="mdi" :path="path" />
        </div>
        <p>
            <slot></slot>
        </p>
    </div>
</template>