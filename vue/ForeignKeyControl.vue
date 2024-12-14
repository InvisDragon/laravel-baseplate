<template>

    <div>
        <Multiselect v-model="currentValue"
                     :options="options"
                     :searchable="true"
                     :loading="isLoading"
                     :label="labelKey"
                     :trackBy="labelKey"
                     @search-change="asyncFind">
        </Multiselect>
    </div>

</template>
<script>
import Multiselect from '~root/vue-multiselect'

export default {
    name: 'ForeignKeyControl',
    props: { field: { type: Object }, value: {}, labelKey: { type: String, default: 'name' } },
    components: {
        Multiselect
    },
    data() {
        return {
            currentValue: this.value ? { title: 'Preselected Value', id: this.value } : null,
            options: [ ],
            isLoading: false
        }
    },
    methods: {
        asyncFind(query) {
            this.isLoading = true;
            axios.get( this.field.apiMethod + "?search=" + encodeURIComponent(query) ).then((res) => {
                this.isLoading = false;
                this.options = res.data.data;
            });
        }
    },
    watch: {
        currentValue() {
            this.$emit('update', this.currentValue.id);
        }
    }
}

</script>
