import Vue from 'vue'

import Loader from './Loader';
import LoadingCard from './LoadingCard';

import ElForm from './ElForm';
import SimpleSlider from './SimpleSliders';

// import Card from './Card';
// import Cards from './Cards';
// import CardWrapper from './CardWrapper';
// import BaseTrendMetric from './Metrics/Base/TrendMetric';
// import BaseValueMetric from './Metrics/Base/ValueMetric';
// import BasePartitionMetric from './Metrics/Base/PartitionMetric';
// import TrendMetric from './Metrics/TrendMetric';
// import ValueMetric from './Metrics/ValueMetric';
// import PartitionMetric from './Metrics/PartitionMetric';
// import Dashboard from './Dashboard'

Vue.component('loader', Loader);
Vue.component('loading-card', LoadingCard);

Vue.component('el-form', ElForm)
Vue.component('simple-slider', SimpleSlider)

// Vue.component('card', Card);
// Vue.component('card-wrapper', CardWrapper);
// Vue.component('cards', Cards);
// Vue.component('base-partition-metric', BasePartitionMetric);
// Vue.component('base-trend-metric', BaseTrendMetric);
// Vue.component('base-value-metric', BaseValueMetric);
// Vue.component('partition-metric', PartitionMetric);
// Vue.component('trend-metric', TrendMetric);
// Vue.component('value-metric', ValueMetric);
// Vue.component('Dashboard', Dashboard);
