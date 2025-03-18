<script setup>
import GuestLayout from "../components/GuestLayout.vue";
import { ref } from "vue";
import axiosClient from "../axios.js";
import router from "../router.js";

const data = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const errors = ref({
  name: [],
  email: [],
  password: [],
});

function submit() {
  axiosClient.get("/sanctum/csrf-cookie").then(() => {
    axiosClient
      .post("/register", data.value)
      .then(() => {
        router.push({ name: "Home" });
      })
      .catch((error) => {
        errors.value = error.response?.data?.errors || {};
      });
  });
}
</script>

<template>
  <GuestLayout>
    <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-gray-900">
      Create new Account
    </h2>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-900">Full Name</label>
          <div class="mt-2">
            <input
              name="name"
              id="name"
              v-model="data.name"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
            />
          </div>
          <p class="text-sm mt-1 text-red-600">{{ errors.name ? errors.name[0] : "" }}</p>
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-gray-900">Email address</label>
          <div class="mt-2">
            <input
              type="email"
              name="email"
              id="email"
              autocomplete="email"
              v-model="data.email"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
            />
          </div>
          <p class="text-sm mt-1 text-red-600">{{ errors.email ? errors.email[0] : "" }}</p>
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
          <div class="mt-2">
           '' <input
              type="password"
              name="password"
              id="password"
              v-model="data.password"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
            />
          </div>
          <p class="text-sm mt-1 text-red-600">{{ errors.password ? errors.password[0] : "" }}</p>
        </div>
        <div>
          <label for="passwordConfirmation" class="block text-sm font-medium text-gray-900">
            Repeat Password
          </label>
          <div class="mt-2">
            <input
              type="password"
              name="password_confirmation"
              id="passwordConfirmation"
              v-model="data.password_confirmation"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
            />
          </div>
        </div>
        <div>
          <button
            type="submit"
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
          >
            Create an Account
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Already have an account?
        <RouterLink to="/login" class="font-semibold text-indigo-600 hover:text-indigo-500">
          Login from here
        </RouterLink>
      </p>
    </div>
  </GuestLayout>
</template>

<style scoped>
</style>