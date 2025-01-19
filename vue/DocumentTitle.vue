<template>

</template>

<script>
export default {
    name: "DocumentTitle",
    props: [ 'title' ],
    computed: {
        fullTitle() {
            let t = this.title;
            if(window['thisAppName']) {
                t += " - " + window.thisAppName;
            }
            return t;
        }
    },
    mounted() {
        document.title = this.fullTitle;
    }
}
</script>
