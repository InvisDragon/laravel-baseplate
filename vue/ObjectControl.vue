<template>

    <div class="card">
        <div class="card-body">
            <div class="form-item mb-2" v-for="(field, fieldKey) in field.fields" :key="fieldKey">
                <label :for="fieldKey" v-text="field.name" />

                <component :is="fieldComponents[ field.inputType ]" v-if="fieldComponents[ field.inputType ]"
                           :field="field"
                           :value="currentValue[fieldKey]"
                           @update="currentValue[fieldKey] = $event; pushObjectData()"
                />

                <input v-else
                       v-model="currentValue[ fieldKey ]"
                       :type="field.inputType"
                       @change="pushObjectData"
                       :disabled="field.readOnly || disabled"
                       :id="fieldKey"
                       class="form-control" />
            </div>
        </div>
    </div>

</template>

<script>

export default {
    name: "ObjectControl",
    props: [ 'field', 'value', 'disabled' ],
    emits: ['update'],
    data() {
        // TODO: accept defaults
        return {
            currentValue: this.value || {},
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
        pushObjectData() {
            this.$emit('update', this.currentValue);
        },
    }
}
</script>
