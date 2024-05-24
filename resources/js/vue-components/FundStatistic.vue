<template>
  <div class="fund-statistic">
    <div class="search-form-inner">
      <div class="card search-form-inner-card">
        <div class="card-header">
          <div>TRA CỨU ĐƠN VỊ QUỸ</div>
        </div>
        <div class="card-body">
          <div class="row m-0">
            <div class="col col-3">
              <div class="cl-0">Tất cả quỹ</div>
              <div>
                <select v-model="form.fund_id" class="c-select" placeholder="Select">
                  <option
                    :key="0"
                    label="Tất cả quỹ"
                    :value="0">
                  </option>
                  <option
                    v-for="(item, index) in fundSelect"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id">
                  </option>
                </select>
              </div>
            </div>
            <div class="col col-3">
              <div class="cl-0">Từ ngày</div>
              <div>
                <input type="date" class="c-input" v-model="form.from_date" placeholder="Chọn ngày" />
              </div>
            </div>
            <div class="col col-3">
              <div class="cl-0">Đến ngày</div>
              <div>
                <input type="date" class="c-input" v-model="form.to_date" placeholder="Chọn ngày" />
              </div>
            </div>
            <div class="col col-3">
              <div style="color: #fff;">action</div>
              <div class="action-block">
                <button type="button" class="btn btn-warning c-btn search-btn" @click="search">Tra cứu</button>
                <button type="button" class="btn refresh-btn c-btn" @click="resetForm">
                  <i class="fa fa-refresh" aria-hidden="true"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="statistic-card">
      <div class="card">
        <div class="clearfix card-header">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span class="cl-0">Kết quả tra cứu (đồng)</span>
            <div>
              <span class="cl-2">Dạng hiển thị: </span>
              <button size="mini" :class="['btn', 'btn-type', show == 'chart' ? 'btn-primary' : 'btn-outline']" @click="show = 'chart'">
                <i class="fa fa-line-chart" aria-hidden="true"></i>
              </button>
              <button size="mini" :class="['btn', 'btn-type', show == 'table' ? 'btn-primary' : 'btn-outline']" @click="show = 'table'">
                <i class="fa fa-bars" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>
        <fund-chart :key="key" v-show="show == 'chart'" :funds='fundList'></fund-chart>
        <fund-table :key="key" v-show="show == 'table'" :fund_costs='fundCostList.data' :funds='fundList'></fund-table>
        <paginate
          :page-count="pageCount"
          :page-range="3"
          prev-text="<i class='fa fa-angle-left' aria-hidden='true'></i>"
          next-text="<i class='fa fa-angle-right' aria-hidden='true'></i>"
          v-show="show == 'table'"
          class="pagination"
          :click-handler="changePage">
        </paginate>
      </div>
    </div>
  </div>
</template>

<script>
  import Axios from 'axios';
  import moment from 'moment';
  import FundChart from './FundChart.vue';
  import FundTable from './FundTable.vue';
  import Paginate from 'vuejs-paginate'
  export default {
    components: {
      FundChart, FundTable, Paginate
    },
    data() {
      return {
        show: 'chart',
        form: {
          fund_id: 0,
          from_date: '',
          to_date: '',
          page: 1,
        },
        fundSelect: [],
        total: 1,
        pageCount: 1,
        fundList: [],
        fundCostList: [],
        key: 0,
      }
    },

    props: [
      'funds', 'fund_costs'
    ],

    created() {
      this.fundList = this.$props.funds;
      this.fundCostList = this.$props.fund_costs;
      this.form.page = this.$props.fund_costs.current_page;
      this.pageCount = this.$props.fund_costs.last_page;
      const fundSelect = [];
      this.$props.funds.forEach((item, index) => {
        fundSelect.push({
          id: item.id,
          name: item.name,
        });
      });
      this.fundSelect = fundSelect;
    },

    methods: {
      changePage(page) {
        this.form.page = page;
        this.submit();
      },
      search() {
        this.submit();
      },
      resetForm() {
        this.form = {
          fund_id: 0,
          from_date: '',
          to_date: '',
          page: 1,
        };
        this.submit();
      },
      async submit() {
        const params = this.form;
        if (params.from_date) {
          params.from_date = moment(params.from_date).format('YYYY-MM-DD');
        }
        if (params.to_date) {
          params.to_date = moment(params.to_date).format('YYYY-MM-DD');
        }
        const response = await Axios.get('/search-fund-costs', { params });
        if (response.status == 200) {
          const data = response.data;
          this.fundList = data.funds;
          this.fundList = !this.form.fund_id ? this.fundList : this.fundList.filter(({ id }) => id == this.form.fund_id);
          this.fundCostList = data.fund_costs;
          this.pageCount = data.fund_costs.last_page;
        }
        this.key += 1;
      }
    }
  }
