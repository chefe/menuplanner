<template>
    <details>
        <summary class="btn-danger" style="display:block">
            {{ $t('general.delete') }}
        </summary>

        <div class="fixed pin bg-black opacity-50"></div>
        <div class="fixed pin" @click="closeIfClickOutside">
            <div class="min-h-screen flex items-center justify-center">
                <center-panel ref="modal" :is-critical-action="true">
                    <template slot="header">{{ $t('general.confirmTitle') }}</template>

                    <p class="p-2 pt-4">{{ $t('general.confirmMessage') }}<p>

                    <div class="flex p-2">
                        <a
                            @click="close"
                            class="btn-secondary flex-1 mr-1">
                            {{ $t('general.cancel') }}
                        </a>
                        <button
                            type="submit"
                            @click="confirmDelete"
                            class="btn-danger flex-1 ml-1">
                            {{ $t('general.delete') }}
                        </button>
                    </div>
                </center-panel>
            </div>
        </div>
    </details>
</template>

<script>
    export default {
        props: ['deleteCallback'],
        methods: {
            closeIfClickOutside(e) {
                if (false == this.$refs.modal.$el.contains(e.target)) {
                    this.close(e);
                }
            },
            close(e) {
                this.$el.attributes.removeNamedItem('open');
            },
            confirmDelete() {
                if (this.deleteCallback) {
                    this.deleteCallback();
                }

                this.close();
            }
        }
    }
</script>

<style scoped>
    details > summary {
        list-style: none;
    }
    details > summary::-webkit-details-marker {
        display: none;
    }
</style>
