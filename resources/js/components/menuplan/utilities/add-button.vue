<template>
    <div>
        <a
            @click="$refs.addModal.show()"
            class="block px-2 py-3 cursor-pointer flex items-center hover:bg-gray-200 rounded">
            <icon class="text-gray-500" name="add" />
            <span class="text-gray-500 ml-2" v-text="$t('general.add')" />
        </a>

        <add-modal
            ref="addModal"
            @addMeal="$refs.addMealModal.show()"
            @addPurchase="$refs.addPurchaseModal.show()" />

        <add-meal-modal
            ref="addMealModal"
            :date="date.format('YYYY-MM-DD')"
            :menuplan="menuplan"
            @mealAdded="mealAdded">
        </add-meal-modal>

        <add-purchase-modal
            ref="addPurchaseModal"
            :date="date.format('YYYY-MM-DD')"
            :menuplanId="menuplan.id"
            @purchaseAdded="purchaseAdded">
        </add-purchase-modal>
    </div>
</template>

<script>
    import AddPurchaseModal from './add-purchase-modal.vue'
    import AddMealModal from './add-meal-modal.vue'
    import AddModal from './add-modal.vue'

    export default {
        props: ['date', 'menuplan'],
        components: {
            AddPurchaseModal,
            AddMealModal,
            AddModal,
        },
        methods: {
            purchaseAdded(purchase) {
                this.$emit('purchaseAdded', purchase);
            },
            mealAdded(meal) {
                this.$emit('mealAdded', meal);
            }
        }
    }
</script>
