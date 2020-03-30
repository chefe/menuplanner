<template>
    <modal ref="modal">
        <div class="bg-white sm:rounded sm:shadow p-8">
            <button
                class="bg-green-400 w-full sm:w-auto p-4 sm:p-8 mb-4 sm:mb-0 sm:mr-4 rounded text-grenn-800 text-center cursor-pointer"
                v-text="$t('meal.create.createMeal')"
                @click="emitAndHide('addMeal')"/>
            <button
                class="bg-green-400 w-full sm:w-auto p-4 sm:p-8 mt-4 sm:mt-0 sm:ml-4 rounded text-grenn-800 text-center cursor-pointer"
                v-text="$t('purchase.add.addPurchase')"
                @click="emitAndHide('addPurchase')"/>
        </div>
    </modal>
</template>

<script>
    export default {
        data() {
            return {}
        },
        methods: {
            show() {
                this.$refs.modal.show();
            },
            hide() {
                this.$refs.modal.hide();
            },
            emitAndHide(signal) {
                this.hide();
                this.$emit(signal);
            }
        }
    }
</script>
