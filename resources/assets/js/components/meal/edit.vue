<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-grey-darkest text-xl bg-white">
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
                    <div class="flex items-center mb-2" v-for="ingredient in ingredients" :key="ingredient.id">
                        <div class="w-1/3 pr-2">
                            <input @change="updateIngredient(ingredient)" class="form-control text-right" type="number" v-model="ingredient.quantity" />
                        </div>
                        <div class="w-1/6" v-text="getUnitForItemId(ingredient.item_id)"></div>
                        <div class="w-1/2 pr-2">
                            <select @change="updateIngredient(ingredient)" class="w-full block appearance-none bg-white border p-1 h-8" v-model="ingredient.item_id">
                                <option v-for="item in items" v-text="item.title" :value="item.id" :key="item.id"></option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-1/3 pr-2">
                            <input @change="addIngredient()" class="form-control text-right" type="number" v-model="newIngredient.quantity"/>
                        </div>
                        <div class="w-1/6" v-text="getUnitForItemId(newIngredient.item_id)"></div>
                        <div class="w-1/2 pr-2">
                            <select @change="addIngredient()" class="w-full block appearance-none bg-white border p-1 h-8" v-model="newIngredient.item_id">
                                <option disabled value="0">Select an item</option>
                                <option v-for="item in items" v-text="item.title" :value="item.id" :key="item.id"></option>
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
                ingredients: [],
                items: [],
                newIngredient: {
                    item_id: 0,
                    quantity: 0
                },
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
            this.fetchIngredients();
        },
        methods: {
            fetchIngredients() {
                axios.get(this.endpoint + '/ingredients').then(response => {
                    this.ingredients = response.data;
                });
            },
            fetchMeal() {
                axios.get(this.endpoint).then(response => {
                    this.meal = response.data;
                    this.fetchItems(this.meal.menuplan_id);
                });
            },
            fetchItems(menuplanId) {
                let endpoint = '/api/menuplan/' + menuplanId + '/items';
                axios.get(endpoint).then(response => {
                    this.items = response.data;
                });
            },
            save() {
                axios.put(this.endpoint, this.meal).then(response => {
                    router.go(-1);
                });
            },
            cancel() {
                router.go(-1);
            },
            updateIngredient(ingredient) {
                let endpoint = '/api/ingredient/' + ingredient.id;
                let data = { quantity: ingredient.quantity, item_id: ingredient.item_id };
                axios.put(endpoint, data).then(response => {
                });
            },
            getUnitForItemId(itemId) {
                let items = this.items.filter(i => {
                    return i.id == itemId;
                });
                
                return items.length > 0 ? items[0].unit : '';
            },
            addIngredient() {
                if (this.newIngredient.item_id != 0 && this.newIngredient.quantity > 0) {
                    axios.post(this.endpoint + '/ingredients', this.newIngredient).then(response => {
                        this.ingredients.push(response.data);
                        this.newIngredient.item_id = 0;
                        this.newIngredient.quantity = 0;
                    });
                }
            }
        }
    }
</script>