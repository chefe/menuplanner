<template>
    <div v-if="show">
        <div class="fixed pin bg-black opacity-50"></div>
        <div class="fixed pin">
            <div class="min-h-screen flex items-center justify-center">
                <center-panel>
                    <template slot="header">{{ $t('meal.edit.addItem') }}</template>
                    <form class="" @submit.prevent="submit">
                        <form-item caption="general.title">
                            <input class="form-control" v-model="caption" type="text" required>
                        </form-item> 
                        <form-item caption="item.index.unit">
                            <input class="form-control" v-model="unit" type="text" required>
                        </form-item>
                        <div class="w-full sm:w-2/3 ml-auto flex sm:py-2 sm:pr-2 flex-col sm:flex-row">
                            <a class="btn-secondary flex-1 sm:mr-2" @click="cancel">{{ $t('general.cancel') }}</a>
                            <button type="submit" class="btn-primary flex-1">{{ $t('general.save') }}</button>
                        </div>
                    </form>
                </center-panel>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['caption', 'show', 'menuplanId'],
        data() {
            return {
                unit: ''
            }
        },
        methods: {
            cancel() {
                this.$emit('hide');
            },
            submit() {
                let endpoint = '/api/menuplan/' + this.menuplanId + '/items';
                let newitem = { title: this.caption, unit: this.unit };
                axios.post(endpoint, newitem).then(response => {
                    let item = response.data;
                    this.$emit('addItem', item);
                    this.$emit('hide');
                });
            }
        }
    }
</script>
