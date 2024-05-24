<template>
  <div class="fund-chart">
    <vue-apex-charts :key="key" type="line" :options="options" :series="series"></vue-apex-charts>
  </div>
</template>

<script>
  import VueApexCharts from 'vue-apexcharts'

  export default {
    components: {
      VueApexCharts,
    },

    data() {
      return {
        key: 0,
        options: {
          chart: {
            toolbar: {
              show: false,
            },
          },
          xaxis: {
            type: "datetime"
          },
          yaxis: {
            opposite: true,
          }
        },
        series: [
        ]
      }
    },

    props: [
      'funds',
    ],

    created() {
      var series = [];
      this.$props.funds.forEach((item, index) => {
        let data = {};
        data['name'] = item.name;
        data.data = [];
        item.fund_costs.forEach((item, index) => {
          data.data.push({
            x: item.date,
            y: item.value,
          });
        });
        series.push(data);
      });
      series.reverse()
      this.series = series;
      this.key += 1;
    },
  }
</script>

<style scoped="">
</style>
