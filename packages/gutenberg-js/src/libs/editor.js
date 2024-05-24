import tinymce from 'tinymce'
import * as oEditor from '@wordpress/editor'

window.tinymce = window.tinymce || tinymce

window.wpEditorL10n = window.wpEditorL10n || {
  tinymce: {
    baseUrl: 'node_modules/tinymce',
    settings: {
      external_plugins: [],
      plugins: 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen', // ,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview',
      toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,kitchensink',
      toolbar2: 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
      toolbar3: '',
      toolbar4: '',
    },
    suffix: '.min',
  },
}

const oldEditorFunctions = {
  initialize: (id, settings = { tinymce: true }) => {
    const defaults = window.wp.editor.getDefaultSettings()
    const init = jQuery.extend({}, defaults.tinymce, settings.tinymce)

    init.selector = '#' + id

    window.tinymce.init(init)

    if (!window.wpActiveEditor) {
      window.wpActiveEditor = id
    }
  },

  autop: () => {},

  getContent: id => {
    const editor = window.tinymce.get(id)

    if (editor && !editor.isHidden()) {
      editor.save()
    }

    return jQuery('#' + id).val()
  },

  remove: id => {
    const mceInstance = window.tinymce.get(id)

    if (mceInstance) {
      if (!mceInstance.isHidden()) {
        mceInstance.save()
      }

      mceInstance.remove()
    }
  },

  removep: () => {},

  getDefaultSettings: () => ({
    tinymce: {
      indent: true,
      keep_styles: false,
      menubar: false,
      plugins: 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen',
      resize: 'vertical',
      skin: 'lightgray',
      theme: 'modern',
      toolbar1: 'bold,italic,bullist,numlist,link',
    },

    quicktags: {
      buttons: 'strong,em,link,ul,ol,li,code',
    },
  }),
}

const editor = {
  ...oEditor,
  ...oldEditorFunctions,
}

const oldEditor = editor

export {
  editor,
  oldEditor,
}
