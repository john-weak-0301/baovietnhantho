import '@babel/polyfill';

import domReady from '@wordpress/dom-ready';
import {dispatch} from '@wordpress/data';
import {render, unmountComponentAtNode} from '@wordpress/element';

import '@wordpress/editor';
import '@wordpress/format-library';

import Editor from './editor';
import registerCoreBlocks from './coreblocks';

// Init the store.
import '@wordpress/editor/build-module/store';

import '@wordpress/edit-post/build-module/hooks/components';
import '@wordpress/edit-post/build-module/plugins';
import '@wordpress/edit-post/build-module/store';

import './exports';

function reinitializeEditor(target, post, settings, initialEdits) {
  unmountComponentAtNode(target);
  const reboot = reinitializeEditor.bind(null, target, post, settings,
    initialEdits);

  render(
    <Editor
      post={post}
      settings={settings}
      initialEdits={initialEdits}
      onError={reboot}
      recovery
    />,
    target,
  );
}

function initializeEditor(id, post, settings, initialEdits) {
  const target = document.getElementById(id);
  const reboot = reinitializeEditor.bind(null, target, post, settings,
    initialEdits);

  registerCoreBlocks();

  // Show a console log warning if the browser is not in Standards rendering mode.
  const documentMode = document.compatMode === 'CSS1Compat'
    ? 'Standards'
    : 'Quirks';
  if (documentMode !== 'Standards') {
    // eslint-disable-next-line no-console
    console.warn(
      'Your browser is using Quirks Mode. ' +
      'Quirks Mode can be triggered by PHP errors or HTML code appearing before the opening <!DOCTYPE html>. ' +
      'Try checking the raw page source or your site\'s PHP error log and resolving errors there, ' +
      'removing any HTML before the doctype, or disabling plugins.',
    );
  }

  render(
    <Editor
      post={post}
      settings={settings}
      initialEdits={initialEdits}
      onError={reboot}
    />,
    target,
  );
}

(function() {
  const uid = window.userSettings ? window.userSettings.uid || 2 : 2;
  const storageKey = `WP_DATA_USER_${uid}`;

  wp.data.use(wp.data.plugins.persistence, {storageKey: storageKey});
  wp.data.plugins.persistence.__unstableMigrate({storageKey: storageKey});

  const post = {
    id: 0,
    title: '',
    type: 'page',
    content: window.gutenbergContent ? window.gutenbergContent : {raw: ''},
  };

  const settings = {
    isRTL: false,
    allowedBlockTypes: true,
    availableTemplates: [],
    mainSidebarActive: true,
    postLock: {isLocked: false},
    titlePlaceholder: '',
    imageSizes: [
      {
        'slug': 'thumbnail',
        'name': 'Thumbnail',
      },
      {
        'slug': 'medium',
        'name': 'Medium',
      },
      {
        'slug': 'large',
        'name': 'Large',
      },
      {
        'slug': 'full',
        'name': 'Full',
      },
    ],
  };

  const initialEdits = {
    title: '',
    excerpt: '',
    content: post.content.raw || '',
  };

  window._wpLoadGutenbergEditor = new Promise(function(resolve) {
    domReady(function() {
      resolve(initializeEditor('editor', post, settings, initialEdits));
    });
  });
})();
