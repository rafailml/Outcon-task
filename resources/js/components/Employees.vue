<template>
    <div>
        <h1 class="mb-5">Employees</h1>
        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    prepend-icon="mdi-magnify"
                    append-icon="mdi-close"
                    label="Search"
                    single-line
                    hide-details
                    @click:append="search = ''"
                    @keyup.enter.native="getEmployees()"
                ></v-text-field>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="employees"
                :search="search"
                :options.sync="options"
                :server-items-length="pagination.total"
                :loading="isLoading"
                :footer-props="{
                    itemsPerPageOptions: [10, 15, 20],
                }"
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
            employees: [],
            pagination: {},
            options: {
                itemsPerPage: 15,
            },
            headers: [
                { text: "Id", value: "id" },
                { text: "Name", value: "name" },
                { text: "Last Name", value: "last_name" },
                { text: "Email", value: "email" },
                { text: "Role", value: "role" },
            ],
            isLoading: false,
        };
    },
    watch: {
        options: {
            handler() {
                this.getEmployees();
            },
            deep: true,
        },
    },
    methods: {
        searchManager() {},
        async getEmployees() {
            const { sortBy, sortDesc, page, itemsPerPage } = this.options;
            const sortByParam = sortBy[0] ? `&sort=${sortBy[0]}` : "";
            const sortDescParam = sortDesc[0] ? `&sort_desc=1` : "";
            const sortSearchParam = this.search ? `&search=${this.search}` : "";

            this.isLoading = true;
            await axios.get("/sanctum/csrf-cookie");
            await axios
                .get(
                    `/api/user/employees?page=${page}` +
                        `&items=${itemsPerPage}` +
                        sortByParam +
                        sortDescParam +
                        sortSearchParam
                )
                .then(({ data }) => {
                    this.employees = data.employees.data;
                    this.pagination = data.employees.pagination;
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style scoped></style>
