<template>
    <div>
        <v-card class="text-center pa-1">
            <v-card-title class="justify-center display-1 mb-2"
                >Profile</v-card-title
            >
            <v-card-subtitle>Edit your profile here</v-card-subtitle>
            <!-- sign up form -->
            <v-card-text>
                <v-form ref="form">
                    <v-text-field
                        v-model="form.name"
                        label="Name"
                        name="name"
                        outlined
                        :error-messages="nameErrors"
                        @blur="$v.form.name.$touch()"
                        @input="$v.form.name.$reset()"
                    ></v-text-field>

                    <v-text-field
                        v-model="form.last_name"
                        label="Last Name"
                        name="last_name"
                        outlined
                        :error-messages="lastNameErrors"
                        @blur="$v.form.last_name.$touch()"
                        @input="$v.form.last_name.$reset()"
                    ></v-text-field>

                    <v-text-field
                        v-model="form.email"
                        label="Email"
                        name="email"
                        outlined
                        :error-messages="emailErrors"
                        @blur="$v.form.email.$touch()"
                        @input="$v.form.email.$reset()"
                    ></v-text-field>

                    <v-img
                        :aspect-ratio="4 / 3"
                        width="300"
                        :src="'storage/profile_pics/' + user.data.photo"
                        class="mb-7"
                    ></v-img>

                    <v-file-input
                        accept="image/png, image/jpeg, image/bmp"
                        placeholder="Pick a profile image"
                        prepend-icon="mdi-camera"
                        label="Profile image"
                        show-size
                        :rules="profileImageRules"
                        v-model="profileImage"
                    ></v-file-input>

                    <v-divider class="mb-5"></v-divider>

                    <v-text-field
                        v-model="form.password"
                        label="Password"
                        name="password"
                        type="password"
                        outlined
                        :error-messages="passwordErrors"
                        @blur="$v.form.password.$touch()"
                        @input="$v.form.password.$reset()"
                    ></v-text-field>

                    <v-text-field
                        v-model="form.password_confirmation"
                        label="Password Confirmation"
                        name="password_confirmation"
                        type="password"
                        outlined
                        :error-messages="passwordConfirmationErrors"
                        @blur="$v.form.password_confirmation.$touch()"
                        @input="$v.form.password_confirmation.$reset()"
                    ></v-text-field>

                    <v-btn
                        :loading="isLoading"
                        block
                        x-large
                        color="primary"
                        @click="submit"
                        >Save Profile</v-btn
                    >
                </v-form>

                <v-snackbar
                    v-model="showProfileEditAlert"
                    timeout="2000"
                    absolute
                    bottom
                    color="success"
                    outlined
                >
                    User profile edited!

                    <template v-slot:action="{ attrs }">
                        <v-btn
                            color="blue"
                            text
                            v-bind="attrs"
                            @click="showProfileEditAlert = false"
                        >
                            Close
                        </v-btn>
                    </template>
                </v-snackbar>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapActions } from "vuex";
import { validationMixin } from "vuelidate";
import {
    email,
    sameAs,
    minLength,
    maxLength,
    required,
} from "vuelidate/lib/validators";
export default {
    name: "Profile",
    mixins: [validationMixin],
    validations: {
        form: {
            name: { required, maxLength: maxLength(255) },
            last_name: { required, maxLength: maxLength(255) },
            email: {
                email,
                required,
                maxLength: maxLength(255),
            },
            password: { minLength: minLength(8), maxLength: maxLength(255) },
            password_confirmation: {
                sameAs: sameAs("password"),
                minLength: minLength(8),
                maxLength: maxLength(255),
            },
        },
    },
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
            profileImage: null,
            profileImageRules: [
                (value) =>
                    !value ||
                    value.size < 2000000 ||
                    "Image size should be less than 2 MB!",
            ],
            showProfileEditAlert: false,
        };
    },
    mounted() {
        this.getUser();
        this.form.name = this.user.data.name;
        this.form.last_name = this.user.data.last_name;
        this.form.email = this.user.data.email;
    },
    computed: {
        nameErrors() {
            const errors = [];

            if (!this.$v.form.name.$dirty) return errors;

            !this.$v.form.name.required &&
                errors.push("#TRS The name is required.");
            !this.$v.form.name.maxLength &&
                errors.push(
                    "#TRS Role name should be maximum 255 characters long."
                );

            return errors;
        },
        lastNameErrors() {
            const errors = [];

            if (!this.$v.form.last_name.$dirty) return errors;

            !this.$v.form.last_name.required &&
                errors.push("#TRS The last name is required.");
            !this.$v.form.last_name.maxLength &&
                errors.push(
                    "#TRS Role name should be maximum 255 characters long."
                );

            return errors;
        },
        emailErrors() {
            const errors = [];

            if (!this.$v.form.email.$dirty) return errors;

            !this.$v.form.email.required &&
                errors.push("#TRS The email is required.");
            !this.$v.form.email.email &&
                errors.push("#TRS Must be valid e-mail");
            !this.$v.form.email.maxLength &&
                errors.push(
                    "#TRS Email should be maximum 255 characters long."
                );

            return errors;
        },
        passwordErrors() {
            const errors = [];

            if (!this.$v.form.password.$dirty) return errors;

            !this.$v.form.password.minLength &&
                errors.push(
                    "#TRS The password should be at least 8 symbols including numbers and special chars."
                );
            !this.$v.form.password.maxLength &&
                errors.push("#TRS The password can be maximum 255 characters");

            return errors;
        },
        passwordConfirmationErrors() {
            const errors = [];

            if (!this.$v.form.password_confirmation.$dirty) return errors;

            !this.$v.form.password_confirmation.minLength &&
                errors.push(
                    "#TRS The password should be at least 8 symbols including numbers and special chars."
                );
            !this.$v.form.password_confirmation.maxLength &&
                errors.push("#TRS The password can be maximum 255 characters");
            !this.$v.form.password_confirmation.sameAs &&
                errors.push(
                    "#TRS Password confirmation should match password."
                );

            return errors;
        },
        user() {
            return this.$store.state.auth.user;
        },
    },
    methods: {
        ...mapActions({
            getUser: "auth/getUser",
        }),
        async submit() {
            this.$v.$touch();

            if (!this.$v.$invalid) {
                this.isLoading = true;

                // Update image
                if (this.profileImage) {
                    await this.uploadFile();
                }

                // Update profile
                await this.editProfile();
            }
        },
        async editProfile() {
            await axios.get("/sanctum/csrf-cookie");
            await axios
                .put("/api/user/profile", this.form)
                .then(() => {
                    this.getUser();
                })
                .catch(({ response: { data } }) => {
                    console.error(data.message);
                })
                .finally(() => {
                    this.isLoading = false;
                    this.showProfileEditAlert = true;
                });
        },
        async uploadFile() {
            await axios.get("/sanctum/csrf-cookie");

            let formData = new FormData();
            formData.append("picture", this.profileImage);
            await axios
                .post("api/user/profile/image-upload", formData)
                .then(() => {})
                .catch((error) => {
                    alert(error);
                });
        },
    },
};
</script>

<style scoped></style>
