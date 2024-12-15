<template>

    <div class="modal fade show d-block" aria-labelledby="modalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <slot></slot>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>

</template>

<script>
export default {
    name: "Modal",
}
</script>
