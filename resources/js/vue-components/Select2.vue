<template>
    <select>
        <slot></slot>
    </select>
</template>

<script>
    export default {
        name: 'select2',

        props: ['options', 'value'],

        mounted: function() {
            const vm = this;

            const options = {
                theme: 'select',
                data: this.options,
                minimumResultsForSearch: Infinity,
            };

            $(this.$el).select2(options).val(this.value).trigger('change').on('change', function() {
                vm.$emit('input', this.value)
            })
        },

        watch: {
            value: function(value) {
                $(this.$el).val(value).trigger('change')
            },

            options: function(options) {
                $(this.$el).empty().select2({data: options})
            },
        },

        destroyed: function() {
            $(this.$el).off().select2('destroy')
        },
    }
</script>
