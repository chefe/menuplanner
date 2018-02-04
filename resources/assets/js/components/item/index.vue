<template>
    <div class="container mx-auto">
        <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-grey-darkest text-xl bg-white">
            <h1 class="flex-1">Items</h1>
            <router-link :to="'/menuplan/' + $route.params.id" class="text-grey-darkest ml-4">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 0h9v9H0V0zm2 2v5h5V2H2zm-2 9h9v9H0v-9zm2 2v5h5v-5H2zm9-13h9v9h-9V0zm2 2v5h5V2h-5zm-2 9h9v9h-9v-9zm2 2v5h5v-5h-5z"/></svg>
            </router-link>
            <router-link to="/" class="text-grey-darkest ml-4">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"/></svg>
            </router-link>
        </div>

        <div class="bg-white m-2 p-2 shadow border">
            <table class="w-full">
                <thead>
                    <tr>
                        <th width="50%" class="p-1 text-left">Title</th>
                        <th width="40%" class="p-1 text-left">Unit</th>
                        <th width="10%" style="min-width:40px"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id">
                        <td class="p-1">
                            <span v-show="!item.editing" v-text="item.title"></span>
                            <input v-show="item.editing" type="text" class="form-control" v-model="item.title">
                        </td>
                        <td class="p-1">
                            <span v-show="!item.editing" v-text="item.unit"></span>
                            <input v-show="item.editing" type="text" class="form-control" v-model="item.unit">
                        </td>
                        <td class="text-right">
                            <a v-show="!item.editing" @click="edit(item)" class="cursor-pointer">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/></svg>
                            </a>
                            <a v-show="item.editing" @click="save(item)" class="cursor-pointer">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
                            </a>
                            <a v-show="item.editing" @click="cancel(item)" class="cursor-pointer">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/></svg>
                            </a>
                            <template v-show="!item.editing">
                                <a v-show="item.can_be_deleted" @click="deleteItem(item)" class="cursor-pointer">
                                    <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/></svg>
                                </a>
                                <span v-show="!item.can_be_deleted" class="text-grey cursor-not-allowed">
                                    <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/></svg>
                                </span>
                            </template>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1">
                            <input type="text" class="form-control" v-model="newitem.title" placeholder="Title">
                        </td>
                        <td class="p-1">
                            <input type="text" class="form-control" v-model="newitem.unit" placeholder="Unit">
                        </td>
                        <td class="text-right">
                            <a @click="addItem()" class="cursor-pointer">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
                            </a>
                            <a @click="cancelAdding()" class="cursor-pointer">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/></svg>
                            </a>
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
                endpoint: '',
                items: [], 
                newitem: {
                    title: '',
                    unit: ''
                }
            };
        },
        mounted() {
            this.endpoint = '/api/menuplan/' + this.$route.params.id + '/items';
            this.fetchItems();
        }, 
        methods: {
            fetchItems() {
                axios.get(this.endpoint).then(response => {
                    this.items = response.data.map(i => {
                        i.editing = false;
                        return i;
                    });
                });
            },
            save(item) {
                let endpoint = '/api/item/' + item.id;
                axios.put(endpoint, item).then(response => {
                    item.editing = false;
                });
            },
            edit(item) {
                item.editing = true;
            },
            cancel(item) {
                item.editing = false;
            },
            deleteItem(item) {
                let endpoint = '/api/item/' + item.id;
                axios.delete(endpoint).then(response => {
                    this.items = this.items.filter(i => {
                        return i.id != item.id; 
                    });
                });
            },
            addItem() {
                axios.post(this.endpoint, this.newitem).then(response => {
                    let item = response.data;
                    item.editing = false;
                    this.items.push(item);
                    this.cancelAdding();
                });
            },
            cancelAdding() {
                this.newitem.title = '';
                this.newitem.unit = '';
            }
        }
    }
</script>