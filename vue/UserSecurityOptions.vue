<template>

    <iframe
        class="security-options"
        ref="iframe"
        allow="publickey-credentials-get; publickey-credentials-create"
        :style="style"
        src="/b/security-options">
    </iframe>

</template>

<script>
export default {
    name: "UserSecurityOptions",
    data() {
        return {
            style: {
                width: '100%',
                border: 0,
                height: '400px'
            }
        }
    },
    methods: {
        onMessage(e) {
            if(e.data.hasOwnProperty('frameHeight')) {
                this.style.height = e.data.frameHeight + 'px';
            }
        }
    },
    mounted() {
        window.addEventListener('message', this.onMessage);
    },
    unmounted() {
        window.removeEventListener('message', this.onMessage);
    }
}
</script>
