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
    meta: {
      title: "Admins",
    },
    path: "/admins",
    name: "admins",
    component: () => import("../pages/AdminView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  
  {
    meta: {
      title: "AddAdmin",
    },
    path: "/add-admin",
    name: "add-admin",
    component: () => import("../pages/NewAdminView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
      title: "UpdateAdmin",
    },
    path: "/update-admin/:id",
    name: "update-admin",
    component: () => import("../pages/UpdateAdminView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  //manage employees
  {
    meta: {
      title: "Employees",
    },
    path: "/employees",
    name: "employees",
    component: () => import("../pages/EmployeeView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
      title: "UpdateEmployee",
    },
    path: "/update-employee/:id",
    name: "update-employee",
    component: () => import("../pages/UpdateEmployeeView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  
  {
    meta: {
      title: "AddEmployee",
    },
    path: "/add-employee",
    name: "add-employee",
    component: () => import("../pages/NewEmployeeView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
      title: "EmployeePresence",
    },
    path: "/employee-presence/:id",
    name: "employee-presence",
    component: () => import("../pages/EmployeePresence.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
      title: "AddManualPresence",
    },
    path: "/add-manual-presence/:id",
    name: "add-manual-presence",
    component: () => import("../pages/NewEmployeePresenceView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },

  //manage elevators
  {
    meta: {
      title: "Elevators",
    },
    path: "/elevators",
    name: "elevators",
    component: () => import("../pages/ElevatorView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  
  {
    meta: {
      title: "AddElevator",
    },
    path: "/add-elevator",
    name: "add-elevator",
    component: () => import("../pages/NewElevatorView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
      title: "DetailElevators",
    },
    path: "/detail-elevator/:id",
    name: "detail-elevator",
    component: () => import("../pages/DetailElevator.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
      title: "UpdateElevators",
    },
    path: "/update-elevator/:id",
    name: "update-elevator",
    component: () => import("../pages/UpdateElevatorView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },

  //manage attendance
  {
    meta: {
    title: "Attendance",
    },
    path: "/attendances",
    name: "attendances",
    component: () => import("../pages/AttendanceView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },

  //manage Asignements
  {
    meta: {
    title: "Assignments",
    },
    path: "/assignments",
    name: "assignments",
    component: () => import("../pages/AssignmentView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
    title: "AddAssignments",
    },
    path: "/add-assignment",
    name: "add-assignment",
    component: () => import("../pages/NewAssignmentView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
      title: "UpdateAssignments",
    },
    path: "/update-assignment/:id",
    name: "update-assigment",
    component: () => import("../pages/UpdateAssignmentView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  
  //manage regulations
  {
    meta: {
    title: "SanitaryIssues",
    },
    path: "/sanitary-issues",
    name: "sanitaryIssues",
    component: () => import("../pages/SanitaryIssuesView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
    title: "TechnicalIssues",
    },
    path: "/technical-issues",
    name: "technicalIssues",
    component: () => import("../pages/TechnicalIssuesView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
  },
  {
    meta: {
      title: "Profile",
    },
    path: "/profile",
    name: "profile",
    component: () => import("../pages/ProfileView.vue"),
    meta: {
      title: "Dashboard",
      requiresAuth: true // Add meta field to indicate authentication requirement
    }
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
