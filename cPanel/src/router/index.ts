import { createRouter, createWebHistory } from "vue-router";
import Layout from "@/themes";
const routes = [{
  path: "/",
  component: Layout,
  meta: { 
    requiresAuth: true,
    requiresVerification: true, 
  },
  children: [
      {
        path: "/",
        name: "Dashboard",
        component: () => import("../pages/DashboardOverview3.vue"),
      },

    ],
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("../views/Auth/Login.vue"),
  },
  {
    path: "/forgot-password",
    name: "ForgotPasswordL",
    component: () => import("../views/Auth/ForgotPassword.vue"),
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("../views/Auth/Register.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 };
  },
});

router.beforeEach((to, from, next) => {
    let token = localStorage.getItem("TOKEN");
    
    let user = JSON.parse(localStorage.getItem("USER")) ?? '';

    if (to.meta.requiresAuth && (token === 'undefined' || !token)) {
      next({ name: "Login" });
    } else if (token && to.meta.requiresVerification && user.email_verified_at === null) {
      next({ name: "VerifyError" })
    } else if (token && to.meta.isGuest) {
      next({ name: "Dashboard" })
    } else {
      next();
    }
});

export default router;
