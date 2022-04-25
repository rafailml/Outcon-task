<template>
    <v-app>
        <v-app-bar app dense>
            <v-toolbar-title>Page title</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn to="login" v-if="!$store.state.auth.authenticated">
                Log in
            </v-btn>

            <v-menu left bottom v-else>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on">
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item to="profile">
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item>
                    <v-list-item
                        to="managers"
                        v-if="user.data && user.data.role === 'Employee'"
                    >
                        <v-list-item-title>Managers</v-list-item-title>
                    </v-list-item>
                    <v-list-item
                        to="employees"
                        v-if="user.data && user.data.role === 'Manager'"
                    >
                        <v-list-item-title>Employees</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="logout()">
                        <v-list-item-title>Log Out</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <!-- Sizes your content based upon application components -->
        <v-main>
            <!-- Provides the application the proper gutter -->
            <v-container fluid>
                <!-- If using vue-router -->
                <router-view></router-view>
            </v-container>
        </v-main>

        <v-footer app>
            <!-- -->
        </v-footer>
    </v-app>
</template>

<script>
import { mapActions } from "vuex";
export default {
    data() {
        return {};
    },
    computed: {
        user() {
            return this.$store.state.auth.user;
        },
    },

    methods: {
        ...mapActions({
            signOut: "auth/logout",
        }),
        async logout() {
            await axios.post("/api/logout").then(() => {
                this.signOut();
                this.$router.push({ name: "login" });
            });
        },
    },
};
</script>

<style scoped></style>
