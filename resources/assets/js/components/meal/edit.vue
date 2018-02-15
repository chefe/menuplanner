<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-grey-darkest text-xl bg-white">
            <h1 class="flex-1">Edit Meal</h1>
            <router-link :to="'/menuplan/' + meal.menuplan_id" class="text-grey-darkest ml-4">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 0h9v9H0V0zm2 2v5h5V2H2zm-2 9h9v9H0v-9zm2 2v5h5v-5H2zm9-13h9v9h-9V0zm2 2v5h5V2h-5zm-2 9h9v9h-9v-9zm2 2v5h5v-5h-5z"/></svg>
            </router-link>
            <router-link to="/" class="text-grey-darkest ml-4">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"/></svg>
            </router-link>
        </div>
        <div class="flex flex-wrap flex-col-reverse md:flex-row">
            <div class="w-full md:w-2/3 p-2">
                <div class="bg-white shadow border p-2 mb-4">
                    <h2 class="mb-2 pb-2 text-grey-darkest border-b">Descriptions</h2>
                    <trix-editor input="trix4Description" placeholder="Enter description ..."></trix-editor>
                </div>

                <div class="bg-white shadow border p-2">
                    <h2 class="mb-2 pb-2 text-grey-darkest border-b">
                        <span>Ingredients for</span>
                        <input class="text-grey-darkest w-16 text-right" type="number" name="ingredients_for" v-model="meal.ingredients_for" min="1" required />
                        <span>people</span>
                    </h2>
                    <div class="flex items-center mb-2" v-for="ingredient in ingredients" :key="ingredient.id">
                        <div class="flex-1 pr-2">
                            <input @change="updateIngredient(ingredient)" class="form-control text-right" type="number" v-model="ingredient.quantity" />
                        </div>
                        <div class="w-32" v-text="getUnitForItemId(ingredient.item_id)"></div>
                        <div class="flex-1">
                            <select @change="updateIngredient(ingredient)" class="w-full block appearance-none bg-white border p-1 h-8" v-model="ingredient.item_id">
                                <option v-for="item in items" v-text="item.title" :value="item.id" :key="item.id"></option>
                            </select>
                        </div>
                        <div class="w-8 text-center">
                            <a @click="deleteIngredient(ingredient)" class="cursor-pointer">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/></svg>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="flex-1 pr-2">
                            <input @change="addIngredient()" class="form-control text-right" type="number" v-model="newIngredient.quantity"/>
                        </div>
                        <div class="w-32" v-text="getUnitForItemId(newIngredient.item_id)"></div>
                        <div class="flex-1">
                            <select @change="addIngredient()" class="w-full block appearance-none bg-white border p-1 h-8" v-model="newIngredient.item_id">
                                <option disabled value="0">Select an item</option>
                                <option v-for="item in items" v-text="item.title" :value="item.id" :key="item.id"></option>
                            </select>
                        </div>
                        <div class="w-8"></div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/3 p-2">
                <div class="bg-white shadow border p-2">
                    <h2 class="mb-2 pb-2 text-grey-darkest border-b">Settings</h2>
                    <form-item caption="Title">
                        <input class="form-control" type="text" name="title" v-model.lazy="meal.title" 
                            placeholder="Please provide a title" required />
                    </form-item>
                    <form-item caption="Date">
                        <input class="form-control" type="date" name="date" v-model.lazy="meal.date"
                               :min="meal.menuplan.start" :max="meal.menuplan.end" required />
                    </form-item>
                    <form-item caption="Start">
                        <input class="form-control" type="time" name="start" v-model.lazy="meal.start" required />
                    </form-item>
                    <form-item caption="End">
                        <input class="form-control" type="time" name="end" v-model.lazy="meal.end" required />
                    </form-item>
                    <form-item caption="People">
                        <input class="form-control" type="number" name="people" v-model.lazy="meal.people" 
                            min="1" :placeholder="meal.menuplan.people" required />
                    </form-item>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'trix';
    import 'trix/dist/trix.css';
    
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
                timeout: undefined
            }
        },
        mounted() {
            this.endpoint = '/api/meal/' + this.$route.params.id;
            this.fetchMeal();
            this.fetchIngredients();
        },
        watch: {
            meal: {
                handler: function (val, oldVal) { axios.put(this.endpoint, this.meal); },
                deep: true
            }
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
                    document.querySelector("trix-editor").editor.insertHTML(this.meal.description);
                    
                    let vm = this;
                    document.querySelector("trix-editor").addEventListener('trix-change', (e) => {
                        let html = e.currentTarget.innerHTML;
                        clearTimeout(this.timeout);
                        this.timeout = setTimeout(() => {
                            vm.meal.description = html;
                        }, 1000);
                    });
                });
            },
            fetchItems(menuplanId) {
                let endpoint = '/api/menuplan/' + menuplanId + '/items';
                axios.get(endpoint).then(response => {
                    this.items = response.data;
                });
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
            },
            deleteIngredient(ingredient) {
                if (confirm('Are you sure?')) {
                    let endpoint = '/api/ingredient/' + ingredient.id;
                    axios.delete(endpoint).then(response => {
                        this.ingredients = this.ingredients.filter(i => {
                            return i.id != ingredient.id; 
                        });
                    });
                }
            }
        }
    }
</script>