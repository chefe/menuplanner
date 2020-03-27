<template>
    <router-link
        :to="'/meal/' + meal.id + '/edit'"
        class="block px-2 py-4 hover:bg-gray-200 rounded">
        <p class="text-gray-700" v-text="meal.title" />
        <small class="text-gray-500">
            {{ time }} &middot; {{ people + ' ' + $t('general.people') }}
        </small>
    </router-link>
</template>

<script>
    export default {
        props: ['meal', 'menuplan'],
        computed: {
            time() {
                return this.meal.start + ' - ' + this.meal.end;
            },
            people() {
                return this.meal.people ? this.meal.people : this.menuplan.people;
            }
        },
    }
</script>
