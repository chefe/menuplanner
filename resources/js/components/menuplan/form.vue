<template>
    <form @submit.prevent="submit">
        <form-item caption="general.title">
            <input type="text" 
                   name="title" 
                   v-model="menuplan.title" 
                   :placeholder="$t('general.provideTitle')" 
                   class="form-control"
                   required />
        </form-item>
        <form-item caption="general.start">
            <input type="date" 
                   name="start" 
                   v-model="menuplan.start" 
                   class="form-control" 
                   required />
        </form-item>
        <form-item caption="general.end">
            <input type="date" 
                   name="end" 
                   v-model="menuplan.end" 
                   :min="menuplan.start" 
                   class="form-control" 
                   required />
        </form-item>
        <form-item caption="general.people">
            <input type="number" 
                   name="people" 
                   v-model="menuplan.people" 
                   min="1" 
                   class="form-control" 
                   required />
        </form-item>
        
        <div class="w-full sm:w-2/3 ml-auto flex sm:py-2 sm:pr-2 flex-col sm:flex-row">
            <a class="btn-secondary flex-1 sm:mr-2" @click="cancel">{{ $t('general.cancel') }}</a>
            <button type="submit" class="btn-primary flex-1">{{ $t('general.save') }}</button>
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

