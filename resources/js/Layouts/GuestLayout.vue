<script setup></script>

<template>
    <div
        class="min-h-screen flex sm:justify-center items-center pt-6 sm:pt-0 bg-white"
    >
        <div class="w-full flex justify-end">
            <img src="./../../images/login.webp" class="max-w-[450px]" />
        </div>

        <div class="w-full">
            <div
                class="w-full max-w-[350px] mt-6 px-6 py-4 bg-white border border-[#DBDBDB]"
            >
                <slot />
            </div>
            <div
                class="w-full max-w-[350px] mt-6 px-6 py-4 bg-white border border-[#DBDBDB]"
            >
                Vous n’avez pas de compte ?
            </div>
        </div>
    </div>
</template>
