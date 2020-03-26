<template>
    <div>
        <vue-progress-bar></vue-progress-bar>

        <transition name="fade">
            <div v-if="error.show" class="w-full mx-auto sm:w-3/4 lg:w-1/2 mb-8">
                <div class="bg-red-100 border-red-500 rounded text-red-800 px-4 py-3 shadow">
                    <div class="flex">
                        <div class="py-1 text-red-500 mr-4">
                            <icon name="information" size="6"></icon>
                        </div>
                        <div>
                            <p class="font-bold" v-text="error.message"></p>
                            <p v-for="point in error.points" class="text-sm" v-text="point" :key="point"></p>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        data() {
            return {
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
                that.clearError();
                next();
            });

            window.axios.interceptors.request.use(function (config) {
                that.clearError();
                that.$Progress.start();
                return config;
            }, function (error) {
                return Promise.reject(error);
            });

            axios.interceptors.response.use(function (response) {
                that.$Progress.finish();
                return response;
            }, function (error) {
                that.$Progress.fail();
                that.handleError(error);
                return Promise.reject(error);
            });

        },
        methods: {
            clearError() {
                this.error.show = false;
            },
            handleError(error) {
                if (error.response.status == 422) {
                    this.handleValidationError(error.response.data);
                } else {
                    console.dir(error);
                }
            },
            handleValidationError(errorData) {
                this.error.message = errorData.message;
                this.error.points = Object.keys(errorData.errors).map((key, index) => {
                    return errorData.errors[key][0];
                });
                this.error.show = true;
            }
        }
    }
</script>
