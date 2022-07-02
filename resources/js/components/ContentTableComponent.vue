<template>
    <div class="container-fluid">
        <!-- Page Content -->

        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-3">
                <img id="poster" v-bind:src="content.poster">
                <button type="button" class="btn btn-warning col-lg-12 col-md-12 col-xs-12" @click="showModal = true">
                    <b>ИЗМЕНИТЬ</b></button>
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
        <br/>
        <h2>Создатели и актеры:</h2>
        <vue-horizontal ref="horizontal" class="horizontal" style="margin-top: 10px" :button="false" @scroll-debounce="onScrollDebounce">
            <div class="item" v-for="item in content.creators" :key="item.id">
                <div class="image" :style="{background: `url(${item.avatar})`}">
                    <div class="overlay">
                        <div class="text">{{ item.name }}</div>
                    </div>
                </div>
                <div class="content">
                    <h6>{{ item.name }} (Кинопоиск ID: {{item.kinopoisk_id}})</h6>
                    <p>{{ item.name }}</p>
                </div>
            </div>
        </vue-horizontal>
    </div>

</template>

<script>
    import moment from "moment";
    import VueHorizontal from "vue-horizontal";

    export default {
        components: {VueHorizontal},
        name: "AllContentTableComponent",
        props: ['id'],
        data: function () {
            return {
                content: {},
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
            },
            prev() {
                this.$refs.horizontal.prev()
            },
            next() {
                this.$refs.horizontal.next()
            },
            onScrollDebounce({hasPrev, hasNext}) {
                this.hasPrev = hasPrev
                this.hasNext = hasNext
            }
        }
    }
</script>

<style scoped>
    .badge {
        margin-right: 3px
    }

    .btn {
        margin-top: 5px;
    }

    #poster {
        width: 100%;
    }

    .image {
        background-position: center !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        padding-top: 100%;
        position: relative;
    }

    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        display: flex;
        justify-content: flex-end;
        align-items: flex-end;
        background: linear-gradient(345deg, #00000080 0, #00000060 5%, #00000040 20%, #00000000 50%);
    }

    .overlay .text {
        padding: 12px;
        line-height: 1;
        font-weight: 700;
        font-size: 14px;
        color: white;
    }

    .content {
        margin-top: 6px;
    }

    .content h6 {
        font-size: 14px;
        text-transform: capitalize;
        line-height: 1.5;
    }

    .content p {
        line-height: 1.5;
        font-size: 12px;
        margin-top: 2px;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .header {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    nav {
        display: flex;
        align-items: center;
    }

    svg {
        width: 24px;
        height: 24px;
        fill: currentColor;
        color: black;
    }

    button.inactive svg {
        color: #BBB;
    }

    button {
        padding: 4px;
    }

    button:focus {
        outline: none;
    }

</style>

<!-- Parent CSS (.container) -->
<style scoped>
    main {
        padding: 24px;
    }

    @media (min-width: 768px) {
        main {
            padding: 48px;
        }
    }
</style>

<!-- Responsive Breakpoints -->
<style scoped>
    .horizontal {
        --count: 2;
        --gap: 16px;
    }

    @media (min-width: 640px) {
        .horizontal {
            --count: 3;
            --gap: 24px;
        }
    }

    @media (min-width: 768px) {
        .horizontal {
            --count: 4;
        }
    }

    @media (min-width: 1024px) {
        .horizontal {
            --count: 5;
        }
    }

    @media (min-width: 1280px) {
        .horizontal {
            --count: 6;
        }
    }

    .item {
        width: calc((100% - ((var(--count) - 1) * var(--gap))) / var(--count));
        margin-right: var(--gap);
    }
</style>
