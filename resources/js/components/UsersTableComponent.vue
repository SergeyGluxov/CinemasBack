<template>
    <div class="container-fluid">
        <!-- Page Content -->
        <h1>{{users}}</h1>
        <a href="intent:#Intent;action=ru.cinemas.glukhov;category=android.intent.category.DEFAULT;category=android.intent.category.BROWSABLE;S.msg_from_browser=Launched%20from%20Browser;end">Click
            this link to launch you app from web</a>
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
