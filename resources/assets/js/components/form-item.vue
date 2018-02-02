<template>
    <div class="flex items-center p-2 flex-col sm:flex-row">
        <label class="w-full sm:w-1/3 mb-0 pr-4 sm:text-right" v-text="caption + ':'"></label>
        <div class="w-full sm:w-2/3">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['caption']
    }
</script>