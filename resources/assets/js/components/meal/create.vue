<template>
    <center-panel>
        <template slot="header">Create Meal</template>
        <form @submit.prevent="save">
            <form-item caption="Title">
                <input type="text" name="title" v-model="meal.title" class="form-control"
                       placeholder="Please provide a title" required />
            </form-item>
            <form-item caption="Date">
                <input type="date" name="date" v-model="meal.date" class="form-control" 
                       :min="meal.menuplan.start" :max="meal.menuplan.end" required />
            </form-item>
            <form-item caption="Start">
                <input type="time" name="start" v-model="meal.start" class="form-control" required />
            </form-item>
            <form-item caption="End">
                <input type="time" name="end" v-model="meal.end" class="form-control" required />
            </form-item>
            <form-item caption="People">
                <input type="number" name="people" v-model="meal.people" min="1" 
                       class="form-control" :placeholder="meal.menuplan.people" />
            </form-item>
            
            <div class="w-full sm:w-2/3 ml-auto flex sm:py-2 sm:pr-2 flex-col sm:flex-row">
                <a class="btn-secondary flex-1 sm:mr-2" @click="cancel">Cancel</a>
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
                meal: {
                    title: '',
                    date: '',
                    start: '',
                    end: '',
                    people: '',
                    menuplan: {
                        people: 0
                    }
                }
            }
        },
        mounted() {
            this.fetchMenuplan();
            this.loadDateFromQueryString();
        },
        methods: {
            fetchMenuplan() {
                let endpoint = '/api/menuplan/' + this.$route.params.id;
                axios.get(endpoint).then(response => {
                    this.meal.menuplan = response.data;
                });
            },
            loadDateFromQueryString() {
                if (this.$route.query.date) {
                    this.meal.date = this.$route.query.date;
                }
            },
            save() {
                let endpoint = '/api/menuplan/' + this.$route.params.id + '/meals';
                axios.post(endpoint, this.meal).then(response => {
                    router.push('/meal/' + response.data.id + '/edit');
                });
            },
            cancel() {
                router.go(-1);
            }
        }
    }
</script>