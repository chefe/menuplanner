<template>
    <div class="container mx-auto">
        <page-title
            :title="$t('shoppinglist.index.shoppinglist')"
            :links="pageTitleLinks">
        </page-title>

        <div class="bg-white m-2 p-2 rounded border-b-2">
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
       </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                shoppingList: [],
                pageTitleLinks: [
                    {
                        href: '/menuplan/' + this.$route.params.id + '/shopping-list/pdf',
                        caption: this.$t('general.downloadAsPdf'),
                        icon: 'download'
                    }, {
                        to: '/menuplan/' + this.$route.params.id,
                        caption: this.$t('menuplan.share.viewMenuplan'),
                        icon: 'tile'
                    }
                ]
            };
        },
        created() {
            moment.locale(this.$i18n.locale);
        },
        mounted() {
            this.endpoint = '/api/menuplan/' + this.$route.params.id + '/shopping-list';
            this.fetchShoppingList();
        },
        methods: {
            fetchShoppingList() {
                axios.get(this.endpoint).then(response => {
                    this.shoppingList = response.data;
                });
            },
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
