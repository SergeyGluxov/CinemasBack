<template>
    <div class="container-fluid">
        <!-- Page Content -->
        <div v-if="page>0">
            <paginate
                :page-count="page"
                :page-range="3"
                :margin-pages="2"
                :click-handler="clickCallback"
                :prev-text="'Prev'"
                :next-text="'Next'"
                :container-class="'pagination'"
                :page-class="'page-item'">
            </paginate>
        </div>
        <a v-for="col in content.contents" v-bind:href="'/admin/contents/'+col.id">
            <img v-bind:src="col.poster" class="col-lg-2 col-md-2 col-xs-2 thumb">
        </a>
    </div>

</template>

<script>
    import Paginate from "vuejs-paginate-next";


    export default {
        name: "AllContentTableComponent",
        components: {Paginate},
        props: ['id'],
        data: function () {
            return {
                content: [],
                page: 0,

            }
        },

        mounted() {
            this.loadFeed();
        },
        methods: {
            /*update: function () {
                axios.get('/api/feed/' + this.id).then((response) => {
                    console.log(response.data)
                    this.content = response.data.results.contents;
                });
            },*/
            loadFeed: function (page) {
                axios.get('/api/feed/' + this.id + '?page=' + page).then((response) => {
                    this.content = response.data.results;
                    this.page = response.data.results.pagination.total_pages
                });
            },
            clickCallback: function (pageNum) {
                console.log(pageNum)
                this.loadFeed(pageNum)
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
