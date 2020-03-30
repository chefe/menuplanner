<template>
    <div class="container mx-auto">
        <page-title
            :title="$t('shoppinglist.index.shoppinglist')"
            :links="pageTitleLinks">
        </page-title>

        <div class="bg-white m-2 p-2 rounded border-b-2">
            <shopping-list-table :shoppingList="shoppingList" />
       </div>
    </div>
</template>

<script>
    import ShoppingListTable from './utilities/shopping-list-table.vue'

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
        components: {
            ShoppingListTable
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
        }
    }
</script>
