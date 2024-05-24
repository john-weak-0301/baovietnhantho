import Vue from 'vue';
import jQuery from 'jquery';
import Selectize from 'selectize';
import _ from 'lodash';
import * as i18n from '@wordpress/i18n';

import {
  Tabs,
  TabPane,
  Input,
  FormItem,
  Upload,
} from 'element-ui';

import './bootstrap';
import './components';

// Register the Vue mixins.
window.$ = jQuery;
window.Vue = Vue;
window.i18n = i18n;
window.Selectize = Selectize;
window.lodash = _.noConflict();
window.__ = i18n.__;

Vue.use(Tabs);
Vue.use(TabPane);
Vue.use(FormItem);
Vue.use(Input);
Vue.use(Upload);

Vue.mixin({
  methods: {
    __: i18n.__,
  },
});

function initializeHelpers() {
  if (window.dashboardInitialized) {
    return;
  }

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
    },
  });

  $('[data-confirm]').on('click', (e) => {
    let message = $(e.currentTarget).data('confirm') ||
      __('Bạn có chắc muốn thực hiện hành động này?');
    return window.confirm(message);
  });

  window.dashboardInitialized = true;
}

function initializeVue() {
  if (!document.getElementById('vue-instance')) {
    return;
  }

  window.dashboardVue = new Vue({
    el: '#vue-instance',

    mounted() {
      initializeHelpers();
    },
  });

  window.dashboardVue.initializeCount = 0;
}

function reInitializeVue() {
  if (window.dashboardVue) {
    let count = window.dashboardVue.initializeCount;

    window.dashboardVue.$destroy();
    initializeVue();

    window.dashboardVue.initializeCount = ++count;
  }
}

document.addEventListener('turbolinks:load', function() {
  if (!window.dashboardVue) {
    initializeVue();
  } else {
    reInitializeVue();
  }
});
