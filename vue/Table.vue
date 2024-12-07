<template>

    <div class="card">
        <div class="card-body">
            <slot name="header"></slot>
            <Loader :url="dataUrl" ref="loader" v-slot="{ data }">
                <table class="table table-striped table-borderless card-table">
                    <thead>
                        <tr>
                            <th v-for="column in columns" v-text="column.title" :class="column.class" :width="column.width"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in data.data">
                            <td v-for="column in columns" :data-title="column.title" :class="column.class">
                                <slot :name=" 'col-' + column.data " :value="item[column.data]" :item="item">
                                    {{ item[column.data] }}
                                </slot>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <Pagination :data="data" @navigate="navigate" />

            </Loader>
        </div>
    </div>

</template>

<script>
import Loader from "./Loader.vue";
import Pagination from "./Pagination.vue";
export default {
    name: "Table",
    components: {Pagination, Loader},
    props: [ 'url', 'columns', ],

    data() {
        return {
            dataUrl: this.url
        }
    },

    methods: {
        load() {
            this.$refs.loader.load();
        },
        navigate(url) {
            this.dataUrl = url;
        }
    }
}
</script>
