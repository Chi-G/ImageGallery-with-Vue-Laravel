import { defineStore } from "pinia";
import axiosClient from "../axios.js";
import router from "../router.js";

export const useUserStore = defineStore("user", {
  state: () => ({
    user: null,
    loading: false,
    error: null,
  }),
  actions: {
    async fetchUser() {
      this.loading = true;
      this.error = null; 
      try {
        const { data } = await axiosClient.get("/api/user");
        this.user = data;
      } catch (error) {
        this.error = error.response?.data?.message || "Failed to fetch user";
        this.user = null;
      } finally {
        this.loading = false; 
      }
    },
    async logout() {
      this.loading = true;
      try {
        await axiosClient.post("/logout");
        this.user = null;
        router.push({ name: "Login" });
      } catch (error) {
        this.error = error.response?.data?.message || "Logout failed";
      } finally {
        this.loading = false;
      }
    },
  },
});

export default useUserStore;