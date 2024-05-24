import Vue from 'vue';

import * as VueGoogleMaps from 'vue2-google-maps';
import BranchLocator from './vue-components/BranchLocator';

import './bootstrap';

Vue.config.productionTip = false;

Vue.use(VueGoogleMaps, {
  load: {
    key: window._googleMapApiKey || 'AIzaSyD2ukfeVAiyOTsmvrUIXx2LLz7sVuqWOZo',
  },
});

Vue.component('branch-locator', BranchLocator);

(function() {
  new Vue({
    el: '#branch-locator',
    template: '<branch-locator/>',
  });
})();
