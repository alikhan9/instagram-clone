<script setup>
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { onMounted,ref } from "vue";
const form = useForm({
    name: "",
    username: "",
    email: "",
    phone: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const usernameRef = ref(null);

onMounted(() => {
    usernameRef.value.focus();
})

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Register" />
    <form @submit.prevent="submit">

        <TextInput dark ref="usernameRef" id="username" type="text" v-model="form.username" placeholder="Pseudo" required autofocus
            autocomplete="username" />
        <InputError class="mt-2" :message="form.errors.username" />
        <TextInput dark id="name" type="text" v-model="form.name" placeholder="Nom" required autofocus autocomplete="name" />
        <InputError class="mt-2" :message="form.errors.name" />
        <TextInput dark id="email" type="email" v-model="form.email" placeholder="Email" required autocomplete="username" />
        <InputError class="mt-2" :message="form.errors.email" />
        <TextInput dark id="phone" type="text" v-model="form.phone" placeholder="Mobile" required autocomplete="phone" />
        <InputError class="mt-2" :message="form.errors.phone" />
        <TextInput dark id="password" type="password" placeholder="Mot de passe" v-model="form.password" required
            autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.password" />
        <TextInput dark id="password_confirmation" type="password" class="mt-1 block w-full" placeholder="Confirmer mot de passe"
            v-model="form.password_confirmation" required autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Validez
        </PrimaryButton>
    </form>
</template>
