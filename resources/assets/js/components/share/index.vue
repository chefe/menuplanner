<template>
    <center-panel>
        <template slot="header">
            <span class="flex-1">Share Menuplan</span>
        </template>

        <div v-for="invitation in invitations" class="flex text-sm p-3 border-b items-center" :key="invitation.id">
            <span class="flex-1 text-grey-dark" v-text="invitation.email"></span>
            <a @click="deleteInvitation(invitation)" class="text-grey-dark hover:text-grey-darkest cursor-pointer">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/></svg>
            </a>
        </div>

        <div class="flex text-sm p-3 border-b items-center">
            <input v-model="email" @keydown.enter="inviteEmail" type="email" placeholder="john@example.com" class="form-control flex-1 mr-2">
            <a class="btn-primary" @click="inviteEmail">Share</a>
        </div>

        <div class="flex text-sm p-3 border-b items-center">
            <router-link :to="'/menuplan/' + menuplanId" class="flex-1 btn-secondary mr-4">
                View menuplan
            </router-link>
            <router-link :to="'/'" class="flex-1 btn-secondary">
                Back to the dashboard
            </router-link>
        </div>
    </center-panel>
</template>

<script>
    import { bus } from '../../eventbus.js';

    export default {
        data() {
            return {
                invitations: [],
                endpoint: '',
                menuplanId: 0,
                email: ''
            }
        },
        mounted() {
            this.menuplanId = this.$route.params.id;
            this.endpoint = '/api/menuplan/' + this.$route.params.id + '/invitation';
            this.fetchInvitations();
        },
        methods: {
            fetchInvitations() {
                bus.$emit('loading', true);
                let that = this;
                axios.get(this.endpoint)
                    .then(function (response) {
                        bus.$emit('loading', false);
                        that.invitations = response.data;
                    })
                    .catch(function (error) {
                        bus.$emit('error', error);
                    });
            },
            inviteEmail() {
                let data = { email: this.email };
                let that = this;
                axios.post(this.endpoint, data).then(response => {
                    that.email = '';
                    that.fetchInvitations();
                });
            },
            deleteInvitation(invitation) {
                let endpoint = '/api/invitation/' + invitation.id;
                let that = this;
                axios.delete(endpoint).then(response => {
                    that.fetchInvitations();
                });
            }
        }
    }
</script>