</script>

<style scoped="true">
  .card-header {
    color: #2a79c0;
    text-align: center;
    font-weight: 700;
  }
  .m-0 {
    margin: 0 !important;
  }
  .cl-0 {
    color: #2a79c0;
  }
  .cl-2 {
    color: #8e8e8e;
  }
  .action-block {
    display: flex;
    height: 40px;
    align-items: center;
    justify-content: center;
  }
  .search-form-inner {
    display: block;
    position: relative;
    width: 100%;
    height: 130px;
  }
  .search-form-inner-card {
    width: 80%;
    position: absolute;
    top: -60px;
    z-index: 100;
    left: 10%;
  }
  .statistic-card {
    width: 80%;
    margin: 0px auto 20px;
  }
  .statistic-card >>> .el-card__body {
    padding: 20px 0 !important;
  }
  .pagination {
    display: flex;
    justify-content: center;
    margin: 20px 0 20px;
  }
  .pagination.is-background >>> .el-pager li {
    border-radius: 50% !important;
  }
  .pagination.is-background >>> .el-pager li:not(.disabled).active {
    background-color: #E6A23C !important;
  }
  .pagination.is-background >>> .el-pager li:not(.disabled):hover {
    color: #E6A23C !important;
  }
  .c-select {
    box-shadow: none;
    outline: none;
    margin: 0;
    border-radius: 4px;
    padding: 0 25px;
    line-height: 40px;
    border: 0;
    color: #000;
    font-size: 15px;
    vertical-align: middle;
    font-family: Avenir Next M;
    height: 40px;
    background-color: #f3f3f3;
    width: 100%;
  }

  .c-btn {
    display: inline-block;
    line-height: 1;
    white-space: nowrap;
    cursor: pointer;
    border: 1px solid #dcdfe6;
    color: #606266;
    -webkit-appearance: none;
    text-align: center;
    box-sizing: border-box;
    outline: none;
    margin: 0;
    transition: .1s;
    font-weight: 500;
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
    padding: 12px 20px;
    font-size: 14px;
    border-radius: 4px;
  }
  .refresh-btn {
    padding: 12px;
    border-radius: 50%;
    border: 1px solid #ebeef5;
    background-color: #fff;
    overflow: hidden;
    color: #303133;
    transition: .3s;
    box-shadow: 0 2px 12px 0 rgb(0 0 0 / 10%);
    margin-left: 10px;
  }
  .search-btn {
    border-radius: 20px;
    padding: 12px 23px;
    color: #fff;
  }
  .c-input {
    height: 40px;
    line-height: 40px;
  }
  .card {
    box-shadow: 0 2px 12px 0 rgb(0 0 0 / 10%);
    border: 1px solid #ebeef5;
  }
  .card-header {
    padding: 1.25rem calc(1.25rem + 15px);
    background-color: #fff;
  }
  .btn-outline {
    border: 1px solid #dcdfe6;
    background: #fff;
  }
  .btn-outline:hover {
    color: #409eff;
    border-color: #c6e2ff;
    background-color: #ecf5ff;
  }
  .statistic-card .card-header {
    font-weight: unset;
  }
  ul.pagination >>> li {
    vertical-align: top;
    display: inline-block;
    font-size: 13px;
    min-width: 35.5px;
    height: 28px;
    line-height: 28px;
    cursor: pointer;
    box-sizing: border-box;
    text-align: center;
    margin: 0 5px;
    background-color: #f4f4f5;
    color: #606266;
    min-width: 30px;
    border-radius: 50%;
  }

  ul.pagination >>> li a {
    display: block;
    width: 100%;
    height: 100%;
    font-size: 13px;
    color: #606266;
    cursor: pointer;
  }

  ul.pagination >>> li.active {
    background-color: #ffb900;
    cursor: default;
  }

  ul.pagination >>> li:hover a {
    color: #409eff;
  }

  ul.pagination >>> li.active a {
    color: #fff;
  }

  .btn-type {
    border-radius: 2px;
    padding: 0.375rem 0;
  }
</style>
