import { render } from '@wordpress/element';
import { uploadMedia } from '@wordpress/media-utils';

import './style.scss';
import IsolatedBlockEditor, { ToolbarSlot, DocumentSection } from '../index';

/** @typedef {import('../index').BlockEditorSettings} BlockEditorSettings */

/**
 * Saves content to the textarea
 *
 * @param {string} content Serialized block content
 * @param {HTMLTextAreaElement} textarea Textarea node
 */
function saveBlocks(content, textarea) {
  textarea.value = content;
}

/**
 * Initial content loader. Determine if the textarea contains blocks or raw HTML
 *
 * @param {string} content Text area content
 * @param {*} parser Gutenberg `parse` function
 * @param {*} rawHandler Gutenberg `rawHandler` function
 */
function onLoad(content, parser, rawHandler) {
  // Does the content contain blocks?
  if (content.indexOf('<!--') !== -1) {
    // Parse the blocks
    return parser(content);
  }

  // Raw HTML - do our best
  return rawHandler({ HTML: content });
}

/**
 * Attach IsolatedBlockEditor to a textarea
 *
 * @param {HTMLTextAreaElement} textarea Textarea node
 * @param {BlockEditorSettings} userSettings Settings object
 */
function attachEditor(textarea, userSettings = {}) {
  // Check it's a textarea
  if (textarea.type.toLowerCase() !== 'textarea') {
    return;
  }

  // Create a node after the textarea
  const editor = document.createElement('div');
  editor.classList.add('editor');

  // Insert after the textarea, and hide it
  // @ts-ignore
  textarea.parentNode.insertBefore(editor, textarea.nextSibling);
  textarea.style.display = 'none';

  // Render the editor
  render(
    <IsolatedBlockEditor
      settings={{ ...settings, ...userSettings }}
      onLoad={(parser, rawHandler) => onLoad(textarea.value, parser,
        rawHandler)}
      onSaveContent={(content) => saveBlocks(content, textarea)}
      onError={() => document.location.reload()}
    ></IsolatedBlockEditor>,
    editor,
  );
}

const mediaUploadBlockEditor = ({ onError, ...argumentsObject }) => {
  uploadMedia({
    wpAllowedMimeTypes: ['image'],
    onError: ({ message }) => onError(message),
    ...argumentsObject,
  });
};

const settings = {
  iso: {
    toolbar: {
      inspector: true,
      toc: true,
      navigation: true,
      selectorTool: true,
    },
    sidebar: {
      inspector: true,
      inserter: true,
    },
    footer: true,

    moreMenu: {
      editor: true,
      fullscreen: true,
      preview: true,
      topToolbar: true,
    },

    blocks: {
      allowBlocks: [
        'core/paragraph',
        'core/image',
        'core/html',
        'core/shortcode',
        'core/custom',
        'core/social',
        'core/social-link',
        'core/social-links',
        'core/file',
      ],
    },
  },

  editor: {
    colors: [
      {
        'slug': 'primary',
        'color': '#3858E9',
        'name': 'Primary',
      },
      {
        'slug': 'secondary',
        'color': '#3858E9',
        'name': 'Secondary',
      },
      {
        'slug': 'foreground',
        'color': '#1E1E1E',
        'name': 'Foreground',
      },
      {
        'slug': 'background',
        'color': '#ffffff',
        'name': 'Background',
      },
      {
        'slug': 'tertiary',
        'color': '#F0F0F0',
        'name': 'Tertiary',
      },
    ],

    enableCustomFields: true,
    enableCustomLineHeight: true,
    enableCustomSpacing: true,
    enableCustomUnits: ['%', 'px', 'em', 'rem', 'vh', 'vw'],

    imageEditing: false,
    imageDefaultSize: 'large',
    allowedMimeTypes: {
      'jpg|jpeg|jpe': 'image/jpeg',
      'png': 'image/png',
      'gif': 'image/gif',
      'mp3|m4a|m4b': 'audio/mpeg',
      'mov|qt': 'video/quicktime',
      'avi': 'video/avi',
      'wmv': 'video/x-ms-wmv',
      'mid|midi': 'audio/midi',
      'pdf': 'application/pdf',
      'mp4|m4v': 'video/mp4',
      'webm': 'video/webm',
      'ogv': 'video/ogg',
      'txt|asc|c|cc|h|srt': 'text/plain',
      'webp': 'image/webp',
    },
    mediaUpload: mediaUploadBlockEditor,
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
  },
};

document.addEventListener('DOMContentLoaded', () => {
  render(
    <IsolatedBlockEditor
      settings={settings}
      onError={() => document.location.reload()}>

      <ToolbarSlot>
        <p>Slot</p>
      </ToolbarSlot>
    </IsolatedBlockEditor>,
    document.getElementById('editor'),
  );
});
