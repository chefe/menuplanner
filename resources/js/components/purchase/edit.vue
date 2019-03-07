<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-grey-darkest text-xl bg-white">
            <h1 class="flex-1">{{ $t('purchase.edit.editPurchase') }}</h1>
            <router-link :to="'/menuplan/' + purchase.menuplan.id" class="text-grey-darkest ml-4">
                <icon name="tile" size="8"></icon>
            </router-link>
        </div>
        <div class="flex flex-wrap flex-col-reverse md:flex-row">
            <div class="w-full md:w-2/3 p-2">
                <div class="bg-white rounded border-b-2 p-2 mb-4">
                    <h2 class="mb-2 pb-2 text-grey-darkest border-b">{{ $t('purchase.edit.notes') }}</h2>
                    <editor v-model="purchase.notes" :placeholder="$t('purchase.edit.enterNotes')"></editor>
                </div>
            </div>
            <div class="w-full md:w-1/3 p-2">
                <div class="bg-white rounded border-b-2 p-2">
                    <h2 class="mb-2 pb-2 text-grey-darkest border-b">{{ $t('meal.edit.settings') }}</h2>
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
                }
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
                });
            }
        }
    }
</script>