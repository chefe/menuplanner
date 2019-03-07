<template>
    <center-panel>
        <div class="flex flex-col items-center justify-center h-64">
            <span class="text-5xl text-grey-darker">{{ $t('notfound.pageNotFound') }}</span>
            
            <button class="btn btn-primary text-xl mt-4" @click="back">{{ $t('notfound.back') }}</button>
        </div>
    </center-panel>
</template>

<script>
    export default {
        methods: {
            back() {
                router.go(-1);
            }
        }
    }
</script>