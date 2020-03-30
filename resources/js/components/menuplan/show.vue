<template>
    <div class="container mx-auto">
        <page-title
            :title="menuplan.title"
            :subtitle="duration"
            :links="pageTitleLinks">
        </page-title>
        <div class="flex flex-wrap bg-white rounded shadow-b border-b-2 mx-2">
            <div v-for="day in days" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2 flex" :key="day.format()">
                <div class="flex-1 bg-white">
                    <day-title :date="day" />
                    <div v-for="event in getEventsForDate(day)" :key="event.id">
                        <meal-link
                            v-if="event.meal"
                            :meal="event.meal"
                            :menuplan="menuplan">
                        </meal-link>
                        <purchase-link
                            v-if="event.purchase"
                            :purchase="event.purchase">
                        </purchase-link>
                    </div>
                    <add-button
                        :date="day"
                        :menuplan="menuplan"
                        @mealAdded="fetchMeals"
                        @purchaseAdded="fetchPurchases" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AddButton from './utilities/add-button.vue';
    import PurchaseLink from './utilities/purchase-link.vue';
    import MealLink from './utilities/meal-link.vue';
    import DayTitle from './utilities/day-title.vue';
    import moment from 'moment';

    export default {
        data() {
            return {
                endpoint: '',
                days: [],
                meals: [],
                purchases: [],
                menuplan: {
                    title: '',
                    start: '',
                    end: ''
                },
                pageTitleLinks: [
                    {
                        href: '/menuplan/' + this.$route.params.id + '/pdf',
                        caption: this.$t('general.downloadAsPdf'),
                        icon: 'download'
                    },
                    {
                        to: '/menuplan/' + this.$route.params.id + '/shopping-list',
                        caption: this.$t('shoppinglist.index.shoppinglist'),
                        icon: 'cart'
                    },
                    {
                        to: '/menuplan/' + this.$route.params.id + '/items',
                        caption: this.$t('item.index.items'),
                        icon: 'bulletlist'
                    },
                ]
            }
        },
        components: {
            AddButton,
            PurchaseLink,
            MealLink,
            DayTitle
        },
        created() {
            moment.locale(this.$i18n.locale);
        },
        mounted() {
            this.endpoint = '/api/menuplan/' + this.$route.params.id;
            this.fetchMenuplan();
            this.fetchMeals();
            this.fetchPurchases();
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
            fetchPurchases() {
                axios.get(this.endpoint + '/purchases').then(response => {
                    this.purchases = response.data;
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
            getEventsForDate(date) {
                let events = [];

                this.meals.filter(function (meal) {
                    return moment(meal.date).format('YYYYMMDD') == date.format('YYYYMMDD');
                }).forEach(m => {
                    events.push({
                        id: 'm'+m.id,
                        meal: m,
                        time: m.start
                    });
                });

                this.purchases.filter(function (purchase) {
                    return moment(purchase.date).format('YYYYMMDD') == date.format('YYYYMMDD');
                }).forEach(p => {
                    events.push({
                        id: 'p'+p.id,
                        purchase: p,
                        time: p.time
                    });
                });

                return events.sort(function (a, b) {
                    return a.time.localeCompare(b.time);
                });
            },
        }
    }
</script>
