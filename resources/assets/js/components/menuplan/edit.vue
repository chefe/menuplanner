<template>
    <center-panel>
        <template slot="header">Edit Menuplan</template>
        <form @submit.prevent="save" action="POST">
            <div class="flex items-center p-2 flex-row">
                <label class="w-1/3 mb-0 pr-4 text-right" for="title">Title:</label>
                <input class="w-2/3" type="text" name="title" v-model="menuplan.title" placeholder="Please provide a title" required />
            </div>
            <div class="flex items-center p-2 flex-row">
                <label class="w-1/3 mb-0 pr-4 text-right" for="start" >Start:</label>
                <input class="w-2/3" type="date" name="start" v-model="menuplan.start" required />
            </div>
            <div class="flex items-center p-2 flex-row">
                <label class="w-1/3 mb-0 pr-4 text-right" for="end">End:</label>
                <input class="w-2/3" type="date" name="end" v-model="menuplan.end" required />
            </div>
            <div class="flex items-center p-2 flex-row">
                <label class="w-1/3 mb-0 pr-4 text-right" for="people">People:</label>
                <input class="w-2/3" type="number" name="people" v-model="menuplan.people" min="1" required />
            </div>
            <div class="w-2/3 ml-auto flex py-2 flex-row">
                <a class="btn-secondary flex-1" @click="cancel">
                    Cancel
                </a>
                <button type="submit" class="btn-primary flex-1">Save</button>
            </div>
        </form>
    </center-panel>
</template>

<script>
    import { bus } from '../../eventbus.js';

    export default {
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