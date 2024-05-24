import '@wordpress/core-data';
import '@wordpress/block-editor';
import '@wordpress/editor';

import {
  registerBlockType,
  setDefaultBlockName,
  setUnregisteredTypeHandlerName,
  unstable__bootstrapServerSideBlockDefinitions,
} from '@wordpress/blocks';

import * as paragraph from '@wordpress/block-library/build-module/paragraph';
import * as image from '@wordpress/block-library/build-module/image';
import * as heading from '@wordpress/block-library/build-module/heading';
import * as quote from '@wordpress/block-library/build-module/quote';
import * as gallery from '@wordpress/block-library/build-module/gallery';
import * as audio from '@wordpress/block-library/build-module/audio';
import * as button from '@wordpress/block-library/build-module/button';
import * as code from '@wordpress/block-library/build-module/code';
import * as columns from '@wordpress/block-library/build-module/columns';
import * as column from '@wordpress/block-library/build-module/column';
import * as cover from '@wordpress/block-library/build-module/cover';
import * as embed from '@wordpress/block-library/build-module/embed';
import * as file from '@wordpress/block-library/build-module/file';
import * as html from '@wordpress/block-library/build-module/html';
import * as mediaText from '@wordpress/block-library/build-module/media-text';
import * as list from '@wordpress/block-library/build-module/list';
import * as missing from '@wordpress/block-library/build-module/missing';
import * as preformatted from '@wordpress/block-library/build-module/preformatted';
import * as pullquote from '@wordpress/block-library/build-module/pullquote';
import * as separator from '@wordpress/block-library/build-module/separator';
import * as spacer from '@wordpress/block-library/build-module/spacer';
import * as subhead from '@wordpress/block-library/build-module/subhead';
import * as table from '@wordpress/block-library/build-module/table';
import * as template from '@wordpress/block-library/build-module/template';
import * as textColumns from '@wordpress/block-library/build-module/text-columns';
import * as verse from '@wordpress/block-library/build-module/verse';
import * as video from '@wordpress/block-library/build-module/video';
import * as group from "@wordpress/block-library/build-module/group";
import * as shortcode from "@wordpress/block-library/build-module/shortcode";
import * as reusableBlock from "@wordpress/block-library/build-module/block";
import * as classic from '@wordpress/block-library/build-module/classic'
import * as navigationMenu from '@wordpress/block-library/build-module/navigation-menu'
import * as navigationMenuItem from '@wordpress/block-library/build-module/navigation-menu-item'

shortcode.settings.category = 'layout';
shortcode.settings.description = 'Insert a shortcode';

const registerCoreBlocks = () => {
  [
    paragraph,
    image,
    heading,
    gallery,
    list,
    quote,
    audio,
    button,
    code,
    columns,
    column,
    group,
    window.wp && window.wp.oldEditor ? classic : null, // Only add the classic block in WP Context
    cover,
    embed,
    ...embed.common,
    file,
    html,
    mediaText,
    missing,
    preformatted,
    pullquote,
    separator,
    reusableBlock,
    spacer,
    subhead,
    table,
    template,
    textColumns,
    verse,
    video,
    shortcode,
    navigationMenu,
    navigationMenuItem,
  ].forEach((block) => {
    if (!block) {
      return;
    }

    const {metadata, settings, name} = block;
    if (metadata) {
      unstable__bootstrapServerSideBlockDefinitions({[name]: metadata}); // eslint-disable-line camelcase
    }

    registerBlockType(name, settings);
  });

  setDefaultBlockName(paragraph.name);
  setUnregisteredTypeHandlerName(missing.name);
};

export default registerCoreBlocks
