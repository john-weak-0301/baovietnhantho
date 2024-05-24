import $ from 'jquery';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { QueryClient, QueryClientProvider } from 'react-query';
import { initializeIcons } from '@fluentui/font-icons-mdl2';

initializeIcons();

import './styles.css';
import LinksPicker from './LinksPicker';
import { parsePathName } from './utils';

const queryClient = new QueryClient();

const api = {
  _queueCallbacks: [],

  _openModalCallback: null,

  flush(link) {
    this._queueCallbacks.forEach(cb => cb(link));

    this._queueCallbacks = [];
  },

  requestOpenLinkPicker(callback) {
    if (typeof this._openModalCallback === 'function') {
      const _cb = this._openModalCallback;
      _cb();

      if (typeof callback === 'function') {
        this._queueCallbacks.push(callback);
      }
    } else {
      console.warn('Link picker is not ready yet');
    }
  },
};

const renderLinkPicker = () => {
  let container = document.querySelector('#modal-link-picker');
  let shouldUnmount = true;

  if (!container) {
    container = document.createElement('div');
    container.id = 'modal-link-picker';

    document.body.appendChild(container);
    shouldUnmount = false;
  }

  const root = createRoot(container);

  if (shouldUnmount) {
    root.unmount();
  }

  root.render(
    <QueryClientProvider client={queryClient}>
      <LinksPicker api={api} />
    </QueryClientProvider>,
  );
};

window.requestOpenLinkPicker = api.requestOpenLinkPicker.bind(api);

document.addEventListener('DOMContentLoaded', () => {
  renderLinkPicker();

  $('.js-open-link-picker').on('click', (e) => {
    e.preventDefault();

    const el = e.currentTarget;

    window.requestOpenLinkPicker((link) => {
      const parent = $(el).parent().find('input');

      const label = $(el).closest('.form').find('[data-target="components--menu.label"]');
      const title = $(el).closest('.form').find('[data-target="components--menu.title"]');

      parent.val(parsePathName(link.url));

      if (label.length && label.val() === '') {
        label.val(link.title);
      }

      if (title.length && title.val() === '') {
        title.val(link.title);
      }
    });
  });
});
