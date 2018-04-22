<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-grey-darkest text-xl bg-white">
            <h1 class="flex-1">{{ $t('shoppinglist.index.shoppinglist') }}</h1>
            <a :href="'/menuplan/' + $route.params.id + '/shopping-list/pdf'" class="text-grey-darkest ml-4">
                <icon name="download" size="8"></icon>
            </a>
            <router-link :to="'/menuplan/' + $route.params.id" class="text-grey-darkest ml-4">
                <icon name="tile" size="8"></icon>
            </router-link>
            <router-link to="/" class="text-grey-darkest ml-4">
                <icon name="list" size="8"></icon>
            </router-link>
        </div>

        <div class="bg-white m-2 p-2 shadow border">
            <table class="w-full">
                <tbody>
                    <tr v-for="item in shoppingList" :key="item.id">
                        <td class="p-1 text-right" v-text="item.quantity"></td>
                        <td class="p-1" v-text="item.unit"></td>
                        <td class="p-1" v-text="item.title"></td>
                        <td>
                            <small v-text="quantityPerMealString(item)"></small>
                        </td>
                    </tr>
                </tbody>
            </table>
       </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                shoppingList: [], 
            };
        },
        mounted() {
            this.endpoint = '/api/menuplan/' + this.$route.params.id + '/shopping-list';
            this.fetchShoppingList();
        }, 
        methods: {
            fetchShoppingList() {
                axios.get(this.endpoint).then(response => {
                    this.shoppingList = response.data;
                });
            },
            quantityPerMealString(item) {
                return item.meals.map(m => {
                    return m.title + ' ['  + m.quantity + item.unit + ']';
                }).join(' / ');
            }
        }
    }
</script>