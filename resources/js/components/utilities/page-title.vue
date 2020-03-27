<template>
    <div class="flex items-center mx-2 mb-8 p-2 rounded border-b-2 shadow-b text-xl bg-white">
        <h1 class="text-4xl flex-1">
            <span class="text-gray-800 mr-2" v-text="title"></span>
            <small v-if="subtitle" class="text-gray-600" v-text="subtitle"></small>
        </h1>
        <template v-for="link in links">
            <a v-if="link.href" :href="link.href" :title="link.caption" class="ml-4">
                <icon class="text-gray-800" :name="link.icon" size="8"></icon>
            </a>
            <router-link v-if="link.to" :to="link.to" :title="link.caption" class="ml-4">
                <icon class="text-gray-800" :name="link.icon" size="8"></icon>
            </router-link>
        </template>
    </div>
</template>

<script>
    export default {
        props: ['title', 'subtitle', 'links']
    }
</script>
