<template>
    <div class="container-fluid">
        <!-- Page Content -->
        <a v-for="col in content" v-bind:href="'/admin/contents/'+col.id">
            <img v-bind:src="col.poster" class="col-lg-2 col-md-2 col-xs-2 thumb">
        </a>
    </div>

</template>

<script>
    export default {
        name: "AllContentTableComponent",
        data: function () {
            return {
                content: [],
            }
        },

        mounted() {
            this.update();
        },
        methods: {
            update: function () {
                axios.get('/api/content').then((response) => {
                    this.content = response.data;
                    console.log(response.data);
                });
            }
        }
    }
</script>

<style scoped>
    body {
        background-color: #1d1d1d !important;
        font-family: "Asap", sans-serif;
        color: #989898;
        margin: 10px;
        font-size: 16px;
    }

    #demo {
        height: 100%;
        position: relative;
        overflow: hidden;
    }


    .green {
        background-color: #6fb936;
    }

    .thumb {
        margin-bottom: 30px;
    }

    .page-top {
        margin-top: 85px;
    }


    img.zoom {
        width: 100%;
        height: 300px;
        border-radius: 5px;
        object-fit: cover;
        -webkit-transition: all .3s ease-in-out;
        -moz-transition: all .3s ease-in-out;
        -o-transition: all .3s ease-in-out;
        -ms-transition: all .3s ease-in-out;
    }


    .transition {
        -webkit-transform: scale(1.2);
        -moz-transform: scale(1.2);
        -o-transform: scale(1.2);
        transform: scale(1.2);
    }

    .modal-header {

        border-bottom: none;
    }

    .modal-title {
        color: #000;
    }

    .modal-footer {
        display: none;
    }

</style>
