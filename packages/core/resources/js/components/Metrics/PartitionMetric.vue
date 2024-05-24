<template>
    <BasePartitionMetric :title="card.name" :chart-data="chartData" :loading="loading" />
</template>

<script>
import { Minimum } from 'laravel-nova'
import BasePartitionMetric from './Base/PartitionMetric'

export default {
    components: {
        BasePartitionMetric,
    },

    props: {
        card: {
            type: Object,
            required: true,
        },
        resourceName: {
            type: String,
            default: '',
        },
        resourceId: {
            type: [Number, String],
            default: '',
        },

        lens: {
            type: String,
            default: '',
        },
    },

    data: () => ({
        loading: true,
        chartData: [],
    }),

    created() {
        this.fetch()
    },

    methods: {
        fetch() {
            this.loading = true

            Minimum(Nova.request(this.metricEndpoint)).then(({ data: { value: { value } } }) => {
                this.chartData = value
                this.loading = false
            })
        },
    },
    computed: {
        metricEndpoint() {
            const lens = this.lens !== '' ? `/lens/${this.lens}` : ''
            if (this.resourceName && this.resourceId) {
                return `/nova-api/${this.resourceName}${lens}/${this.resourceId}/metrics/${
                    this.card.uriKey
                }`
            } else if (this.resourceName) {
                return `/nova-api/${this.resourceName}${lens}/metrics/${this.card.uriKey}`
            } else {
                return `/nova-api/metrics/${this.card.uriKey}`
            }
        },
    },
}
</script>

<style>
    /* Partition Metric */
    .ct-series-a .ct-area,
    .ct-series-a .ct-slice-donut-solid,
    .ct-series-a .ct-slice-pie {
        fill: #f5573b !important;
    }
    .ct-series-b .ct-area,
    .ct-series-b .ct-slice-donut-solid,
    .ct-series-b .ct-slice-pie {
        fill: #f99037 !important;
    }
    .ct-series-c .ct-area,
    .ct-series-c .ct-slice-donut-solid,
    .ct-series-c .ct-slice-pie {
        fill: #f2cb22 !important;
    }
    .ct-series-d .ct-area,
    .ct-series-d .ct-slice-donut-solid,
    .ct-series-d .ct-slice-pie {
        fill: #8fc15d !important;
    }
    .ct-series-e .ct-area,
    .ct-series-e .ct-slice-donut-solid,
    .ct-series-e .ct-slice-pie {
        fill: #098f56 !important;
    }
    .ct-series-f .ct-area,
    .ct-series-f .ct-slice-donut-solid,
    .ct-series-f .ct-slice-pie {
        fill: #47c1bf !important;
    }
    .ct-series-g .ct-area,
    .ct-series-g .ct-slice-donut-solid,
    .ct-series-g .ct-slice-pie {
        fill: #1693eb !important;
    }
    .ct-series-h .ct-area,
    .ct-series-h .ct-slice-donut-solid,
    .ct-series-h .ct-slice-pie {
        fill: #6474d7 !important;
    }
    .ct-series-i .ct-area,
    .ct-series-i .ct-slice-donut-solid,
    .ct-series-i .ct-slice-pie {
        fill: #9c6ade !important;
    }
    .ct-series-j .ct-area,
    .ct-series-j .ct-slice-donut-solid,
    .ct-series-j .ct-slice-pie {
        fill: #e471de !important;
    }
</style>
