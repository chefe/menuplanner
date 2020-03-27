<template>
    <div class="container mx-auto">
        <page-title
            :title="$t('item.index.items')"
            :links="pageTitleLinks">
        </page-title>

        <div class="bg-white m-2 p-2 rounded border-b-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <th width="50%" class="p-1 text-left">{{ $t('general.title') }}</th>
                        <th width="40%" class="p-1 text-left">{{ $t('item.index.unit') }}</th>
                        <th width="10%" style="min-width:40px"></th>
                    </tr>
                </thead>
                <tbody>
                     <tr>
                        <td class="p-1">
                            <input type="text"
                                   class="form-control"
                                   @keydown.enter="tryToAddItem"
                                   v-model="newitem.title"
                                   :placeholder="$t('general.title')">
                        </td>
                        <td class="p-1">
                            <input type="text"
                                   class="form-control"
                                   @keydown.enter="tryToAddItem"
                                   v-model="newitem.unit"
                                   :placeholder="$t('item.index.unit')">
                        </td>
                        <td class="text-right">
                            <a @click="addItem()" class="cursor-pointer">
                                <icon class="inline" name="checkmark"></icon>
                            </a>
                            <a @click="cancelAdding()" class="cursor-pointer">
                                <icon class="inline" name="close"></icon>
                            </a>
                        </td>
                    </tr>
                    <tr v-for="item in items" :key="item.id">
                        <td class="p-1">
                            <span v-show="!item.editing" v-text="item.title" @dblclick="changeToEditMode(item, 'title')"></span>
                            <input :ref="'title-' + item.id"
                                   v-show="item.editing"
                                   type="text"
                                   @keydown.enter="save(item)"
                                   class="form-control"
                                   v-model="item.title">
                        </td>
                        <td class="p-1">
                            <span v-show="!item.editing" v-text="item.unit" @dblclick="changeToEditMode(item, 'unit')"></span>
                            <input :ref="'unit-' + item.id"
                                   v-show="item.editing"
                                   type="text"
                                   @keydown.enter="save(item)"
                                   class="form-control"
                                   v-model="item.unit">
                        </td>
                        <td class="text-right">
                            <a v-show="!item.editing" @click="edit(item)" class="cursor-pointer">
                                <icon class="inline" name="edit"></icon>
                            </a>
                            <a v-show="item.editing" @click="save(item)" class="cursor-pointer">
                                <icon class="inline" name="checkmark"></icon>
                            </a>
                            <a v-show="item.editing" @click="cancel(item)" class="cursor-pointer">
                                <icon class="inline" name="close"></icon>
                            </a>
                            <template>
                                <a v-show="!item.editing && item.can_be_deleted" @click="deleteItem(item)" class="cursor-pointer">
                                    <icon class="inline" name="trash"></icon>
                                </a>
                                <span v-show="!item.editing && !item.can_be_deleted" class="text-gray-500 cursor-not-allowed">
                                    <icon class="inline" name="trash"></icon>
                                </span>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
       </div>
    </div>
</template>

<script>
    import Vue from 'vue';

    export default {
        data() {
            return {
                endpoint: '',
                items: [],
                newitem: {
                    title: '',
                    unit: ''
                },
                pageTitleLinks: [
                    {
                        to: '/menuplan/' + this.$route.params.id,
                        caption: this.$t('menuplan.share.viewMenuplan'),
                        icon: 'tile'
                    }
                ],
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
            changeToEditMode(item, field) {
                item.editing = true;
                let vm = this;
                Vue.nextTick(function () {
                    vm.$refs[field + '-' + item.id][0].focus();
                })
            },
            tryToAddItem() {
                if (this.newitem.title != '' && this.newitem.unit != '') {
                    this.addItem();
                }
            },
            addItem() {
                axios.post(this.endpoint, this.newitem).then(response => {
                    let item = response.data;
                    item.editing = false;
                    this.items.push(item);
                    this.items = this.items.sort((a, b) => {
                        return a.title.localeCompare(b.title);
                    });
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
