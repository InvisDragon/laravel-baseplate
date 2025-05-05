<template>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <ul class="table-filters list-unstyled mb-0 d-flex">
                <li v-for="filter in filters">
                    <button type="button" class="btn btn-link" @click="openFilter(filter)">
                        {{ filter.title }}
                    </button>
                </li>
            </ul>
            <slot name="header"></slot>
        </div>
        <div class="card-body pt-0 pb-0">
            <Loader :url="dataUrl" ref="loader" v-slot="{ data }" @load="onload">
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
    props: {
        url: { type: String },
        columns: { type: Array },
        filters: { type: Array, default: () => {  return [];  } },
    },

    emits: [ 'load' ],

    data() {
        return {
            dataUrl: this.url
        }
    },

    methods: {
        load() {
            this.$refs.loader.load();
        },
        onload($e) {
            this.$emit('load', $e);
        },
        navigate(url) {
            this.dataUrl = url;
        },
        openFilter(filter) {
            let orig = this.dataUrl.split('?');
            let params = new URLSearchParams(orig[1]);
            for(let key in filter.filters) {
                params.set(key, filter.filters[key]);
            }
            this.dataUrl = orig[0] + "?" + params.toString();
            console.log(this.dataUrl);
        }
    }
}
</script>
