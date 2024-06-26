<template>
    <BaseValueMetric
        @selected="handleRangeSelected"
        :title="card.name"
        :previous="previous"
        :value="value"
        :ranges="card.ranges"
        :format="format"
        :prefix="prefix"
        :suffix="suffix"
        :selected-range-key="selectedRangeKey"
        :loading="loading"
    />
</template>

<script>
    import {Minimum} from 'laravel-nova'
    import BaseValueMetric from './Base/ValueMetric'

    export default {
        name: 'ValueMetric',

        components: {
            BaseValueMetric,
        },

        props: {
            card: {
                type: Object,
                required: true,
            }
        },

        data: () => ({
            loading: true,
            format: '(0[.]00a)',
            value: 0,
            previous: 0,
            prefix: '',
            suffix: '',
            selectedRangeKey: null,
        }),

        created() {
            if (this.hasRanges) {
                this.selectedRangeKey = this.card.ranges[0].value
            }
        },

        mounted() {
            this.fetch(this.selectedRangeKey)
        },

        methods: {
            handleRangeSelected(key) {
                this.selectedRangeKey = key
                this.fetch()
            },

            fetch() {
                this.loading = true

                Minimum(axios.get(this.metricEndpoint, this.rangePayload)).then(
                    ({
                         data: {
                             value: {value, previous, prefix, suffix, format},
                         },
                     }) => {
                        this.value = value
                        this.format = format || this.format
                        this.prefix = prefix || this.prefix
                        this.suffix = suffix || this.suffix
                        this.previous = previous
                        this.loading = false
                    }
                )
            },
        },

        computed: {
            hasRanges() {
                return this.card.ranges.length > 0
            },

            rangePayload() {
                return this.hasRanges ? {params: {range: this.selectedRangeKey}} : {}
            },

            metricEndpoint() {
                return `/dashboard/girls/metric/?metric=${this.card.uriKey}`
            },
        },
    }
</script>
