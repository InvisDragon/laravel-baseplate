<template>

    <div class="do-loader">

        <div class="py-3 text-center" v-if="status === 0">
            <Spinner />
        </div>

        <div v-if="status === 1">
            ERROR!
        </div>

        <slot :data="remoteData" v-if="status === 2"></slot>

    </div>

</template>
<script>
import Spinner from "./Spinner.vue";
const STATUS_LOADING = 0;
const STATUS_ERROR = 1;
const STATUS_SUCCESS = 2;

export default {
    components: {Spinner},
    props: ['url'],
    emits: ['load'],
    data() {
        return {
            status: 0,
            remoteData: []
        }
    },
    mounted() {
        this.load();
    },
    methods: {
        load() {
            this.status = STATUS_LOADING;
            if(!this.url) {
                this.status = STATUS_SUCCESS;
                return;
            }
            axios.get(this.url).then((res) => {
                this.remoteData = res.data;
                this.status = STATUS_SUCCESS;
                this.$emit('load', res.data);
            }).catch(() => {
                this.status = STATUS_ERROR;
            });
        }
    },
    watch: {
        url() {
            this.load();
        }
    }
}
</script>
