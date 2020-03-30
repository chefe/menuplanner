<template>
    <modal ref="modal">
        <center-panel>
            <template slot="header">{{ $t('meal.create.createMeal') }}</template>
            <form @submit.prevent="submit">
                <form-item caption="general.title">
                    <input type="text"
                           name="title"
                           v-model="title"
                           class="form-control"
                           :placeholder="$t('general.provideTitle')"
                           required />
                </form-item>
                <form-item caption="general.start">
                    <input type="time"
                           name="start"
                           v-model="start"
                           class="form-control"
                           required />
                </form-item>
                <form-item caption="general.end">
                    <input type="time"
                           name="end"
                           v-model="end"
                           class="form-control"
                           required />
                </form-item>
                <div class="p-2 flex items-center">
                    <span class="block h-1 my-1 bg-gray-200 w-full rounded" />
                    <small class="block mx-2 text-gray-600" v-text="$t('general.optional')" />
                    <span class="block h-1 my-1 bg-gray-200 w-full rounded" />
                </div>
                <form-item caption="general.people">
                    <input type="number"
                           name="people"
                           v-model="people"
                           min="1"
                           class="form-control"
                           :placeholder="menuplan.people" />
                </form-item>
                <div class="w-full sm:w-2/3 ml-auto flex sm:py-2 sm:pr-2 flex-col sm:flex-row">
                    <a class="btn-secondary flex-1 sm:mr-2" @click="hide">{{ $t('general.cancel') }}</a>
                    <button type="submit" class="btn-primary flex-1">{{ $t('general.save') }}</button>
                </div>
            </form>
        </center-panel>
    </modal>
</template>

<script>
    export default {
        props: ['date', 'menuplan'],
        data() {
            return {
                title: '',
                start: '',
                end: '',
                people: '',
            }
        },
        methods: {
            show() {
                this.$refs.modal.show();
            },
            hide() {
                this.$refs.modal.hide();
            },
            submit() {
                let endpoint = '/api/menuplan/' + this.menuplan.id + '/meals';

                let newMeal = {
                    title: this.title,
                    date: this.date,
                    start: this.start,
                    end: this.end,
                    people: this.people,
                };

                axios.post(endpoint, newMeal).then(response => {
                    let meal = response.data;
                    this.$emit('mealAdded', meal);
                    this.hide();
                });
            },
        }
    }
</script>
