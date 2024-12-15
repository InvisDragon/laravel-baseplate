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
        let defaultValue = { id: this.value, initial: true };
        defaultValue[this.labelKey] = 'Preselected Value';

        return {
            currentValue: this.value ? defaultValue : null,
            options: [ ],
            isLoading: false
        }
    },
    mounted() {
        if(this.currentValue && this.currentValue.initial) {
            axios.get( this.field.apiMethod + '/' + this.currentValue.id ).then((res) => {
                this.currentValue = res.data;
            });
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
