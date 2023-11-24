<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="min-h-screen flex sm:justify-center items-center pt-6 sm:pt-0 bg-white">
        <div class="w-full hidden md:flex justify-end">
            <img src="./../../images/login.webp" class="max-w-[450px]" />
        </div>
        <div class="w-full">
            <div class="w-full max-w-[350px] mt-6 px-6 py-4 bg-white border border-[#DBDBDB]">
                <h1 class="text-4xl text-center py-8">Instagram</h1>

                <slot />
            </div>
            <div class="w-full max-w-[350px] mt-6 px-6 py-4 bg-white border border-[#DBDBDB]">
                <div v-if="$page.url == '/login'">
                    <span>Vous n’avez pas de compte ?</span>
                    <Link class="text-blue-500" href="/register">
                    Inscrivez-vous
                    </Link>
                </div>
                <div v-else>
                    <span>Vous avez un compte ?</span>
                    <Link class="text-blue-500" href="/login">
                    Connectez-vous
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
