<template>

    <div class="repeater">
        <table class="table table-striped">
            <tbody>
                <tr v-for="(row, k) in currentValue" :key="k">
                    <td style="width:1%">{{ k+1 }}</td>
                    <td>
                        <div class="form-item mb-2" v-for="(field, fieldKey) in field.items.fields">
                            <label :for="fieldKey" v-text="field.name" />

                            <component :is="fieldComponents[ field.inputType ]" v-if="fieldComponents[ field.inputType ]"
                                       :field="field"
                                       :value="row[fieldKey]"
                                       @update="row[fieldKey] = $event; pushData()"
                            />

                            <input v-else
                                   v-model="row[ fieldKey ]"
                                   :type="field.inputType"
                                   @change="pushData"
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
                        <button @click="currentValue.push({})" type="button" class="btn btn-link">
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
    name: "RepeaterControl",
    props: [ 'field', 'value', 'disabled' ],
    components: {
        PlusIcon, MinusIcon
    },
    emits: ['update'],
    data() {
        // TODO: accept defaults
        return {
            currentValue: this.value || [ {} ],
            fieldComponents: window.fieldComponents,
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
        }
    }
}
</script>
