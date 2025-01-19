<template>

    <div class="modal fade show d-block" aria-labelledby="modalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <ExclamationTriangleIcon v-if="type === 'warning'" class="large-icon text-warning" />
                    <ExclamationCircleIcon v-if="type === 'error'" class="large-icon text-danger" />
                    <CheckCircleIcon v-if="type === 'success'" class="large-icon text-success" />
                    <QuestionMarkCircleIcon v-if="type === 'question'" class="large-icon text-info" />
                    <InformationCircleIcon v-if="type === 'info'" class="large-icon text-info" />
                    <h1 class="text-center" v-text="title || message" />
                    <p v-text="message" v-if="title" />
                </div>
                <div class="modal-body"><slot></slot></div>
                <div class="modal-footer text-center py-4" v-if="loading">
                    <Spinner />
                </div>
                <div class="modal-footer" v-if="!loading">
                    <button type="button" class="btn btn-primary" v-if="primaryButton" @click="$emit('action')" v-text="primaryButton"></button>
                    <button type="button" class="btn btn-secondary" @click="$emit('close')">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-backdrop fade show"></div>

</template>

<script>
import { ExclamationTriangleIcon, ExclamationCircleIcon, CheckCircleIcon, QuestionMarkCircleIcon, InformationCircleIcon } from '~root/@heroicons/vue/24/outline';
import Spinner from "./Spinner.vue";

export default {
    name: "MessageBox",
    props: {
        type: {
            type: String,
            default: 'error'
        },
        message: {
            type: String
        },
        title: {
            type: String
        },
        primaryButton: {
            type: String
        },
        loading: {
            type: Boolean,
            default: false
        }
    },
    emits: ['close', 'action'],
    components: {
        Spinner,
        ExclamationTriangleIcon,
        ExclamationCircleIcon,
        CheckCircleIcon,
        QuestionMarkCircleIcon,
        InformationCircleIcon,
    }
}
</script>

<style>
.large-icon {
    max-width: 6rem;
    height: auto;
}
</style>
