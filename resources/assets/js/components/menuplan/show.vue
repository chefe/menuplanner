<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-grey-darkest text-xl bg-white">
            <h1 class="flex-1" v-text="menuplan.title"></h1>
            <router-link to="/" class="text-grey-darkest ml-4">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"/></svg>
            </router-link>
        </div>
        <div class="flex flex-wrap">
            <div v-for="day in days" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2 flex" :key="day.format()">
                <div class="flex-1 bg-white p-2 shadow border">
                    <p class="text-xl border-b text-grey-darkest" v-text="day.format('dddd, Do MMM')"></p>
                    <router-link :to="'/meal/' + meal.id + '/edit'" v-for="meal in getMealsForDate(day)" :key="meal.id" class="block no-underline border p-2 mt-3">
                        <p class="text-grey-darker" v-text="meal.title"></p>
                        <small class="text-grey" v-text="getMealTime(meal)"></small>
                    </router-link>
                    <router-link to="#" class="block no-underline border border-dashed text-grey bg-transparent p-2 mt-3">
                        Add new meal
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                endpoint: '',
                days: [],
                meals: [],
                menuplan: {
                    title: '',
                    start: '',
                    end: ''
                }
            }
        },
        mounted() {
            this.endpoint = '/api/menuplan/' + this.$route.params.id;
            this.fetchMenuplan();
            this.fetchMeals();
        },
        methods: {
            fetchMenuplan() {
                axios.get(this.endpoint).then(response => {
                    this.menuplan = response.data;
                    this.setupDays();
                });
            },
            fetchMeals() {
                axios.get(this.endpoint + '/meals').then(response => {
                    this.meals = response.data;
                });
            },
            setupDays() {
                let start = moment(this.menuplan.start);
                let days = moment(this.menuplan.end).diff(start, 'days');

                this.days = [];
                for (let i = 0; i <= days; i++) {
                    this.days.push(start.clone().add(i, 'd'));
                }
            },
            getMealsForDate(date) {
                return this.meals.filter(function (meal) {
                    return moment(meal.date).format('YYYYMMDD') == date.format('YYYYMMDD');
                });
            },
            getMealTime(meal) {
                return meal.start + ' - ' + meal.end;
            }
        }
    }
</script>