<template>
    <div class="sm:rounded sm:shadow">
        <div class="flex items-center font-medium text-lg bg-brand-light p-3 sm:rounded-t">
            <slot name="header"></slot>
        </div>
        <div class="bg-white sm:rounded-b">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>