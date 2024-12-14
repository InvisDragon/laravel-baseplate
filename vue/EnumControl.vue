<template>

    <ul class="nav nav-pills">
        <li class="nav-item" v-for="(item, k) in field.enum" :key="k">
            <a class="nav-link" :class="{ 'active': currentValue === item.value }"
               href="#" @click.prevent="currentValue = item.value" v-text="item.key"></a>
        </li>
    </ul>

</template>
<script>
export default {
    name: 'EnumControl',
    props: ['value', 'field'],
    data() {
        return {
            currentValue: this.value
        }
    },
    watch: {
        currentValue() {
            this.$emit('update', this.currentValue);
        }
    }
}
</script>
