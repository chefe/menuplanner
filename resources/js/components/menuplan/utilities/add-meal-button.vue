<template>
    <a
        @click="addMeal"
        class="block px-2 py-3 cursor-pointer flex items-center hover:bg-gray-200 rounded">
        <icon class="text-gray-500" name="add" />
        <span class="text-gray-500 ml-2" v-text="$t('menuplan.show.addNewMeal')" />
    </a>
</template>

<script>
    export default {
        props: ['date'],
        methods: {
            addMeal() {
                router.push({
                    path: '/menuplan/' + this.$route.params.id + '/meal/create',
                    query: { date: this.date.format('YYYY-MM-DD') }
                });
            }
        }
    }
</script>
