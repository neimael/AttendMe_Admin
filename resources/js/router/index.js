import { createRouter, createWebHistory } from "vue-router";

import axios from 'axios';

import Home from "../pages/HomeView.vue"

const routes = [
 
  {
    // Document title tag
    // We combine it with defaultDocumentTitle set in `src/main.js` on router.afterEach hook
    
    path: "/dashboard",
    name: "dashboard",
    component: Home,
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    // Document title tag
    // We combine it with defaultDocumentTitle set in `src/main.js` on router.afterEach hook
    meta: {
      title: "Login",
    },
    path: "/",
    name: "login",
    component: import("../pages/LoginView.vue"),
  },
  //manage users
  {
 
    path: "/admins",
    name: "admins",
    component: () => import("../pages/AdminView.vue"),
    meta: {
      title: "Admins",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  
  {
   
    path: "/add-admin",
    name: "add-admin",
    component: () => import("../pages/NewAdminView.vue"),
    meta: {
      title: "AddAdmin",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
  
    path: "/update-admin/:id",
    name: "update-admin",
    component: () => import("../pages/UpdateAdminView.vue"),
    meta: {
      title: "UpdateAdmin",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  //manage employees
  {
    
    path: "/employees",
    name: "employees",
    component: () => import("../pages/EmployeeView.vue"),
    meta: {
      title: "Employees",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
   
    path: "/update-employee/:id",
    name: "update-employee",
    component: () => import("../pages/UpdateEmployeeView.vue"),
    meta: {
      title: "UpdateEmployee",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  
  {
   
    path: "/add-employee",
    name: "add-employee",
    component: () => import("../pages/NewEmployeeView.vue"),
    meta: {
      title: "AddEmployee",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
   
    path: "/employee-presence/:id",
    name: "employee-presence",
    component: () => import("../pages/EmployeePresence.vue"),
    meta: {
      title: "EmployeePresence",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
   
    path: "/add-manual-presence/:id",
    name: "add-manual-presence",
    component: () => import("../pages/NewEmployeePresenceView.vue"),
    meta: {
      title: "AddManualPresence",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },

  //manage elevators
  {
  
    path: "/elevators",
    name: "elevators",
    component: () => import("../pages/ElevatorView.vue"),
    meta: {
      title: "Elevators",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  
  {
   
    path: "/add-elevator",
    name: "add-elevator",
    component: () => import("../pages/NewElevatorView.vue"),
    meta: {
      title: "AddElevator",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
   
    path: "/detail-elevator/:id",
    name: "detail-elevator",
    component: () => import("../pages/DetailElevator.vue"),
    meta: {
      title: "DetailElevators",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
   
    path: "/update-elevator/:id",
    name: "update-elevator",
    component: () => import("../pages/UpdateElevatorView.vue"),
    meta: {
      title: "UpdateElevators",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },

  //manage attendance
  {
    
    path: "/attendances",
    name: "attendances",
    component: () => import("../pages/AttendanceView.vue"),
    meta: {
      title: "Attendance",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },

  //manage Asignements
  {
    
    path: "/assignments",
    name: "assignments",
    component: () => import("../pages/AssignmentView.vue"),
    meta: {
      title: "Assignments",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
   
    path: "/add-assignment",
    name: "add-assignment",
    component: () => import("../pages/NewAssignmentView.vue"),
    meta: {
      title: "AddAssignments",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
   
    path: "/update-assignment/:id",
    name: "update-assigment",
    component: () => import("../pages/UpdateAssignmentView.vue"),
    meta: {
      title: "UpdateAssignments",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  
  //manage regulations
  {
   
    path: "/sanitary-issues",
    name: "sanitaryIssues",
    component: () => import("../pages/SanitaryIssuesView.vue"),
    meta: {
      title: "SanitaryIssues",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
  
    path: "/technical-issues",
    name: "technicalIssues",
    component: () => import("../pages/TechnicalIssuesView.vue"),
    meta: {
      title: "TechnicalIssues",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
      title: "Profile",
      requiresAuth: true // Add meta field to indicate authentication requirement
    },
    path: "/profile",
    name: "profile",
    component: () => import("../pages/ProfileView.vue"),
  
  },
 
  {
    meta: {
      title: "Error",
    },
    path: "/error",
    name: "error",
    component: () => import("../pages/ErrorView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 };
  },
});

router.beforeEach(async (to, from, next) => {
  const response = await axios.get('/api/checkAuthStatus');
  const { authenticated } = response.data;

  if (to.matched.some(record => record.meta.requiresAuth) && !authenticated) {
    // User is not authenticated, redirect to login page
    next('/');
  } else {
    // User is authenticated or route doesn't require authentication
    next();
  }
});

export default router;
