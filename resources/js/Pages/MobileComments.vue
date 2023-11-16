<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    scrollPosition: Number
})


const emit = defineEmits(['toggleComments'])

const close = () => {
    emit('toggleComments');
}

</script>

<template>
    <div class="text-white h-screen sticky bg-red-300">
        <div class="mt-5">
            <button @click="close">Fermer Avec emit</button>
        </div>
    </div>
</template>