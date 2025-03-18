import { createRouter, createWebHistory } from "vue-router";
import DefaultLayout from "./components/DefaultLayout.vue";
import Home from "./pages/Home.vue";
import MyImages from "./pages/MyImages.vue";
import Login from "./pages/Login.vue";
import Signup from "./pages/Signup.vue";
import NotFound from "./pages/NotFound.vue";
import useUserStore from "./store/user.js";

const routes = [
  {
    path: "/",
    component: DefaultLayout,
    meta: { requiresAuth: true },
    children: [
      { path: "/", name: "Home", component: Home },
      { path: "/images", name: "MyImages", component: MyImages },
      { path: "/images/:date", name: "ImagesByDate", component: MyImages },
    ],
  },
  { path: "/login", name: "Login", component: Login },
  { path: "/signup", name: "Signup", component: Signup },
  {
    path: "/:pathMatch(.*)*",
    name: "NotFound",
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();
  if (to.meta.requiresAuth) {
    try {
      await userStore.fetchUser();
      if (!userStore.user) {
        return next({ name: "Login" });
      }
      next();
    } catch (error) {
      next({ name: "Login" });
    }
  } else {
    next();
  }
});

export default router;