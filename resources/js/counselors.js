import Vue from 'vue';

import Toasted from 'vue-toasted';
import VueFormWizard from 'vue-form-wizard';

import './bootstrap';
import Counselors from './vue-components/Counselors';

Vue.use(VueFormWizard);

Vue.use(Toasted, {
  theme: 'bubble',
  position: 'top-right',
  duration: 6000,
});

Vue.component('counselors', Counselors);

(function() {
  new Vue({
    el: '#counselors',
    template: '<counselors/>',
  });
})();
