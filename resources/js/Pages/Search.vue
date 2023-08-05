<script setup>
import { ref } from "vue";
import { useDebounceFn } from '@vueuse/core'
import axios from "axios";
import { Link } from "@inertiajs/vue3";


const users = ref(null);

const getUsers = useDebounceFn(value => {
    axios.get('/search/' + value)
        .then(response => {
            console.log(response);
            users.value = response.data;
        })
}, 1000)

</script>

<template>
    <div class="w-[400px] h-screen bg-black text-white ml-[70px] absolute  border-r border-[hsl(0,0%,23%)]">
        <div class="border-b border-[hsl(0,0%,23%)] px-5 py-4">
            <h1 class="text-3xl ">Recherche</h1>
            <input type="text" @input="getUsers($event.target.value)" placeholder="Rechercher"
                class="w-full mt-10 mb-5 bg-[hsl(0,0%,15%)] rounded-md outline-none border-none py-3 px-4 focus:ring-0" />
        </div>
        <div class="overflow-auto h-[85vh]">
            <Link :href="'/profile/' + user.username" class="flex items-center gap-4 px-4 py-4 hover:bg-[hsl(0,0%,15%)]" v-for="(user, index) in users" v-if="users" :key="index">
                <img class="rounded-full" src="https://picsum.photos/seed/picsum/54/54" />
                <div>
                    <div class="font-semibold">{{ user.username }}</div>
                    <div class="text-[#858585]">1.2M followers</div>
                </div>
            </Link>
        </div>
    </div>
</template>