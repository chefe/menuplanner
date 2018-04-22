<template>
    <div>
        <center-panel v-if="openInvitations.length > 0">
            <template slot="header">{{ $t('dashboard.invitations') }}</template>

            <div v-for="invitation in openInvitations" class="flex text-sm p-3 border-b items-center" :key="invitation.id">
                <div class="flex-1 text-grey-dark">
                    <span class="mr-2" v-text="invitation.menuplan.title"></span>
                    <small class="text-grey" v-text="getDuration(invitation.menuplan)"></small>
                </div>
                <span class="btn-primary-small mr-2" @click="acceptInvitation(invitation)">
                    {{ $t('dashboard.accept')}}
                </span>
                <span class="btn-danger-small" @click="declineInvitation(invitation)">
                    {{ $t('dashboard.decline')}}
                </span>
            </div>
        </center-panel>

        <center-panel>
            <template slot="header">
                <span class="flex-1">{{ $t('dashboard.menuplans') }}</span>
                <router-link to="/menuplan/create" class="text-black hover:text-grey-darker no-underline">
                    <icon name="add"></icon>
                </router-link>
            </template>

            <div v-for="menuplan in ownMenuplans" class="flex text-sm p-3 border-b items-center" :key="menuplan.id">
                <router-link :to="'/menuplan/' + menuplan.id" class="flex-1 text-grey-dark hover:text-grey-darkest no-underline">
                    <span class="mr-2" v-text="menuplan.title"></span>
                    <small class="text-grey" v-text="getDuration(menuplan)"></small>
                </router-link>
                <router-link :to="'/menuplan/' + menuplan.id + '/edit'" class="text-grey-dark hover:text-grey-darkest mr-3">
                    <icon name="cog"></icon>
                </router-link>
                <router-link :to="'/menuplan/' + menuplan.id + '/share'" class="text-grey-dark hover:text-grey-darkest">
                    <icon name="share"></icon>
                </router-link>
            </div>

            <div v-if="menuplans.length == 0" class="flex text-sm p-3 border-b items-center">
                <router-link to="/menuplan/create" class="flex-1 text-grey-dark hover:text-grey-darkest no-underline">
                    {{ $t('dashboard.createFirstMenuplan') }}
                </router-link>
            </div>
        </center-panel>

        <center-panel v-if="sharedMenuplans.length > 0">
            <template slot="header">
                <span class="flex-1">{{ $t('dashboard.sharedMenuplans') }}</span>
            </template>

            <div v-for="menuplan in sharedMenuplans" class="flex text-sm p-3 border-b items-center" :key="menuplan.id">
                <router-link :to="'/menuplan/' + menuplan.id" class="flex-1 text-grey-dark hover:text-grey-darkest no-underline">
                    <span class="mr-2" v-text="menuplan.title"></span>
                    <small class="text-grey" v-text="getDuration(menuplan)"></small>
                </router-link>
                <span :title="$t('dashboard.leave')" 
                      @click="leaveMenuplan(menuplan)" 
                      class="text-grey-dark hover:text-grey-darkest cursor-pointer">
                    <icon name="trash"></icon>
                </span>
            </div>
        </center-panel>
    </div>
</template>

<script>
    import { bus } from '../eventbus.js';
    import moment from 'moment';

    export default {
        data() {
            return {
                menuplans: [],
                invitations: []
            }
        },
        mounted() {
            moment.locale(this.$i18n.locale);
            this.fetchMenuplans();
            this.fetchInvitations();
        },
        watch: {
            '$i18n.locale': val => moment.locale(val)
        },
        computed: {
            ownMenuplans() {
                return this.menuplans.filter(m => m.is_shared == false);
            },
            sharedMenuplans() {
                return this.menuplans.filter(m => m.is_shared == true);
            },
            openInvitations() {
                return this.invitations.filter(i => i.user_id == null);
            }
        },
        methods: {
            fetchMenuplans() {
                bus.$emit('loading', true);
                let that = this;
                axios.get('api/menuplan')
                    .then(function (response) {
                        bus.$emit('loading', false);
                        that.menuplans = response.data;
                    })
                    .catch(function (error) {
                        bus.$emit('error', error);
                    });
            },
            fetchInvitations() {
                bus.$emit('loading', true);
                let that = this;
                axios.get('api/invitation')
                    .then(function (response) {
                        bus.$emit('loading', false);
                        that.invitations = response.data;
                    })
                    .catch(function (error) {
                        bus.$emit('error', error);
                    });
            },
            acceptInvitation(invitation) {
                let that = this;
                axios.post('api/invitation/' + invitation.id + '/accept')
                    .then(function (response) {
                        that.fetchInASecond();
                    })
            },
            declineInvitation(invitation) {
                let that = this;
                axios.delete('api/invitation/' + invitation.id + '/decline')
                    .then(function (response) {
                        that.fetchInASecond();
                    })
            },
            leaveMenuplan(menuplan) {
                let that = this;
                let invitation = this.invitations.filter(i => i.menuplan_id == menuplan.id);
                if (invitation.length > 0) {
                    axios.delete('api/invitation/' + invitation[0].id + '/decline')
                        .then(function (response) {
                            that.fetchInASecond();
                        })
                }
            }, 
            fetchInASecond() {
                let that = this;
                setTimeout(function() {
                    that.fetchInvitations();
                    that.fetchMenuplans();
                }, 1000);
            },
            getDuration(menuplan) {
                return moment(menuplan.start).format('Do MMM') + 
                    ' - ' + moment(menuplan.end).format('Do MMM');
            }
        }
    }
</script>