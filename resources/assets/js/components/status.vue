<template>
    <div>
        <div v-if="loading">
            Loading ..
        </div>
    </div>
</template>

<script>
    import { bus } from '../eventbus.js';

    export default {
        data() {
            return { 
                loading: false 
            };
        },
        mounted() {
            bus.$on('loading', function (flag) {
                this.loading = flag;
            });

            bus.$on('error', function (error) {
                console.log(error);
            });
        }
    }
</script>