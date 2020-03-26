<template>
    <center-panel>
        <template slot="header">
            <span class="flex-1">{{ $t('menuplan.share.shareMenuplan') }}</span>
        </template>

        <div v-for="invitation in invitations" class="flex text-sm p-3 border-b items-center" :key="invitation.id">
            <div class="flex-1 text-gray-600">
                <span v-text="invitation.email"></span>
                <small class="p-1 rounded bg-gray-600 text-white" v-text="getStatus(invitation)"></small>
            </div>
            <a @click="deleteInvitation(invitation)" class="text-gray-600 hover:text-gray-800 cursor-pointer">
                <icon name="trash"></icon>
            </a>
        </div>

        <div class="flex text-sm p-3 border-b items-center">
            <input v-model="email"
                   @keydown.enter="inviteEmail"
                   type="email"
                   placeholder="john@example.com"
                   class="form-control flex-1 mr-2">
            <a class="btn-primary" @click="inviteEmail">{{ $t('menuplan.share.share') }}</a>
        </div>

        <div class="flex text-sm p-3 border-b items-center">
            <router-link :to="'/menuplan/' + menuplanId" class="flex-1 btn-secondary mr-4">
                {{ $t('menuplan.share.viewMenuplan') }}
            </router-link>
            <router-link :to="'/'" class="flex-1 btn-secondary">
                {{ $t('menuplan.share.backToDashboard') }}
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
            },
            getStatus(invitation) {
                return invitation.user_id == null ?
                    this.$t('menuplan.share.pending') :
                    this.$t('menuplan.share.accepted');
            }
        }
    }
</script>
