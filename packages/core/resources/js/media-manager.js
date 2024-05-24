import Manager from './media/MediaManager';
window.Vue.component('media-manager', Manager);

(function () {
  window.mediaManager = new window.Vue({
    el: '#filemanager'
  });
})();
