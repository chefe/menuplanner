<template>
    <center-panel>
        <template slot="header">{{ $t('menuplan.create.createMenuplan') }}</template>
        <menuplan-form :menuplan="menuplan" @submit="save" @cancel="cancel">
        </menuplan-form>
    </center-panel>
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
                    people: 1,
                }
            }
        },
        methods: {
            save() {
                axios.post('/api/menuplan', this.menuplan).then(response => {
                    router.push('/');
                });
            },
            cancel() {
                router.go(-1);
            }
        }
    }
</script>
