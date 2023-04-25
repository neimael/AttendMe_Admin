import { createRouter, createWebHashHistory } from "vue-router";


import Home from "../pages/HomeView.vue"

const routes = [
 
  {
    // Document title tag
    // We combine it with defaultDocumentTitle set in `src/main.js` on router.afterEach hook
    meta: {
      title: "Dashboard",
    },
    path: "/",
    name: "dashboard",
    component: Home,
  },
  //manage users
  {
    meta: {
      title: "Admins",
    },
    path: "/admins",
    name: "admins",
    component: () => import("../pages/AdminView.vue"),
  },
  
  {
    meta: {
      title: "AddAdmin",
    },
    path: "/add-admin",
    name: "add-admin",
    component: () => import("../pages/NewAdminView.vue"),
  },
  {
    meta: {
      title: "Employees",
    },
    path: "/employees",
    name: "employees",
    component: () => import("../pages/EmployeeView.vue"),
  },
  
  {
    meta: {
      title: "AddEmployee",
    },
    path: "/add-employee",
    name: "add-employee",
    component: () => import("../pages/NewEmployeeView.vue"),
  },
  //manage elevators
  {
    meta: {
      title: "Elevators",
    },
    path: "/elevators",
    name: "elevators",
    component: () => import("../pages/ElevatorView.vue"),
  },
  
  {
    meta: {
      title: "AddElevator",
    },
    path: "/add-elevator",
    name: "add-elevators",
    component: () => import("../pages/NewElevatorView.vue"),
  },
  //manage attendance
  {
    meta: {
    title: "Attendance",
    },
    path: "/attendances",
    name: "attendances",
    component: () => import("../pages/AttendanceView.vue"),
  },
  //manage Asignements
  {
    meta: {
    title: "Assignments",
    },
    path: "/assignments",
    name: "assignments",
    component: () => import("../pages/AsignementView.vue"),
  },
  {
    meta: {
      title: "Profile",
    },
    path: "/profile",
    name: "profile",
    component: () => import("../pages/ProfileView.vue"),
  },
 
 
  {
    meta: {
      title: "Login",
    },
    path: "/login",
    name: "login",
    component: () => import("../pages/LoginView.vue"),
  },
  {
    meta: {
      title: "Error",
    },
    path: "/error",
    name: "error",
    component: () => import("../pages/ErrorView.vue"),
  },

];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 };
  },
});

export default router;
