<script setup>
import GuestLayout from "../components/GuestLayout.vue";
import axiosClient from "../axios.js";
import { ref } from "vue";
import router from "../router.js";

const data = ref({
  email: "",
  password: "",
});
const errorMessage = ref("");

function submit() {
  axiosClient.get("/sanctum/csrf-cookie").then(() => {
    axiosClient
      .post("/login", data.value)
      .then(() => {
        router.push({ name: "Home" });
      })
      .catch((error) => {
        errorMessage.value = error.response?.data?.message || "Login failed";
      });
  });
}
</script>

<template>
  <GuestLayout>
    <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-gray-900">
      Sign in to your account
    </h2>

    <div v-if="errorMessage" class="mt-4 py-2 px-3 rounded text-white bg-red-400">
      {{ errorMessage }}
    </div>

    <div class="mt-4 sm:mx-auto sm:w-full sm:max-w-sm">
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-900">Email address</label>
          <div class="mt-2">
            <input
              type="email"
              name="email"
              id="email"
              autocomplete="email"
              required
              v-model="data.email"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
            />
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
          <div class="mt-2">
            <input
              type="password"
              name="password"
              id="password"
              autocomplete="current-password"
              required
              v-model="data.password"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
          >
            Sign in
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Not a member?
        <RouterLink to="/signup" class="font-semibold text-indigo-600 hover:text-indigo-500">
          Create an account
        </RouterLink>
      </p>
    </div>
  </GuestLayout>
</template>

<style scoped>
</style>