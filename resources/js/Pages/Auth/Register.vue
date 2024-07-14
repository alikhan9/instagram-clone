<script setup>
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
const form = useForm({
  name: "",
  username: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
  terms: false,
  avatar: "",
});

const usernameRef = ref(null);
const tempImg = ref(null);

onMounted(() => {
  usernameRef.value.focus();
});

const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};

const updateAvatar = (event) => {
  form.avatar = event.target.files[0];

  if (form.avatar) {
    const reader = new FileReader();

    reader.onload = (e) => {
      tempImg.value = e.target.result; // This will be the data URL of the image
    };

    reader.readAsDataURL(form.avatar); // Convert the file to a data URL
  }
};
</script>

<template>
  <Head title="Register" />
  <form @submit.prevent="submit">
    <TextInput
      dark
      ref="usernameRef"
      id="username"
      type="text"
      v-model="form.username"
      placeholder="Pseudo"
      required
      autofocus
      autocomplete="username"
    />
    <InputError class="mt-2" :message="form.errors.username" />
    <TextInput
      dark
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
      dark
      id="email"
      type="email"
      v-model="form.email"
      placeholder="Email"
      required
      autocomplete="username"
    />
    <InputError class="mt-2" :message="form.errors.email" />
    <TextInput
      dark
      id="phone"
      type="text"
      v-model="form.phone"
      placeholder="Mobile"
      required
      autocomplete="phone"
    />
    <InputError class="mt-2" :message="form.errors.phone" />
    <TextInput
      dark
      id="password"
      type="password"
      placeholder="Mot de passe"
      v-model="form.password"
      required
      autocomplete="new-password"
    />
    <InputError class="mt-2" :message="form.errors.password" />
    <TextInput
      dark
      id="password_confirmation"
      type="password"
      class="mt-1 block w-full"
      placeholder="Confirmer mot de passe"
      v-model="form.password_confirmation"
      required
      autocomplete="new-password"
    />
    <InputError class="mt-2" :message="form.errors.password_confirmation" />
    <div>
      <label for="file-upload" class="custom-file-upload"> Select an avatar </label>
      <input
        id="file-upload"
        name="avatar"
        type="file"
        v-on:change="updateAvatar"
        accept="image/x-png,image/gif,image/jpeg"
        placeholder="avatar"
        required
      />
      <img
        class="w-[150px] h-[150px] mt-2"
        v-if="tempImg !== null"
        :src="tempImg"
        alt="avatar"
      />
    </div>
    <InputError class="mt-2" :message="form.errors.avatar" />
    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
      Validez
    </PrimaryButton>
  </form>
</template>

<style scoped>
input[type="file"] {
  display: none;
}
.custom-file-upload {
  border: 3px dashed #dbdbdb;
  display: inline-block;
  padding: 6px 12px;
  cursor: pointer;
  width: 100%;
  margin-top: 1rem;
  border-radius: 3px;
  padding-top: 9px;
  padding-bottom: 9px;
  font-weight: 400;
  color: #74869b;
}
</style>
