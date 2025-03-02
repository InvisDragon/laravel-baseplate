<template>
    <div class="tab-view">
        <ul class="nav nav-tabs">
            <li v-for="(item, key) in tabs" class="nav-item" :key="key">
                <a class="nav-link" :class="{ 'active' : currentTab === key }"
                   @click.prevent="currentTab = key"
                   :aria-current="currentTab === key ? 'page' : ''" href="#">
                    {{ item }}
                </a>
            </li>
        </ul>
        <slot :name="currentTab"></slot>
    </div>
</template>

<script>
export default {
    name: "Tabs",
    props: [ 'tabs', ],
    data() {
        let cT = '';
        if(this.$route.query['tab']) {
            if(this.tabs[ this.$route.query['tab'] ]) {
                cT = this.$route.query['tab'];
            }
        }
        if(cT === '') {
            if (Object.keys(this.tabs).length) {
                cT = Object.keys(this.tabs)[0];
            }
        }
        return {
            currentTab: cT
        };
    },
}
</script>
