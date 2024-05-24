<template>
    <div class="form-input-group">
        <label>{{ label }}</label>

        <div class="inners">
            <div class="form-control-group">
                <cleave
                    @input="onChange"
                    v-bind="$attrs"
                    :value="value"
                    :options="cleaveOptions"
                    :class="{'input-has-error': !!hasError}"
                    class="form-control"/>

                <span class="form-unit">đ</span>
            </div>
        </div>
    </div>
</template>

<script>
    import Cleave from 'vue-cleave-component';

    export default {
        name: 'input-money',

        inheritAttrs: false,
        props: ['label', 'value', 'hasError'],

        components: {
            Cleave,
        },

        methods: {
            onChange(value) {
                this.$emit('input', value)
            },
        },

        computed: {
            cleaveOptions: () => ({
                numeral: true,
                numeralPositiveOnly: true,
                numeralDecimalScale: 0,
                numeralDecimalMark: ',',
                delimiter: '.',
            }),
        },
    }
</script>
