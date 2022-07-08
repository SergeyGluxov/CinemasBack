<template>
    <div class="modal fade" id="reject" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <slot name="name" class="modal-title"></slot>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <slot></slot>
                    </div>
                </div>
                <div class="modal-footer">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "EditModelComponent",
        title: ''
    }
</script>

<style scoped>

</style>
