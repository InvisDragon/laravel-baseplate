<template>
    <Loader :url="describeURL" @load="newData">
        <form @submit.prevent="submitForm">

            <div class="alert alert-danger" v-if="error === 1">
                Request could not be completed. Please try again.
            </div>

            <div class="alert alert-warning" v-if="error === 2">
                Check over the elements highlighted
            </div>

            <div class="form-item mb-3" v-for="(field, key) in describeData" :key="key">
                <label :for="key" v-text="field.name"
                       v-if="field.inputType !== 'bool' && field.inputType !== 'hidden'" />

                <textarea
                    v-if="field.inputType === 'textarea'"
                    v-model="formData[ key ]"
                    :disabled="field.readOnly || state !== 0"
                    :id="key"
                    class="form-control">
                </textarea>

                <div class="form-check" v-else-if="field.inputType === 'bool'">
                    <input :id="key" type="checkbox" v-model="formData[key]" :disabled="field.readOnly || state !== 0" class="form-check-input" />
                    <label :for="key" v-text="field.name" class="form-check-label"></label>
                </div>

                <component :is="fieldComponents[ field.inputType ]" v-else-if="fieldComponents[ field.inputType ]"
                           :field="field"
                           :value="formData[key]"
                           :disabled="field.readOnly || state !== 0"
                           @update="formData[key] = $event"
                           />

                <input v-else
                    v-model="formData[ key ]"
                    :type="field.inputType"
                    :disabled="field.readOnly || state !== 0"
                    :id="key"
                    class="form-control" />

                <p v-if="errorDetail[ key ]" class="text-danger validatione-error">
                    <span v-for="error in errorDetail[key]" v-text="error" />
                </p>

            </div>

            <button type="submit"
                v-if="state === 0"
                v-text="submitButtonText"
                class="btn btn-primary">
            </button>
            <div class="throbber py-3" v-if="state !== 0">
                <Spinner />
            </div>
        </form>
    </Loader>
</template>
<script>
import Loader from './Loader.vue';
import Spinner from "./Spinner.vue";

export default {
    props: {
        describeURL: { type: String },
        formURL: { type: String },
        submitButtonText: { type: String, default: 'Submit' },
        data: { type: Object, default: Object.create },
        method: { type: String, default: 'POST' },
        formFields: { type: Object, default: null },
    },
    emits: ['submit', 'success'],
    data() {
        return {
            state: 0,
            error: 0,
            errorDetail: {},
            formData: this.data,
            fieldComponents: window.fieldComponents || {},
            describeData: this.formFields
        }
    },
    components: {
        Spinner,
        Loader,
    },
    methods: {
        submitForm() {
            if(!this.formURL) {
                return this.$emit('submit');
            }
            this.state = 1;
            axios({
                method: this.method,
                url: this.formURL,
                data: this.formData
            }).then((res) => {
                this.$emit('success', res.data);
                // Reset state in case we want to re-use this form
                this.formData = {};
                this.state = 0;
                this.errorDetail = {};
                this.error = 0;
            }).catch((error) => {
                this.error = 1;
                this.state = 0;
                if (error.response) {
                    if(error.response.status == 422) {
                        this.error = 2;
                        this.errorDetail = error.response.data.errors;
                    }
                }
            });
        },
        newData(data){
            this.describeData = data;
            for(let key in data) {
                let col = data[key];
                if(typeof col.default !== 'undefined' && typeof this.formData[key] === 'undefined') {
                    this.formData[key] = col.default;
                }
            }
        }
    }
}
</script>
