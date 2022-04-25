<template>
    <div>
        <h1 class="mb-5">Managers</h1>
        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="managers"
                :search="search"
                :loading="isLoading"
                loading-text="Loading... Please wait"
            ></v-data-table>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            search: "",
            managers: [],
            headers: [
                { text: "Name", value: "name" },
                {
                    text: "Last Name",
                    value: "last_name",
                },
                { text: "Email", value: "email" },
                { text: "Role", value: "role" },
            ],
            isLoading: false,
        };
    },
    methods: {
        searchManager() {},
    },
    async mounted() {
        this.isLoading = true;
        await axios.get("/sanctum/csrf-cookie");
        await axios.get("/api/user/managers").then(({ data }) => {
            this.managers = data.managers.data;
            this.isLoading = false;
        });
    },
};
</script>

<style scoped></style>
