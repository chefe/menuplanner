<template>
    <div>
        <center-panel>
            <template slot="header">{{ $t('menuplan.edit.editMenuplan') }}</template>
            <menuplan-form :menuplan="menuplan" @submit="save" @cancel="cancel">
            </menuplan-form>
        </center-panel>

        <center-panel :is-critical-action="true">
            <template slot="header">{{ $t('menuplan.edit.deleteMenuplan') }}</template>

            <div v-if="confirmMode" class="px-2 py-4 text-center">
                <span class="text-grey-dark mr-2">{{ $t('menuplan.edit.sure') }}</span>
                <a class="btn-secondary mr-2" @click="cancelDelete">{{ $t('general.no') }}</a>
                <a class="btn-danger" @click="deleteMenuplan">{{ $t('general.yes') }}</a>
            </div>

            <div v-else class="px-2 py-4 flex items-center">
                <a class="btn-danger mr-2" @click="confirmDelete">{{ $t('menuplan.edit.deleteMenuplan') }}</a>
                <span class="text-grey-dark">{{ $t('menuplan.edit.notRevertable') }}</span>
            </div>
        </center-panel>
    </div>
</template>

<script>
    import { bus } from '../../eventbus.js';

    export default {
        components: {
            'menuplan-form': require('./form.vue').default
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
                axios.get(this.endpoint).then(response => {
                    this.menuplan = response.data;
                });
            },
            save() {
                axios.put(this.endpoint, this.menuplan).then(response => {
                    router.push('/');
                });
            },
            cancel() {
                router.go(-1);
            },
            confirmDelete() {
                this.confirmMode = true;
            },
            cancelDelete() {
                this.confirmMode = false;
            },
            deleteMenuplan() {
                axios.delete(this.endpoint).then(response => {
                    router.push('/');
                });
            }
        }
    }
</script>
