<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-grey-darkest text-xl bg-white">
            <h1 class="flex-1">
                <span class="mr-2" v-text="menuplan.title"></span>
                <small class="text-grey-dark" v-text="duration"></small>
            </h1>
            <a :href="'/menuplan/' + $route.params.id + '/pdf'" class="text-grey-darkest ml-4">
                <icon name="download" size="8"></icon>
            </a>
            <router-link :to="'/menuplan/' + $route.params.id + '/shopping-list'" class="text-grey-darkest ml-4">
                <icon name="cart" size="8"></icon>
            </router-link>
            <router-link :to="'/menuplan/' + $route.params.id + '/items'" class="text-grey-darkest ml-4">
                <icon name="bulletlist" size="8"></icon>
            </router-link>
        </div>
        <div class="flex flex-wrap bg-white rounded shadow-b border-b-2 mx-2">
            <div v-for="day in days" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2 flex" :key="day.format()">
                <div class="flex-1 bg-white">
                    <p class="text-xl border-b text-grey-darkest px-2 py-3 mb-2" v-text="day.format('dddd, Do MMM')"></p>
                    <router-link :to="'/meal/' + meal.id + '/edit'" 
                                 v-for="meal in getMealsForDate(day)" 
                                 :key="meal.id" 
                                 class="block no-underline px-2 py-4 hover:bg-grey-lighter rounded">
                        <p class="text-grey-darker" v-text="meal.title"></p>
                        <small class="text-grey">
                            {{ getMealTime(meal) }} &middot; 
                            {{ getMealPeople(meal) }} 
                            {{ $t('general.people') }}
                        </small>
                    </router-link>
                    <a @click="addMeal(day)" 
                       class="block no-underline text-grey px-2 py-3 cursor-pointer flex hover:bg-grey-lighter rounded">
                       <icon name="add"></icon>
                        <span class="ml-2">{{ $t('menuplan.show.addNewMeal') }}</span>
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
        watch: {
            '$i18n.locale': function (val) {
                moment.locale(val);
                this.setupDays();
                this.foreReevaluateOfDurationProperty();
            }
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
                }).sort(function (a, b) {
                    return a.start.localeCompare(b.start);
                });
            },
            getMealTime(meal) {
                return meal.start + ' - ' + meal.end;
            },
            getMealPeople(meal) {
                return meal.people ? meal.people : this.menuplan.people;
            },
            addMeal(date) {
                router.push({ 
                    path: '/menuplan/' + this.$route.params.id + '/meal/create', 
                    query: { date: date.format('YYYY-MM-DD') }
                });
            },
            foreReevaluateOfDurationProperty() {
                let start = this.menuplan.start;
                this.menuplan.start = '2000-01-01';
                this.menuplan.start = start;
            }
        }
    }
</script>