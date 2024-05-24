<template>
    <BaseTrendMetric
        @selected="handleRangeSelected"
        :title="card.name"
        :value="value"
        :chart-data="data"
        :ranges="card.ranges"
        :format="format"
        :prefix="prefix"
        :suffix="suffix"
        :selected-range-key="selectedRangeKey"
        :loading="loading"
    />
</template>

<script>
    import _ from 'lodash'
    import {Minimum} from 'laravel-nova'
    import BaseTrendMetric from './Base/TrendMetric'

    export default {
        name: 'TrendMetric',

        components: {
            BaseTrendMetric,
        },

        props: {
            card: {
                type: Object,
                required: true,
            },
        },

        data: () => ({
            loading: true,
            value: '',
            data: [],
            format: '(0[.]00a)',
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
            this.fetch()
        },

        methods: {
            handleRangeSelected(key) {
                this.selectedRangeKey = key
                this.fetch()
            },

            fetch() {
                this.loading = true

                Minimum(axios.get(this.metricEndpoint, this.metricPayload)).then(
                    ({
                         data: {
                             value: {labels, trend, value, prefix, suffix, format},
                         },
                     }) => {
                        this.value = value
                        this.labels = Object.keys(trend)
                        this.data = {
                            labels: Object.keys(trend),
                            series: [
                                _.map(trend, (value, label) => {
                                    return {
                                        meta: label,
                                        value: value,
                                    }
                                }),
                            ],
                        }
                        this.format = format || this.format
                        this.prefix = prefix || this.prefix
                        this.suffix = suffix || this.suffix
                        this.loading = false
                    }
                )
            },
        },

        computed: {
            hasRanges() {
                return this.card.ranges.length > 0
            },

            metricPayload() {
                const payload = {
                    params: {
                        timezone: this.userTimezone,
                        twelveHourTime: this.usesTwelveHourTime,
                    },
                }

                if (this.hasRanges) {
                    payload.params.range = this.selectedRangeKey
                }

                return payload
            },

            metricEndpoint() {
                return `/dashboard/girls/metric/?metric=${this.card.uriKey}`
            },
        },
    }
</script>

<style>
    /*Trend Metric*/
    .full {
        top: 20%;
    }

    .half {
        top: 60%;
    }

    .ct-series-a .ct-bar,
    .ct-series-a .ct-line,
    .ct-series-a .ct-point {
        stroke: rgba(64, 153, 222, .7) !important;
        stroke-width: 2px;
    }

    .ct-series-a .ct-area,
    .ct-series-a .ct-slice-pie {
        fill: rgba(64, 153, 222, .5) !important;
    }

    .ct-point {
        stroke: #4099de !important;
        stroke-width: 6px !important;
    }
</style>
