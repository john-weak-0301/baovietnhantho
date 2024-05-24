import Vue from 'vue';
import './bootstrap';
Vue.config.productionTip = false;
Vue.component('fund-statistic', require('./vue-components/FundStatistic.vue').default);

(function() {
  new Vue({
    el: '#funds',
    // template: '<fund-statistic/>',
  });
})();
