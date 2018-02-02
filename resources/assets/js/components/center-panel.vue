<template>
    <div class="flex items-center mb-8">
        <div class="w-full mx-auto sm:w-3/4 lg:w-1/2 sm:rounded sm:shadow">
            <div v-if="hasHeader" :class="headerClasses">
                <slot name="header" class="p-3"></slot>
            </div>
            <div :class="contentClasses">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['header-accent'],
        computed: {
            headerClasses() {
                return 'flex items-center font-medium text-lg p-3 sm:rounded-t ' +
                    'bg-' + (this.headerAccent ? this.headerAccent : 'brand') + '-light';
            },
            contentClasses() {
                return 'bg-white sm:rounded-b ' + (this.hasHeader ? '' : 'sm:rounded-t');
            },
            hasHeader() {
                return !! this.$slots['header'];
            }
        }
    }
</script>