<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    receiver: Object
})

</script>

<template>
    <div class="flex flex-col justify-center pt-12 w-full items-center gap-5">
        <div class="w-28 h-28 rounded-full overflow-hidden">
            <img class="w-full h-full" src="https://picsum.photos/seed/picsum/96/96" />
        </div>
        <div>
            <div class="font-semibold mb-2 text-[20px]">
                {{ receiver.name }}
            </div>
            <div class="font-semibold text-center text-[14px] text-[hsl(0,0%,70%)]">
                {{ receiver.username }}
            </div>
        </div>
        <Link :href="'/profile/' + receiver.username" as="button"
            class="px-4 rounded-lg text-white font-semibold py-2 bg-[hsl(0,0%,21%)]">Voir profil</Link>
    </div>
</template>