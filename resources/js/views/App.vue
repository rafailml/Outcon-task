<template>
    <v-app>
        <component :is="currentLayout" v-if="isRouterLoaded">
            <transition name="fade" mode="out-in">
                <router-view />
            </transition>
        </component>
    </v-app>
</template>

<script>
import authLayout from "../layouts/AuthLayout.vue";
import defaultLayout from "../layouts/DefaultLayout";
export default {
    name: "App",
    components: {
        defaultLayout,
        authLayout,
    },
    computed: {
        isRouterLoaded: function () {
            if (this.$route.name !== null) return true;

            return false;
        },
        currentLayout: function () {
            const layout = this.$route.meta.layout || "default";

            return layout + "Layout";
        },
    },
};
</script>

<style scoped></style>
