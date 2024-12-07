<template>

    <nav :aria-label=" 'Table Navigation' ">
        <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ 'disabled': !data.prev_page_url }">
                <a class="page-link" href="#" @click.prevent="openPage(data.prev_page_url)">
                    Previous
                </a>
            </li>

            <li class="page-item" :class="{ 'disabled': !data.next_page_url }">
                <a class="page-link" href="#" @click.prevent="openPage(data.next_page_url)">
                    Next
                </a>
            </li>
        </ul>
    </nav>

</template>

<script>
export default {
    name: "Pagination",
    props: [ 'data' ],
    emits: [ 'navigate' ],
    methods: {
        openPage(url) {
            if(url) {
                this.$emit('navigate', url);
            }
        }
    }
}
</script>
