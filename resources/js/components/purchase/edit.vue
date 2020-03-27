<template>
    <div class="container mx-auto">
        <page-title
            :title="$t('purchase.edit.editPurchase')"
            :links="pageTitleLinks">
        </page-title>

        <div class="flex flex-wrap flex-col-reverse md:flex-row">
            <div class="w-full md:w-2/3 p-2">
                <div class="bg-white rounded border-b-2 p-2 mb-4">
                    <h2 class="text-2xl mb-2 pb-2 text-gray-800 border-b">{{ $t('purchase.edit.notes') }}</h2>
                    <editor v-model="purchase.notes" :placeholder="$t('purchase.edit.enterNotes')"></editor>
                </div>
            </div>
            <div class="w-full md:w-1/3 p-2">
                <div class="bg-white rounded border-b-2 p-2">
                    <h2 class="text-2xl mb-2 pb-2 text-gray-800 border-b">{{ $t('meal.edit.settings') }}</h2>
                    <form-item caption="general.date">
                        <input class="form-control"
                               type="date"
                               name="date"
                               v-model.lazy="purchase.date"
                               :min="purchase.menuplan.start"
                               :max="purchase.menuplan.end"
                               required />
                    </form-item>
                    <form-item caption="general.time">
                        <input class="form-control" type="time" name="start" v-model.lazy="purchase.time" required />
                    </form-item>
                </div>
                <div class="bg-white rounded border-b-2 p-2 mt-2">
                    <h2 class="text-2xl mb-2 pb-2 text-gray-800 border-b">{{ $t('general.actions') }}</h2>
                    <delete-button :delete-callback="deletePurchase"></delete-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                endpoint: '',
                purchase: {
                    menuplan: {
                        id: 0,
                        start: 0,
                        stop: 0,
                    }
                },
                pageTitleLinks: []
            }
        },
        mounted() {
            this.endpoint = '/api/purchase/' + this.$route.params.id;
            this.fetchPurchase();
        },
        watch: {
            purchase: {
                handler: function (val, oldVal) { axios.put(this.endpoint, this.purchase); },
                deep: true
            }
        },
        methods: {
            fetchPurchase() {
                axios.get(this.endpoint).then(response => {
                    this.purchase = response.data;
                    this.pageTitleLinks.push({
                        to: '/menuplan/' + this.purchase.menuplan.id,
                        caption: this.$t('menuplan.share.viewMenuplan'),
                        icon: 'tile'
                    });
                });
            },
            deletePurchase() {
                axios.delete(this.endpoint).then(response => {
                    router.push('/menuplan/' + this.purchase.menuplan.id);
                });
            }
        }
    }
</script>
