import {createRouter, createWebHistory} from "vue-router";
import ENV from '../config/env';
import appService from "../services/appService";
import DashboardComponent from "../components/admin/dashboard/DashboardComponent.vue";
import NotFoundComponent from "../components/frontend/otherPage/NotFoundComponent.vue";
import ExceptionComponent from "../components/frontend/otherPage/ExceptionComponent.vue";
import store from "../store";
import authRoutes from "./modules/authRoutes";
import settingRoutes from "./modules/settingRoutes";
import offerRoutes from "./modules/offerRoutes";
import itemRoutes from "./modules/itemRoutes";
import couponRoutes from "./modules/couponRoutes";
import onlineOrderRoutes from "./modules/onlineOrderRoutes";
import pushNotificationRoutes from "./modules/pushNotificationRoutes";
import customerRoutes from "./modules/customerRoutes";
import administratorRoutes from "./modules/administratorRoutes";
import deliveryBoyRoutes from "./modules/deliveryBoyRoutes";
import employeeRoutes from "./modules/employeeRoutes";
import frontendRoutes from "./modules/frontendRoutes";
import salesReportRoutes from "./modules/salesReportRoutes";
import itemsReportRoutes from "./modules/itemsReportRoutes";
import posRoutes from "./modules/posRoutes";
import messageRoutes from "./modules/messageRoutes";
import profileRoutes from "./modules/profileRoutes";
import posOrderRoutes from "./modules/posOrderRoutes";
import transactionRoutes from "./modules/transactionRoutes";
import creditBalanceReportRoutes from "./modules/creditBalanceReportRoutes";
import pointBalanceReportRoutes from "./modules/pointBalanceReportRoutes";
import tableOrderRoutes from "./modules/tableOrderRoutes";
import adminTableOrderRoutes from "./modules/adminTableOrderRoutes";
import diningTableRoutes from "./modules/diningTableRoutes";
import subscriberRoutes from "./modules/subscriberRoutes";
import copyItemRoutes from "./modules/copyItemRoutes";


const baseRoutes = [
    {
        path: "/",
        redirect: {name: "frontend.home"},
        name: "root"
    },
    {
        path: "/:pathMatch(.*)*",
        name: "route.notFound",
        component: NotFoundComponent,
        meta: {
            isFrontend: true,
        },
    },
    {
        path: "/exception",
        name: "route.exception",
        component: ExceptionComponent,
    },
    {
        path: "/admin/dashboard",
        component: DashboardComponent,
        name: "admin.dashboard",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "dashboard",
            breadcrumb: "dashboard",
        },
    },
];

const routes = baseRoutes.concat(
    frontendRoutes,
    authRoutes,
    settingRoutes,
    offerRoutes,
    itemRoutes,
    pushNotificationRoutes,
    couponRoutes,
    onlineOrderRoutes,
    customerRoutes,
    deliveryBoyRoutes,
    administratorRoutes,
    employeeRoutes,
    salesReportRoutes,
    itemsReportRoutes,
    messageRoutes,
    profileRoutes,
    posRoutes,
    posOrderRoutes,
    transactionRoutes,
    creditBalanceReportRoutes,
    pointBalanceReportRoutes,
    tableOrderRoutes,
    adminTableOrderRoutes,
    diningTableRoutes,
    subscriberRoutes,
    copyItemRoutes
);

const permission = store.getters.authPermission;
appService.recursiveRouter(routes, permission);

const API_URL = ENV.API_URL;
const router = createRouter({
    linkActiveClass: "active",
    mode: 'history',
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        return { left: 0, top: 0}
    }
});

router.beforeEach((to, from, next) => {
    if (to.meta.auth === true) {
        if (!store.getters.authStatus) {
            next({name: "auth.login"});
        } else {
            if (to.meta.isFrontend === false) {
                if (to.meta.access === false) {
                    next({
                        name: "route.exception",
                    });
                } else {
                    next();
                }
            } else {
                next();
            }
        }
    } else if (to.name === "auth.login" && store.getters.authStatus) {
        next({name: "frontend.home"});
    } else {
        next();
    }
});
export default router;
