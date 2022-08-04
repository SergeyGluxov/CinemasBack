<template>
    <div class="container-fluid">
        <!-- Page Content -->
        <h1>{{users}}</h1>
    </div>

</template>

<script>
    export default {
        name: "UsersTableComponent",
        data: function () {
            return {
                users: [],
            }
        },

        mounted() {
            this.update();
        },
        methods: {
            update: function () {
                axios.get('/api/users').then((response) => {
                    this.users = response.data;
                    console.log(response.data);
                });
            }
        }
    }
</script>

<style scoped>

</style>
