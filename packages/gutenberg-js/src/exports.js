import * as hooks from '@wordpress/hooks';
import * as i18n from '@wordpress/i18n';

import * as url from '@wordpress/url';
import apiFetch from '@wordpress/api-fetch';
import apiFetchHandler from './api/api-fetch';

apiFetch.setFetchHandler(apiFetchHandler);

import * as autop from '@wordpress/autop';
import * as blob from '@wordpress/blob';
import * as blockSerializationDefaultParser
  from '@wordpress/block-serialization-default-parser';

import * as escapeHtml from '@wordpress/escape-html';
import * as element from '@wordpress/element';
import * as isShallowEqual from '@wordpress/is-shallow-equal';
import * as compose from '@wordpress/compose';
import * as reduxRoutine from '@wordpress/redux-routine';
import * as data from '@wordpress/data';
import * as dom from '@wordpress/dom';
import * as htmlEntities from '@wordpress/html-entities';
import * as shortcode from '@wordpress/shortcode';

import * as blocks from '@wordpress/blocks';

import * as keycodes from '@wordpress/keycodes';
import * as richText from '@wordpress/rich-text';

import * as components from '@wordpress/components';
import * as coreData from '@wordpress/core-data';
import * as date from '@wordpress/date';

import deprecated from '@wordpress/deprecated';
import * as notices from '@wordpress/notices';

import * as nux from '@wordpress/nux';
import * as tokenList from '@wordpress/token-list';
import * as viewport from '@wordpress/viewport';
import * as wordcount from '@wordpress/wordcount';

import * as blockEditor from '@wordpress/block-editor';
import * as blockLibrary from '@wordpress/block-library';
import domReady from '@wordpress/dom-ready';
import * as plugins from '@wordpress/plugins';

// import * as editor from '@wordpress/editor';
import {editor, oldEditor} from './libs/editor.js';

import * as a11y from '@wordpress/a11y';
import * as formatLibrary from '@wordpress/format-library';
import * as mediaUtils from '@wordpress/media-utils';

const imports = {
  a11y,
  apiFetch,
  autop,
  blob,
  blockEditor,
  blockLibrary,
  blockSerializationDefaultParser,
  blocks,
  components,
  compose,
  coreData,
  data,
  date,
  deprecated,
  dom,
  domReady,
  element,
  escapeHtml,
  formatLibrary,
  hooks,
  htmlEntities,
  i18n,
  isShallowEqual,
  keycodes,
  notices,
  nux,
  plugins,
  reduxRoutine,
  richText,
  shortcode,
  tokenList,
  url,
  viewport,
  wordcount,
  editor,
  oldEditor,
  mediaUtils,
};

window.wp = {
  ...(window.wp || {}),
  ...imports,
};

export default imports;
