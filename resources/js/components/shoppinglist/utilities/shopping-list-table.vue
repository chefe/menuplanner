<template>
    <table class="w-full">
        <tbody>
            <tr v-for="item in shoppingList" :key="item.id">
                <td class="p-1 text-right" v-text="item.quantity"></td>
                <td class="p-1" v-text="item.unit"></td>
                <td class="p-1" v-text="item.title"></td>
                <td>
                    <small v-text="quantityPerMealString(item)"></small>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['shoppingList'],
        created() {
            moment.locale(this.$i18n.locale);
        },
        methods: {
            quantityPerMealString(item) {
                return item.meals.map(m => {
                    return m.title + ', '
                        + moment(m.date).format('ddd Do')
                        + ' ['  + m.quantity + item.unit + ']';
                }).join(' · ');
            }
        }
    }
</script>
