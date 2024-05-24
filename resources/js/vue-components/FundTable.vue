<template>
  <div>
    <table class="fund-table">
        <thead>
          <tr>
            <td class="cl-2">NGÀY CÔNG BỐ GIÁ</td>
            <td v-for="(item, index) in funds" :key="index" class="fund-name cl-0">{{ item['name'] }}</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in fundCosts" :key="index">
            <td class="text-center">{{ item['date'] }}</td>
            <td class="text-center" v-for="(fund, key) in funds" :key="key">{{ item.value[key] ? item.value[key].value : 0 }}</td>
          </tr>
        </tbody>
    </table>
  </div>
</template>

<script>

  export default {

    data() {
      return {
        fundCosts: [],
      }
    },

    props: [
      'funds', 'fund_costs'
    ],

    created() {
      const fundCosts = [];
      this.$props.fund_costs.forEach((fundCost, index) => {
        const data = {
          'date': fundCost.date,
          'value': []
        };
        this.$props.funds.forEach((item, index) => {
          data.value[index] = fundCost[`fund${item.id}`];
        });
        fundCosts.push(data);
      });
      this.fundCosts = fundCosts;
    },
  }
</script>

<style scoped="">
  .fund-table tr {
    border-bottom: 1px solid #EBEEF5;
  }
  .fund-name {
    text-transform: uppercase;
  }
  .cl-0 {
    color: #2a79c0;
  }
  .cl-2 {
    color: #8e8e8e;
  }
  .text-center {
    text-align: center;
  }
  .fund-table th, .fund-table td {
    padding: 20px;
  }
</style>
