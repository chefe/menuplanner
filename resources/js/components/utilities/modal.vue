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
    export default {
        data() {
            return {
                isOpen: false
            }
        },
        methods: {
            show() {
                this.isOpen = true;
            },
            hide() {
                this.isOpen = false;
            }
        }
    }
</script>
