<template>
    <div></div>
</template>

<script>
    import Quill from 'quill';

    export default {
        props: ['value', 'placeholder'],
        data() {
            return {
                quill: undefined,
            }
        },
        mounted() {
            let options = {
                placeholder: this.placeholder || '',
                theme: 'snow'
            };

            let vm = this;
            this.quill = new Quill(this.$el, options);
            this.quill.on('text-change', function(delta, oldDelta, source) {
                vm.debounceInput();
            });
        },
        watch: {
            value(newVal, oldVal) {
                if (newVal != this.quill.root.innerHTML) {
                    this.quill.pasteHTML(newVal);
                }
            }
        },
        methods: {
            debounceInput: _.debounce(function () {
                this.$emit('input', this.quill.root.innerHTML);
            }, 500),
        }
    }
</script>

