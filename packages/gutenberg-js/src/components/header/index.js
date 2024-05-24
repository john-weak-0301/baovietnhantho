import {
  Inserter,
  NavigableToolbar,
  BlockNavigationDropdown,
} from '@wordpress/block-editor'

import {
  TableOfContents,
  EditorHistoryRedo,
  EditorHistoryUndo,
} from '@wordpress/editor'

import { __ } from '@wordpress/i18n'
import { compose } from '@wordpress/compose'
import { withSelect, withDispatch } from '@wordpress/data'
import { IconButton, Button } from '@wordpress/components'

import PinnedPlugins from '@wordpress/edit-post/build-module/components/header/pinned-plugins'
import FullscreenModeClose from '@wordpress/edit-post/build-module/components/header/fullscreen-mode-close'

function Header (props) {
  const toggleGeneralSidebar = props.isSidebarOpened ? props.closeGeneralSidebar : props.openGeneralSidebar

  return (
    <div role="region" className="edit-post-header" tabIndex="-1">
      <div className="edit-post-header__toolbar">
        <FullscreenModeClose />

        {props.hasEditUrl && (
          <IconButton
            icon={'arrow-left-alt2'}
            label={__('Edit')}
            href={props.getEditUrl()}></IconButton>
        )}

        <NavigableToolbar className="edit-post-header-toolbar">
          <Inserter position="bottom right" />

          <EditorHistoryUndo />
          <EditorHistoryRedo />

          <TableOfContents />
          <BlockNavigationDropdown />
        </NavigableToolbar>
      </div>

      <div className="edit-post-header__settings">
        {props.hasPreviewUrl && (
          <Button
            isLink
            target={'_blank'}
            label={__('Xem Trang')}
            href={props.getPreviewUrl()}>
            {__('Xem Trang')}
          </Button>
        )}

        <Button
          isPrimary
          label={__('Lưu thay đổi')}
          onClick={props.onSavehandler}>
          {__('Lưu thay đổi')}
        </Button>,

        <IconButton
          icon="admin-generic"
          label={__('Settings')}
          isToggled={props.isSidebarOpened}
          onClick={toggleGeneralSidebar}
        />

        <PinnedPlugins.Slot />
      </div>
    </div>
  )
}

export default compose(
  withSelect(select => ({
    hasEditUrl: !!window._gutenbergEditUrl,
    hasPreviewUrl: !!window._gutenbergPreviewUrl,
    isSidebarOpened: select('core/edit-post').isEditorSidebarOpened(),
  })),

  withDispatch((dispatch, ownProps, { select }) => {
    const {
      openGeneralSidebar,
      closeGeneralSidebar,
    } = dispatch('core/edit-post')

    return {
      closeGeneralSidebar,
      openGeneralSidebar: () => openGeneralSidebar('edit-post/block'),

      onSavehandler () {
        if (window.onGutenbergSave) {
          dispatch('core/editor').savePost()
          window.onGutenbergSave(select('core/editor').getEditedPostContent())
        }
      },

      getEditUrl () {
        if (window._gutenbergEditUrl) {
          return window._gutenbergEditUrl
        }
      },

      getPreviewUrl () {
        if (window._gutenbergPreviewUrl) {
          return window._gutenbergPreviewUrl
        }
      },
    }
  }),
)(Header)
