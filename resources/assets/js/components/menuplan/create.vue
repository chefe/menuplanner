<template>
    <center-panel>
        <template slot="header">Create Menuplan</template>
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
                    people: 1,
                }
            }
        },
        methods: {
            save() {
                let that = this;
                axios.post('/api/menuplan', {
                    title: that.menuplan.title,
                    start: that.menuplan.start,
                    end: that.menuplan.end,
                    people: that.menuplan.people
                })
                .then(function (response) {
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