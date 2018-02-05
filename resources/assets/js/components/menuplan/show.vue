<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-grey-darkest text-xl bg-white">
            <h1 class="flex-1">
                <span class="mr-2" v-text="menuplan.title"></span>
                <small class="text-grey-dark" v-text="duration"></small>
            </h1>
            <router-link :to="'/menuplan/' + $route.params.id + '/items'" class="text-grey-darkest ml-4">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M18 9.87V20H2V9.87a4.25 4.25 0 0 0 3-.38V14h10V9.5a4.26 4.26 0 0 0 3 .37zM3 0h4l-.67 6.03A3.43 3.43 0 0 1 3 9C1.34 9 .42 7.73.95 6.15L3 0zm5 0h4l.7 6.3c.17 1.5-.91 2.7-2.42 2.7h-.56A2.38 2.38 0 0 1 7.3 6.3L8 0zm5 0h4l2.05 6.15C19.58 7.73 18.65 9 17 9a3.42 3.42 0 0 1-3.33-2.97L13 0z"/></svg>
            </router-link>
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
                    <a @click="addMeal(day)" class="block no-underline border border-dashed text-grey bg-transparent p-2 mt-3 cursor-pointer">
                        Add new meal
                    </a>
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
        computed: {
            duration() {
                return moment(this.menuplan.start).format('Do MMM') + 
                    ' - ' + moment(this.menuplan.end).format('Do MMM');
            }
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
            },
            addMeal(date) {
                router.push({ 
                    path: '/menuplan/' + this.$route.params.id + '/meal/create', 
                    query: { date: date.format('YYYY-MM-DD') }
                });
            }
        }
    }
</script>