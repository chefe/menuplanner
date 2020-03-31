<template>
    <div v-if="isOpen">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="fixed inset-0">
            <div class="min-h-screen flex items-center justify-center">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    const KEY_CODE_ESCAPE = 27;

    export default {
        data() {
            return {
                isOpen: false
            }
        },
        mounted() {
            document.addEventListener('keyup', this.handleKeyUp);
        },
        destroyed() {
            document.removeEventListener('keyup', this.handleKeyUp);
        },
        methods: {
            show() {
                this.isOpen = true;
            },
            hide() {
                this.isOpen = false;
            },
            handleKeyUp(ev) {
                if (ev.keyCode === KEY_CODE_ESCAPE) {
                    this.hide();
                }
            }
        }
    }
</script>
