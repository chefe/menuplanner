<template>
    <form @submit.prevent="submit">
        <form-item caption="Title">
            <input type="text" 
                    name="title" 
                    v-model="menuplan.title" 
                    placeholder="Please provide a title" 
                    class="form-control"
                    required />
        </form-item>
        <form-item caption="Start">
            <input type="date" name="start" v-model="menuplan.start" class="form-control" required />
        </form-item>
        <form-item caption="End">
            <input type="date" name="end" v-model="menuplan.end" class="form-control" required />
        </form-item>
        <form-item caption="People">
            <input type="number" name="people" v-model="menuplan.people" min="1" class="form-control" required />
        </form-item>
        
        <div class="w-full sm:w-2/3 ml-auto flex sm:py-2 sm:pr-2 flex-col sm:flex-row">
            <a class="btn-secondary flex-1 sm:mr-2" @click="cancel">Cancel</a>
            <button type="submit" class="btn-primary flex-1">Save</button>
        </div>
    </form>
</template>

<script>
    export default {
        props: ['menuplan'],
        methods: {
            submit() {
                this.$emit('submit');
            },
            cancel() {
                this.$emit('cancel');
            }
        }
    }
</script>

