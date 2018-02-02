<template>
    <div class="w-full mx-auto sm:w-3/4 lg:w-1/2 mb-8">
        <div v-if="loading">
            Loading ..
        </div>

        <div v-if="error.show" class="bg-red-lightest border-red rounded text-red-darkest px-4 py-3 shadow">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-red mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                    <div>
                        <p class="font-bold" v-text="error.message"></p>
                        <p v-for="point in error.points" class="text-sm" v-text="point" :key="point"></p>
    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import { bus } from '../eventbus.js';

    export default {
        data() {
            return { 
                loading: false,
                error: {
                    message: '',
                    points: [],
                    show: false
                }
            };
        },
        mounted() {
            let that = this;

            router.beforeEach((to, from, next) => {
                that.error.show = false;

                next();
            });

            bus.$on('loading', function (flag) {
                this.loading = flag;
            });

            bus.$on('error', function (error) {
                if (error.response.status == 422) {
                    that.handleValidationError(error.response.data);
                }
            });
        },
        methods: {
            handleValidationError(errorData) {
                this.error.message = errorData.message;
                console.dir(errorData.errors);
                this.error.points = Object.keys(errorData.errors).map((key, index) => {
                    return errorData.errors[key][0];
            });
                this.error.show = true;
            }
        }
    }
</script>