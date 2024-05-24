window._ = require('underscore');
window.Backbone = require('backbone');
window.moxie = require('plupload/js/moxie.min.js');
window.plupload = require('plupload/js/plupload.min.js');

require('./wp-media/global');
require('./wp-media/wp-util');
require('./wp-media/wp-backbone');
require('./wp-media/media-models');
require('./wp-media/media-views');
require('./wp-media/wp-plupload')

;(function() {
  function resolveUrl(action, id) {
    var method = 'POST';

    var url = '/dashboard/resource/media';
    if (id) {
      url = `/dashboard/resource/media/${id}`;
    }

    switch (action) {
      case 'query-attachments':
        method = 'GET';
        break;
      case 'get-attachment':
        method = 'GET';
        break;
      case 'delete-post':
        method = 'DELETE';
        break;
      case 'save-attachment':
        method = 'PUT';
        break;
    }

    return [method, url];
  }

  function ajaxRequest(action, options) {
    if (_.isObject(action)) {
      options = action;
      action = options.data.action;
    } else {
      options = options || {};
    }

    var _data = resolveUrl(action, options.data.id);

    options.url = _data[1];
    options.method = _data[0];
    options.data._method = _data[0];
    console.log(options);

    return wp.ajax.send(options);
  }

  wp.media.ajax = function(action, options) {
    return ajaxRequest(action, options);
  };

  wp.media.post = function(action, data) {
    return ajaxRequest(action, {data: data});
  };
})();
