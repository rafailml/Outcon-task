<template>
    <div>
        <v-card class="text-center pa-1">
            <v-card-title class="justify-center display-1 mb-2"
                >Welcome</v-card-title
            >
            <v-card-subtitle>Sign in to your account</v-card-subtitle>

            <!-- sign in form -->
            <v-card-text>
                <v-form ref="form">
                    <v-text-field
                        v-model="form.email"
                        label="Email"
                        name="email"
                        outlined
                    ></v-text-field>

                    <v-text-field
                        v-model="form.password"
                        type="password"
                        label="Password"
                        name="password"
                        outlined
                    ></v-text-field>

                    <v-btn
                        :loading="isLoading"
                        block
                        x-large
                        color="primary"
                        @click="login"
                        >Login</v-btn
                    >
                </v-form>
            </v-card-text>
        </v-card>

        <div class="text-center mt-6">
            No account ?
            <router-link to="register" class="font-weight-bold">
                Register here
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
                email: "",
                password: "",
            },
            isLoading: false,
        };
    },
    methods: {
        ...mapActions({
            getUser: "auth/getUser",
        }),
        async login() {
            this.isLoading = true;
            await axios.get("/sanctum/csrf-cookie");
            await axios
                .post("/api/login", this.form)
                .then(() => {
                    this.getUser();
                    this.$router.push("/");
                })
                .catch(({ response: { data } }) => {
                    alert(data.message);
                })
                .finally(() => {
                    this.processing = false;
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style scoped></style>
