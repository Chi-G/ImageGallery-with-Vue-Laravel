<script setup>
import { onMounted, ref, computed, watch } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios.js";

const route = useRoute();
const images = ref([]);
const dates = ref([]);
const error = ref(null);

// Filter states
const searchName = ref("");
const filterDate = ref("");
const filterLabel = ref(""); // New filter for label

const currentDate = computed(() => route.params.date || null);

async function fetchImages() {
  const endpoint = currentDate.value ? `/api/images/date/${currentDate.value}` : "/api/images";
  try {
    const response = await axiosClient.get(endpoint, {
      params: {
        name: searchName.value || undefined,
        date: filterDate.value || undefined,
        label: filterLabel.value || undefined, // Send label filter
      },
    });
    images.value = response.data.images || response.data;
    if (!currentDate.value) {
      dates.value = response.data.dates || [];
    }
    error.value = null;
  } catch (err) {
    console.error("Failed to fetch images:", err.response?.data || err.message);
    error.value = err.response?.data?.error || "Failed to load images";
  }
}

async function copyImageUrl(url) {
  await navigator.clipboard.writeText(url);
  alert("Image URL copied to clipboard!");
}

function deleteImage(id) {
  if (!confirm("Are you sure you want to delete this image?")) return;
  axiosClient.delete(`/api/images/${id}`).then(() => {
    images.value = images.value.filter((image) => image.id !== id);
  });
}

// Fetch images initially and when filters change
onMounted(fetchImages);
watch([searchName, filterDate, filterLabel], () => fetchImages(), { debounce: 300 }); // Include filterLabel in watch
</script>

<template>
  <header class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        My Images {{ currentDate ? `(${currentDate})` : "" }}
      </h1>
    </div>
  </header>
  <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <!-- Error Display -->
      <div v-if="error" class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        {{ error }}
      </div>

      <!-- Filters -->
      <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <label for="filter-label" class="block text-sm font-medium text-gray-900">Filter by Label Name</label>
            <input
              id="filter-label"
              v-model="filterLabel"
              type="text"
              placeholder="Enter label..."
              class="mt-1 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
            />
          </div>
          <div class="flex-1">
            <label for="filter-date" class="block text-sm font-medium text-gray-900">Filter by Date</label>
            <input
              id="filter-date"
              v-model="filterDate"
              type="date"
              class="mt-1 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
            />
          </div>
          
        </div>
      </div>

      <!-- Date Navigation (Folders) -->
      <div v-if="!currentDate" class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900">Folders by Date</h2>
        <div class="mt-2 flex flex-wrap gap-2">
          <RouterLink
            v-for="date in dates"
            :key="date"
            :to="{ name: 'ImagesByDate', params: { date } }"
            class="rounded-md bg-gray-200 px-3 py-1 text-sm font-medium text-gray-700 hover:bg-gray-300"
          >
            {{ date }}
          </RouterLink>
        </div>
      </div>

      <!-- Image Grid -->
      <div v-if="images.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <div v-for="image in images" :key="image.id" class="bg-white overflow-hidden shadow rounded-lg">
          <img :src="image.url" :alt="image.name" class="w-full h-48 object-contain" />
          <div class="px-4 py-4">
            <p class="text-sm text-gray-500 mb-4">{{ image.label }}</p>
            <div class="flex justify-between">
              <button
                @click="copyImageUrl(image.url)"
                class="rounded-md bg-indigo-600 px-3 py-1 text-sm font-semibold text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
              >
                Copy URL
              </button>
              <button
                @click="deleteImage(image.id)"
                class="rounded-md bg-red-600 px-3 py-1 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-700 focus:ring-offset-2"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
      <p v-else class="text-gray-500">No images found {{ currentDate ? `for ${currentDate}` : "" }}.</p>
    </div>
  </main>
</template>