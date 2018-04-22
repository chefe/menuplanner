<template>
    <svgicon :icon="name" :class="classes"></svgicon>
</template>

<script>
export default {
    props: {
        name: String,
        size: {
            type: String,
            default: '4'
        }
    },
    computed: {
        classes() {
            return `h-${this.size} w-${this.size} fill-current`;
        }
    }, 
    mounted() {
        require('../../icons')
    }
}
</script>

