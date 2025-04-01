<template>

    <div class="string-array">
        <table class="table table-striped">
            <tbody>
                <tr v-for="(item, k) in currentValue" :key="k">
                    <td style="width:1%">{{ k+1 }}</td>
                    <td>
                        <div class="form-item mb-1">
                            <input
                                :value="item"
                                :type="field.inputType"
                                @change="updateValue(k, $event)"
                                :disabled="field.readOnly || disabled"
                                :id="fieldKey"
                                class="form-control" />
                        </div>
                    </td>
                    <td style="width:1%">
                        <button @click="removeRow(k)" type="button" class="btn btn-danger">
                            <MinusIcon style="width:24px" />
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-center">
                        <button @click="currentValue.push('')" type="button" class="btn btn-link">
                            <PlusIcon style="width:24px" />
                            Add Row
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</template>

<script>
import { PlusIcon, MinusIcon } from '~root/@heroicons/vue/24/outline';

export default {
    name: "StringArrayControl",
    props: [ 'field', 'value', 'disabled' ],
    components: {
        PlusIcon, MinusIcon
    },
    emits: ['update'],
    data() {
        // TODO: accept defaults
        return {
            currentValue: this.value || [ "" ],
        }
    },
    watch: {
        currentValue: {
            handler() {
                this.$emit('update', this.currentValue);
            },
            deep: true
        }
    },
    methods: {
        pushData() {
            this.$emit('update', this.currentValue);
        },
        removeRow(k) {
            if(confirm('Are you sure?')) {
                this.currentValue.splice(k, 1);
            }
        },
        updateValue(k, $event) {
            this.currentValue[k] = $event.target.value;
        }
    }
}
</script>
