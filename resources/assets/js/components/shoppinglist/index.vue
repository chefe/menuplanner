<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-grey-darkest text-xl bg-white">
            <h1 class="flex-1">{{ $t('shoppinglist.index.shoppinglist') }}</h1>
            <a :href="'/menuplan/' + $route.params.id + '/shopping-list/pdf'" class="text-grey-darkest ml-4">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
            </a>
            <router-link :to="'/menuplan/' + $route.params.id" class="text-grey-darkest ml-4">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 0h9v9H0V0zm2 2v5h5V2H2zm-2 9h9v9H0v-9zm2 2v5h5v-5H2zm9-13h9v9h-9V0zm2 2v5h5V2h-5zm-2 9h9v9h-9v-9zm2 2v5h5v-5h-5z"/></svg>
            </router-link>
            <router-link to="/" class="text-grey-darkest ml-4">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"/></svg>
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