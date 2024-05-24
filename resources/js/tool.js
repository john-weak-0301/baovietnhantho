import Vue from 'vue';
import Toasted from 'vue-toasted';
import Transitions from 'vue2-transitions';

import './bootstrap';
import Tools from './vue-components/Tools';
import PlanningForm from './vue-components/Tools/PlanningForm';
import Select2 from './vue-components/Select2';

Vue.config.productionTip = false;

Vue.use(Transitions);

Vue.use(Toasted, {
  theme: 'bubble',
  position: 'top-right',
  duration: 6000,
});

Vue.component('tools', Tools);
Vue.component('planning-form', PlanningForm);
Vue.component('select2', Select2);

// Share the Vue
window.Vue = Vue;

(function() {
  if (document.getElementById('tool-root')) {
    window.toolApp = new Vue({
      el: '#tool-root',
      template: '<tools/>',
    });
  }
})();
