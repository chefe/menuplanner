<template>
    <center-panel>
        <template slot="header">Edit Menuplan</template>
        <menuplan-form 
            :menuplan="menuplan" 
            @submit="save"
            @cancel="cancel">
        </menuplan-form>
    </center-panel>
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
                endpoint: ''
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
            }
        }
    }
</script>