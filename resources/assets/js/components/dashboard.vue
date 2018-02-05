<template>
    <center-panel>
        <template slot="header">
            <span class="flex-1">Menuplans</span>
            <router-link to="/menuplan/create" class="text-grey-dark hover:text-grey-darkest no-underline">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/></svg>
            </router-link>
        </template>

        <div v-for="menuplan in menuplans" class="flex text-sm p-3 border-b items-center" :key="menuplan.id">
            <router-link :to="'/menuplan/' + menuplan.id" class="flex-1 text-grey-dark hover:text-grey-darkest no-underline">
                <span class="mr-2" v-text="menuplan.title"></span>
                <small class="text-grey" v-text="getDuration(menuplan)"></small>
            </router-link>
            <router-link :to="'/menuplan/' + menuplan.id + '/edit'" class="text-grey-dark hover:text-grey-darkest">
                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M3.94 6.5L2.22 3.64l1.42-1.42L6.5 3.94c.52-.3 1.1-.54 1.7-.7L9 0h2l.8 3.24c.6.16 1.18.4 1.7.7l2.86-1.72 1.42 1.42-1.72 2.86c.3.52.54 1.1.7 1.7L20 9v2l-3.24.8c-.16.6-.4 1.18-.7 1.7l1.72 2.86-1.42 1.42-2.86-1.72c-.52.3-1.1.54-1.7.7L11 20H9l-.8-3.24c-.6-.16-1.18-.4-1.7-.7l-2.86 1.72-1.42-1.42 1.72-2.86c-.3-.52-.54-1.1-.7-1.7L0 11V9l3.24-.8c.16-.6.4-1.18.7-1.7zM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
            </router-link>
        </div>

        <div v-if="menuplans.length == 0" class="flex text-sm p-3 border-b items-center">
            <router-link to="/menuplan/create" class="flex-1 text-grey-dark hover:text-grey-darkest no-underline">
                Create your first menuplan
            </router-link>
        </div>
    </center-panel>
</template>

<script>
    import { bus } from '../eventbus.js';
    import moment from 'moment';

    export default {
        data() {
            return {
                menuplans: [],
            }
        },
        mounted() {
            this.fetchMenuplans();
        },
        methods: {
            fetchMenuplans() {
                bus.$emit('loading', true);
                let that = this;
                axios.get('api/menuplan')
                    .then(function (response) {
                        bus.$emit('loading', false);
                        that.menuplans = response.data;
                    })
                    .catch(function (error) {
                        bus.$emit('error', error);
                    });
            },
            getDuration(menuplan) {
                return moment(menuplan.start).format('Do MMM') + 
                    ' - ' + moment(menuplan.end).format('Do MMM');
            }
        }
    }
</script>