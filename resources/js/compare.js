import Vue from 'vue';

import './bootstrap';
import Compare from './vue-components/Compare';

(function() {
  new Vue({
    el: '#compare-products',
    components: {
      Compare,
    },
  });
})();
