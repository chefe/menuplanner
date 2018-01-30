<template>
    <div>
        <div class="flex items-center mb-8">
            <div class="w-full mx-auto sm:w-3/4 lg:w-1/2">
                <div class="shadow rounded">
                    <div class="flex items-center font-medium text-lg bg-green-light p-3 rounded-t">
                        <span>Create Menuplan</span>
                    </div>
                    <div class="bg-white rounded-b">
                        <form @submit.prevent="save" action="POST">
                            <div class="flex items-center p-2 flex-row">
                                <label class="w-1/3 mb-0 pr-4 text-right" for="title">Title:</label>
                                <input class="w-2/3" type="text" name="title" v-model="title" placeholder="Please provide a title" required />
                            </div>
                            <div class="flex items-center p-2 flex-row">
                                <label class="w-1/3 mb-0 pr-4 text-right" for="start" >Start:</label>
                                <input class="w-2/3" type="date" name="start" v-model="start" required />
                            </div>
                            <div class="flex items-center p-2 flex-row">
                                <label class="w-1/3 mb-0 pr-4 text-right" for="end">End:</label>
                                <input class="w-2/3" type="date" name="end" v-model="end" required />
                            </div>
                            <div class="flex items-center p-2 flex-row">
                                <label class="w-1/3 mb-0 pr-4 text-right" for="people">People:</label>
                                <input class="w-2/3" type="number" name="people" v-model="people" min="1" required />
                            </div>
                            <div class="w-2/3 ml-auto flex py-2 flex-row">
                                <a class="btn-secondary flex-1" @click="cancel">
                                    Cancel
                                </a>
                                <button type="submit" class="btn-primary flex-1">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from '../../eventbus.js';

    export default {
        data() {
            return {
                title: '',
                start: '',
                end: '',
                people: 1
            }
        },
        mounted() {
        },
        methods: {
            save() {
                let that = this;
                axios.post('/api/menuplan', {
                    title: that.title,
                    start: that.start,
                    end: that.end,
                    people: that.people
                })
                .then(function (response) {
                    router.push('/');
                })
                .catch(function (error) {
                    bus.$emit('error', error);
                });
            },
            cancel() {
                router.go(-1);
            }
        }
    }
</script>