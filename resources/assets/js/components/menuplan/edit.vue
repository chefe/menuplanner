<template>
    <div>
        <center-panel>
            <template slot="header">Edit Menuplan</template>
            <menuplan-form 
                :menuplan="menuplan" 
                @submit="save"
                @cancel="cancel">
            </menuplan-form>
        </center-panel>
        <center-panel header-accent="red">
            <template slot="header">Delete Menuplan</template>
            <div v-if="confirmMode" class="px-2 py-4 text-center">
                <span class="text-grey-dark mr-2">Are you sure?</span>
                <a class="btn-secondary mr-2" @click="cancelDelete">No</a>
                <a class="btn-danger" @click="deleteMenuplan">Yes</a>
            </div>
            <div v-else class="px-2 py-4">
                <a class="btn-danger mr-2" @click="deleteMenuplan">Delete Menuplan</a>
                <span class="text-grey-dark">This action can to be reverted!</span>
            </div>
        </center-panel>
    </div>
</template>

<script>
    import { bus } from '../../eventbus.js';

    export default {
        components: {
            'menuplan-form': require('./form.vue')
        },
        data() {
            return {
                menuplan: {
                    title: '',
                    start: '',
                    end: '',
                    people: 1
                }, 
                endpoint: '',
                confirmMode: false,
            }
        },
        mounted() {
            this.endpoint = '/api/menuplan/' + this.$route.params.id;

            this.fetchMenuplan();
        },
        methods: {
            fetchMenuplan() {
                let that = this;
                bus.$emit('loading', true);
                axios.get(this.endpoint)
                    .then(function (response) {
                        bus.$emit('loading', false);
                        that.menuplan = response.data;
                    })
                    .catch(function (error) {
                        bus.$emit('error', error);
                    });
            },
            save() {
                let that = this;
                bus.$emit('loading', true);
                axios.put(this.endpoint, this.menuplan)
                    .then(function (response) {
                        bus.$emit('loading', false);
                        router.push('/');
                    })
                    .catch(function (error) {
                        bus.$emit('error', error);
                    });
            },
            cancel() {
                router.go(-1);
            }, 
            deleteMenuplan() {
                if (this.confirmMode == false) {
                    this.confirmMode = true;
                    return;
                }

                let that = this;
                bus.$emit('loading', true);
                axios.delete(this.endpoint)
                    .then(function (response) {
                        bus.$emit('loading', false);
                        router.push('/');
                    })
                    .catch(function (error) {
                        bus.$emit('error', error);
                    });
            },
            cancelDelete() {
                this.confirmMode = false;
            }
        }
    }
</script>