<template>
    <p class="text-xl border-b text-gray-800 px-2 py-3 mb-2" v-text="date.format('dddd, Do MMM')"></p>
</template>

<script>
    export default {
        props: ['date']
    }
</script>
