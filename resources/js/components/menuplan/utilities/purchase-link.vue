<template>
    <router-link
        :to="'/purchase/' + purchase.id + '/edit'"
        class="block px-2 py-2 hover:bg-gray-200 rounded flex align-center">
        <span class="block h-1 my-1 bg-gray-400 w-full rounded"></span>
        <div class="mx-2 flex">
            <icon name="store" class="text-gray-400"></icon>
            <small class="mx-1 text-gray-700" v-text="purchase.time"></small>
        </div>
        <span class="block h-1 my-1 bg-gray-400 w-full rounded"></span>
    </router-link>
</template>

<script>
    export default {
        props: ['purchase'],
    }
</script>
