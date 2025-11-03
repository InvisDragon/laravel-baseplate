<template>

    <div>

        <p v-if="field.hasConnected">
            Connected. You can choose to re-connect with a different account.
        </p>

        <a class="btn btn-primary"
            :href="field.connectUrl"
            target="_blank">
            Connect
        </a>

    </div>

</template>
<script>
export default {
    name: 'IntegrationControl',
    props: { field: { type: Object }, value: {}, labelKey: { type: String, default: 'name' } },
};
</script>
