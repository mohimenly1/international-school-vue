import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Tables from "../views/Tables.vue";
import Billing from "../views/Billing.vue";
import RTL from "../views/Rtl.vue";
import Notifications from "../views/Notifications.vue";
import Profile from "../views/Profile.vue";
import SignIn from "../views/SignIn.vue";
import SignUp from "../views/SignUp.vue";
import Students from "../views/Students/Students.vue";
import Parents from "../views/Parents/Parents.vue";
import Salaries from "../views/Salaries/Salaries.vue";
import ManageSalaries from "../views/ManageSalaries/ManageSalaries.vue";
import Users from "../views/Users/Users.vue";
import Attendances from "../views/Attendances/Attendances.vue";
import Employees from "../views/Employees/Employees.vue";
import EmployeeDetails from "../views/EmployeeDetails/EmployeeDetails.vue";
import SummerCamp from "../views/SummerCamp/SummerCampAdd.vue";
import SummerCampData from "../views/SummerCamp/SummerCampData.vue";
import Classes from "../views/Classes/Classes.vue";
import Departments from "../views/Departments/Departments.vue";
import SchoolSchedule from "../views/SchoolSchedule/SchoolSchedule.vue";
import TeacherType from "../views/TeacherType/TeacherType.vue";
import ClassesSections from "../views/ClassesSections/ClassesSections.vue";
import Exams from "../views/Exams/Exams.vue";
import Buses from "../views/Buses/Buses.vue";
import store from "../store"; // Import Vuex store
const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "Dashboard",
      component: Dashboard,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/tables",
      name: "Tables",
      component: Tables,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/billing",
      name: "Billing",
      component: Billing,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/buses",
      name: "Buses",
      component: Buses,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/exams",
      name: "Manage Exams",
      component: Exams,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/classes-sections",
      name: "Classes Sections",
      component: ClassesSections,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/rtl-page",
      name: "RTL",
      component: RTL,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/notifications",
      name: "Notifications",
      component: Notifications,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/profile",
      name: "Profile",
      component: Profile,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/sign-in",
      name: "SignIn",
      component: SignIn,
    },
    {
      path: "/students",
      name: "Students",
      component: Students,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/users",
      name: "Users",
      component: Users,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/parents",
      name: "Parents",
      component: Parents,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/salaries",
      name: "Salary Management",
      component: Salaries,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/manage-salaries",
      name: "Manage Salaries",
      component: ManageSalaries,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/sign-up",
      name: "SignUp",
      component: SignUp,
         meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/classes",
      name: "classes",
      component: Classes,
      meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/departments",
      name: "departments",
      component: Departments,
      meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/teachers-type-subject",
      name: "TeacherType",
      component: TeacherType,
      meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/school-schedule",
      name: "SchoolSchedule",
      component: SchoolSchedule,
      meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/employees",
      name: "Employees",
      component: Employees,
      meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/employee-details",
      name: "EmployeeDetails",
      component: EmployeeDetails,
      meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/summer-camp-data",
      name: "SummerCampData",
      component: SummerCampData,
      meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/summer-camp-add",
      name: "SummerCamp",
      component: SummerCamp,
      meta: { requiresAuth: true }, // Add meta field for authentication requirement
    },
    {
      path: "/attendances",
      name: "Attendances",
      component: Attendances,
      meta: {
        store: store, // Ensure Vuex store is attached to meta
      },
      beforeEnter: (to, from, next) => {
        // Check if Vuex store is attached to route meta
        if (to.meta && to.meta.store) {
          to.meta.store.dispatch('hideAllElements');
        } else {
          console.error('Vuex store not attached to route meta!');
        }
        next();
      },
    },
    {
      path: "/:catchAll(.*)",
      redirect: "/",
    },
  ],
  linkActiveClass: "active",
});

router.beforeEach((to, from, next) => {
  if (from.name === 'Attendances' && from.meta && from.meta.store) {
    from.meta.store.dispatch('showAllElements');
  }
  next();
});


// Add navigation guard to check for token
router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const token = localStorage.getItem('access_token');

  if (requiresAuth && !token) {
    // If the route requires authentication and the token is not present, redirect to SignIn
    next({ name: 'SignIn' });
  } else {
    next();
  }
});

export default router;
