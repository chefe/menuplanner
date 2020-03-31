<template>
    <div class="container mx-auto">
        <page-title
            :title="$t('meal.edit.editMeal')"
            :links="pageTitleLinks">
        </page-title>

        <div class="flex flex-wrap flex-col-reverse md:flex-row">
            <div class="w-full md:w-2/3 p-2">
                <div class="bg-white rounded border-b-2 p-2 mb-4">
                    <h2 class="text-2xl mb-2 pb-2 text-gray-800 border-b">{{ $t('meal.edit.description') }}</h2>
                    <editor v-model="meal.description" :placeholder="$t('meal.edit.enterDescription')"></editor>
                </div>

                <div class="bg-white rounded border-b-2 p-2">
                    <h2 class="text-2xl mb-2 pb-2 border-b">
                        <span class="text-gray-800">{{ $t('meal.edit.ingredientsFor') }}</span>
                        <input class="text-gray-800 border-b border-dashed w-16 text-right"
                               type="number"
                               name="ingredients_for"
                               v-model="meal.ingredients_for"
                               min="1"
                               required />
                        <span class="text-gray-800">{{ $t('meal.edit.people') }}</span>
                    </h2>
                    <div class="flex items-center mb-2" v-for="ingredient in ingredients" :key="ingredient.id">
                        <div class="w-16 sm:flex-1 pr-2">
                            <input @change="updateIngredient(ingredient)"
                                   class="form-control text-right"
                                   type="number"
                                   v-model="ingredient.quantity" />
                        </div>
                        <div class="w-16 sm:w-32" v-text="getUnitForItemId(ingredient.item_id)"></div>
                        <div class="flex-1">
                            <multiselect
                                @input="i => ingredient.item_id = i.id"
                                @select="updateIngredient(ingredient)"
                                :value="getItemByIngredient(ingredient)"
                                :custom-label="i => i.title"
                                :options="items"
                                :allowEmpty="false"
                                :showLabels="false"
                                :option-height="32"
                                :placeholder="$t('meal.edit.selectPlaceholder')"
                            >
                                <span slot="noResult">{{ $t('meal.edit.nothingFound') }}</span>
                                <span slot="noOptions">{{ $t('meal.edit.noOptions') }}</span>
                            </multiselect>
                        </div>
                        <div class="w-8 text-center">
                            <a @click="deleteIngredient(ingredient)" class="cursor-pointer">
                                <icon name="trash"></icon>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-16 sm:flex-1 pr-2">
                            <input @change="addIngredient()"
                                   class="form-control text-right"
                                   type="number"
                                   v-model="newIngredient.quantity"/>
                        </div>
                        <div class="w-16 sm:w-32" v-text="getUnitForItemId(newIngredient.item_id)"></div>
                        <div class="flex-1">
                            <multiselect
                                @input="i => newIngredient.item_id = i.id"
                                @select="addIngredient()"
                                :value="getItemByIngredient(newIngredient)"
                                :custom-label="i => i.title"
                                :options="items"
                                :allowEmpty="false"
                                :showLabels="false"
                                :option-height="32"
                                :placeholder="$t('meal.edit.selectPlaceholder')"
                                :taggable="true"
                                @tag="showAddItemModal"
                                :tagPlaceholder="$t('meal.edit.tagPlaceholder')"
                            >
                                <span slot="noResult">{{ $t('meal.edit.nothingFound') }}</span>
                                <span slot="noOptions">{{ $t('meal.edit.noOptions') }}</span>
                            </multiselect>
                        </div>
                        <div class="w-8"></div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/3 p-2">
                <div class="bg-white rounded border-b-2 p-2">
                    <h2 class="text-2xl mb-2 pb-2 text-gray-800 border-b">{{ $t('meal.edit.settings') }}</h2>
                    <form-item caption="general.title">
                        <input class="form-control"
                               type="text"
                               name="title"
                               v-model.lazy="meal.title"
                               :placeholder="$t('general.provideTitle')"
                               required />
                    </form-item>
                    <form-item caption="general.date">
                        <input class="form-control"
                               type="date"
                               name="date"
                               v-model.lazy="meal.date"
                               :min="meal.menuplan.start"
                               :max="meal.menuplan.end"
                               required />
                    </form-item>
                    <form-item caption="general.start">
                        <input class="form-control" type="time" name="start" v-model.lazy="meal.start" required />
                    </form-item>
                    <form-item caption="general.end">
                        <input class="form-control" type="time" name="end" v-model.lazy="meal.end" required />
                    </form-item>
                    <form-item caption="general.people">
                        <input class="form-control"
                               type="number"
                               name="people"
                               v-model.lazy="meal.people"
                               min="1"
                               :placeholder="meal.menuplan.people"
                               required />
                    </form-item>
                </div>

                <div class="bg-white rounded border-b-2 p-2 mt-2">
                    <h2 class="text-2xl mb-2 pb-2 text-gray-800 border-b">{{ $t('general.actions') }}</h2>
                    <delete-button :delete-callback="deleteMeal"></delete-button>
                </div>
            </div>
        </div>

        <add-item-modal :caption="addItemModal.caption"
                        :show="addItemModal.show"
                        :menuplanId="meal.menuplan_id"
                        @addItem="addItem"
                        @hide="addItemModal.show = false"
        ></add-item-modal>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    import AddItemModal from '../item/add-item-modal'

    export default {
        components: {
            Multiselect,
            AddItemModal
        },
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
                timeout: undefined,
                addItemModal: {
                    caption: '',
                    show: false
                },
                pageTitleLinks: []
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
                    this.pageTitleLinks.push({
                        to: '/menuplan/' + this.meal.menuplan_id,
                        caption: this.$t('menuplan.share.viewMenuplan'),
                        icon: 'tile'
                    });
                    this.fetchItems(this.meal.menuplan_id);
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
                if (this.newIngredient.item != {} && this.newIngredient.quantity > 0) {
                    axios.post(this.endpoint + '/ingredients', this.newIngredient).then(response => {
                        this.ingredients.push(response.data);
                        this.newIngredient.item_id = 0;
                        this.newIngredient.quantity = 0;
                    });
                }
            },
            deleteIngredient(ingredient) {
                let endpoint = '/api/ingredient/' + ingredient.id;
                axios.delete(endpoint).then(response => {
                    this.ingredients = this.ingredients.filter(i => {
                        return i.id != ingredient.id;
                    });
                });
            },
            getItemByIngredient(ingredient) {
                let id = ingredient != undefined ? ingredient.item_id : 0;
                let filtered = this.items.filter(i => i.id == id);
                return filtered.length > 0 ? filtered[0] : undefined;
            },
            showAddItemModal(caption) {
                this.addItemModal.caption = caption;
                this.addItemModal.show = true;
            },
            addItem(item) {
                this.items.push(item);
                this.items = this.items.sort((a, b) => {
                    return a.title.localeCompare(b.title);
                });
                this.newIngredient.item_id = item.id;
            },
            deleteMeal() {
                axios.delete(this.endpoint).then(response => {
                    router.push('/menuplan/' + this.meal.menuplan.id);
                });
            }
        }
    }
</script>
