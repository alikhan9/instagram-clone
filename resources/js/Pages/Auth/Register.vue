<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});
const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />
        <form @submit.prevent="submit">
            <TextInput
                id="name"
                type="text"
                v-model="form.name"
                placeholder="Nom"
                required
                autofocus
                autocomplete="name"
            />
            <InputError class="mt-2" :message="form.errors.name" />
            <TextInput
                id="email"
                type="email"
                v-model="form.email"
                placeholder="email"
                required
                autocomplete="username"
            />
            <InputError class="mt-2" :message="form.errors.email" />
            <TextInput
                id="password"
                type="password"
                placeholder="Mot de passe"
                v-model="form.password"
                required
                autocomplete="new-password"
            />
            <InputError class="mt-2" :message="form.errors.password" />
            <TextInput
                id="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                placeholder="Confirmer mot de passe"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
            />
            <InputError
                class="mt-2"
                :message="form.errors.password_confirmation"
            />
            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Validez
            </PrimaryButton>
        </form>
    </GuestLayout>
</template>
