<template>
    <div class="flex items-center mb-8">
        <div class="w-full mx-auto sm:w-3/4 lg:w-1/2 sm:rounded sm:shadow">
            <div v-if="hasHeader" class="flex items-center font-medium text-lg bg-brand-light p-3 sm:rounded-t">
                <slot name="header" class="p-3"></slot>
            </div>
            <div :class="'bg-white sm:rounded-b ' +(hasHeader ? '' : 'sm:rounded-t')">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            contentClasses() {

            },
            hasHeader() {
                return !! this.$slots['header'];
            }
        }
    }
</script>