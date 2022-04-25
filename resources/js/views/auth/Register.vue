<template>
    <div>
        <v-card class="text-center pa-1">
            <v-card-title class="justify-center display-1 mb-2"
                >Register</v-card-title
            >
            <v-card-subtitle>In our amazing platform</v-card-subtitle>
            <!-- sign up form -->
            <v-card-text>
                <v-form ref="form">
                    <v-text-field
                        v-model="form.name"
                        label="Name"
                        name="name"
                        outlined
                    ></v-text-field>

                    <v-text-field
                        v-model="form.last_name"
                        label="Last Name"
                        name="last_name"
                        outlined
                    ></v-text-field>

                    <v-text-field
                        v-model="form.email"
                        label="Email"
                        name="email"
                        outlined
                    ></v-text-field>

                    <v-text-field
                        v-model="form.password"
                        label="Password"
                        name="password"
                        type="password"
                        outlined
                    ></v-text-field>

                    <v-text-field
                        v-model="form.password_confirmation"
                        label="Password Confirmation"
                        name="password_confirmation"
                        type="password"
                        outlined
                    ></v-text-field>

                    <v-btn
                        :loading="isLoading"
                        block
                        x-large
                        color="primary"
                        @click="register"
                        >Register</v-btn
                    >
                </v-form>
            </v-card-text>
        </v-card>

        <div class="text-center mt-6">
            Have an account?
            <router-link to="login" class="font-weight-bold">
                Login
            </router-link>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    data() {
        return {
            form: {
                name: "",
                last_name: "",
                email: "",
                password: "",
                password_confirmation: "",
            },
            isLoading: false,
        };
    },
    methods: {
        ...mapActions({
            getUser: "auth/getUser",
        }),
        async register() {
            this.isLoading = true;
            await axios
                .post("/api/register", this.form)
                .then(() => {
                    this.getUser();
                    this.$router.push("/");
                })
                .catch(({ response: { data } }) => {
                    alert(data.message);
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style scoped></style>
