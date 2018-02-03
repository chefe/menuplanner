<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 pb-2 border-b-2 shadow-b text-grey-darkest text-xl">
            <h1 class="flex-1">Edit Meal</h1>
            <a class="btn-secondary mr-2" @click="cancel">Cancel</a>
            <a class="btn-primary" @click="save">Save</a>
        </div>
        <div class="flex flex-wrap flex-col-reverse md:flex-row">
            <div class="w-full md:w-2/3 p-2">
                <div class="bg-white shadow border p-2 mb-4">
                    <h2 class="mb-2 pb-2 text-grey-darkest border-b">Descriptions</h2>
                    <textarea class="w-full border" rows="10" v-model="meal.description" placeholder="Enter description ..."></textarea>
                </div>

                <div class="bg-white shadow border p-2">
                    <h2 class="mb-2 pb-2 text-grey-darkest border-b">Ingredients</h2>
                    <div class="flex items-center mb-2" v-for="ingredient in meal.ingredients" :key="ingredient.id">
                        <div class="w-1/3 pr-2">
                            <input class="form-control" type="number"/>
                        </div>
                        <div class="w-1/6">g</div>
                        <div class="w-1/2 pr-2">
                            <select class="w-full block appearance-none bg-white border p-1 h-8">
                                <option>Demo</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-1/3 pr-2">
                            <input class="form-control" type="number"/>
                        </div>
                        <div class="w-1/6">g</div>
                        <div class="w-1/2 pr-2">
                            <select class="w-full block appearance-none bg-white border p-1 h-8">
                                <option>Demo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/3 p-2">
                <div class="bg-white shadow border p-2">
                    <h2 class="mb-2 pb-2 text-grey-darkest border-b">Settings</h2>
                    <form-item caption="Title">
                        <input class="form-control" type="text" name="title" v-model="meal.title" 
                            placeholder="Please provide a title" required />
                    </form-item>
                    <form-item caption="Date">
                        <input class="form-control" type="date" name="date" v-model="meal.date"
                               :min="meal.menuplan.start" :max="meal.menuplan.end" required />
                    </form-item>
                    <form-item caption="Start">
                        <input class="form-control" type="time" name="start" v-model="meal.start" required />
                    </form-item>
                    <form-item caption="End">
                        <input class="form-control" type="time" name="end" v-model="meal.end" required />
                    </form-item>
                    <form-item caption="People">
                        <input class="form-control" type="number" name="people" v-model="meal.people" 
                            min="1" :placeholder="meal.menuplan.people" required />
                    </form-item>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                endpoint: '',
                meal: {
                    description: '',
                    menuplan: {
                        people: 0
                    }
                },
            }
        },
        mounted() {
            this.endpoint = '/api/meal/' + this.$route.params.id;
            this.fetchMeal();
        },
        methods: {
            fetchMeal() {
                axios.get(this.endpoint).then(response => {
                    this.meal = response.data;
                });
            },
            save() {
                axios.put(this.endpoint, this.meal).then(response => {
                    router.go(-1);
                });
            },
            cancel() {
                router.go(-1);
            }
        }
    }
</script>