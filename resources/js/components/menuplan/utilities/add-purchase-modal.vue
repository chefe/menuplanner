<template>
    <modal ref="modal">
        <center-panel>
            <template slot="header">{{ $t('purchase.add.addPurchase') }}</template>
            <form @submit.prevent="submit">
                <form-item caption="general.time">
                    <input class="form-control" v-model="time" type="time" required>
                </form-item>
                <div class="w-full sm:w-2/3 ml-auto flex sm:py-2 sm:pr-2 flex-col sm:flex-row">
                    <a class="btn-secondary flex-1 sm:mr-2" @click="hide">{{ $t('general.cancel') }}</a>
                    <button type="submit" class="btn-primary flex-1">{{ $t('general.save') }}</button>
                </div>
            </form>
        </center-panel>
    </modal>
</template>

<script>
    export default {
        props: ['date', 'menuplanId'],
        data() {
            return {
                time: ''
            }
        },
        methods: {
            show() {
                this.$refs.modal.show();
            },
            hide() {
                this.$refs.modal.hide();
            },
            submit() {
                let endpoint = '/api/menuplan/' + this.menuplanId + '/purchases';
                let newPurchase = { date: this.date, time: this.time };
                axios.post(endpoint, newPurchase).then(response => {
                    let purchase = response.data;
                    this.$emit('purchaseAdded', purchase);
                    this.hide();
                });
            }
        }
    }
</script>
