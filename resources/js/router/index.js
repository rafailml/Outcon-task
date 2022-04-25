import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store";

import Home from "@/js/components/Home";
import Profile from "@/js/components/Profile";
import Managers from "@/js/components/Managers";
import Employees from "@/js/components/Employees";
import Login from "@/js/views/auth/Login";
import Register from "@/js/views/auth/Register";

Vue.use(VueRouter);

const Routes = [
    {
        path: "/",
        name: "home",
        component: Home,
        meta: {
            middleware: "guest",
            title: `Home`,
        },
    },
    {
        path: "/profile",
        name: "profile",
        component: Profile,
        meta: {
            middleware: "auth",
            title: `Profile`,
        },
    },
    {
        path: "/managers",
        name: "managers",
        component: Managers,
        meta: {
            middleware: "auth",
            title: `Managers`,
        },
    },
    {
        path: "/employees",
        name: "employees",
        component: Employees,
        meta: {
            middleware: "auth",
            title: `Employees`,
        },
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        meta: {
            layout: "auth",
            middleware: "guest",
            title: `Login`,
        },
    },
    {
        path: "/register",
        name: "register",
        component: Register,
        meta: {
            layout: "auth",
            middleware: "guest",
            title: `Register`,
        },
    },
];

var router = new VueRouter({
    mode: "history",
    routes: Routes,
});

router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title} - ${process.env.MIX_APP_NAME}`;
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "profile" });
        }
        next();
    } else {
        if (store.state.auth.authenticated) {
            next();
        } else {
            next({ name: "login" });
        }
    }
});

export default router;
