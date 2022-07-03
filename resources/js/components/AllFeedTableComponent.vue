<template>
    <div class="container-fluid">
        <h1><b>{{page.title}}</b></h1>

        <div v-for="feed in page.feeds">
            <h2>{{feed.title}}</h2>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button"
                        id="dropdownFeedMenuButton" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    Управление подборкой
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownFeedMenuButton">
                    <a href="#reject" role="button" class="dropdown-item" data-toggle="modal"
                       v-on:click="editFeed = feed">Добавить контент</a>
                    <a class="dropdown-item" href="#">Удалить подборку</a>
                </div>
            </div>

            <vue-horizontal ref="horizontal" class="horizontal" style="margin-top: 10px" :button="false"
                            @scroll-debounce="onScrollDebounce">
                <div class="item" v-for="item in feed.contents" :key="item.id">
                    <div class="image" :style="{background: `url(${item.poster})`}">
                        <div class="overlay">
                            <div class="text">{{ item.title }}</div>
                        </div>
                    </div>
                    <div class="dropdown">

                        <button class="btn btn-secondary dropdown-toggle col-lg-12 col-md-12 col-xs-12" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            Управление
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item" v-on:click="deleteFromContentFeed(feed.id,item.id)">Удалить из
                                подборки
                            </button>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
            </vue-horizontal>
            <hr/>
        </div>

        <div class="modal fade" id="reject" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Редактирование подборки</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Введите ID контента: </label>
                            <input v-model="contentId" class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success col-lg-12 col-md-12 col-xs-12" data-dismiss="modal"
                                @click="storeContentToFeed(editFeed.id,contentId)">Добавить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import VueHorizontal from "vue-horizontal";

    export default {
        components: {VueHorizontal},
        name: "AllFeedTableComponent",
        data: function () {
            return {
                page: {},
                showModalPlaylist: false,
                editFeed: {},
                contentId: ''
            }
        },

        mounted() {
            this.update();
        },
        methods: {
            update: function () {
                axios.get('/api/page/1').then((response) => {
                    this.page = response.data;
                    console.log(response.data);
                });
            },

            storeContentToFeed: function (feed_id, content_id) {
                const formData = new FormData();
                formData.append('feed_id', feed_id);
                formData.append('content_id', content_id);
                axios.post('/api/feedContent', formData).then((response) => {
                    this.update();
                });
            },
            deleteFromContentFeed: function (feed_id, content_id) {
                const formData = new FormData();
                formData.append('feed_id', feed_id);
                formData.append('content_id', content_id);
                axios.post('/api/deleteContentFromFeed', formData).then((response) => {
                    this.update();
                });
            },
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

