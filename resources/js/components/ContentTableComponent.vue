<template>
    <div class="container-fluid">
        <!-- Page Content -->

        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-3">
                <img id="poster" v-bind:src="content.poster" >
                <button type="button" class="btn btn-warning col-lg-12 col-md-12 col-xs-12"  @click="showModal = true"><b>ИЗМЕНИТЬ</b></button>
                <button type="button" class="btn btn-danger col-lg-12 col-md-12 col-xs-12"><b>УДАЛИТЬ</b></button>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-9">
                <h1><b>{{content.title}}</b></h1>
                <h4><b>Кинопоиск ID:</b> {{content.kinopoisk_id}}</h4>
                <h4><b>Год:</b> {{content.year}}</h4>
                <h4><b>Возрастное:</b> {{content.restrict}}+</h4>
                <h4><b>Рейтинг:</b> {{content.rating}}</h4>
                <h4><b>Жанры:</b> <a v-for="col in content.genres" href="#"
                                     class="badge badge-primary">{{col.title}}</a></h4>
                <h4 v-if="content.type_content"><b>Тип:</b> {{content.type_content.title}}</h4>
                <h4><b>Продолжительность: </b> {{secToTime(content.duration)}}</h4>
                <h4><b>Описание: </b></h4>
                <h4>{{content.description}}</h4>
            </div>
        </div>
    </div>

</template>

<script>
    import moment from "moment";

    export default {
        name: "AllContentTableComponent",
        props: ['id'],
        data: function () {
            return {
                content: {}
            }
        },

        mounted() {
            axios.get('/api/content/' + this.id).then((response) => {
                this.content = response.data;
                console.log(response.data);
                console.log(response.data.type_content.title);
            });
        },
        methods: {

            secToTime: function (sec) {
                return moment.utc(sec * 1000).format('HH:mm:ss');
            }
        }
    }
</script>

<style scoped>
    .badge {
        margin-right: 3px
    }
    .btn{
        margin-top: 5px;
    }

    #poster {
        width: 100%;
    }
</style>
