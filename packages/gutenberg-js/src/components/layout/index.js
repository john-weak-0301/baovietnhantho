import classnames from 'classnames'

import {
  Popover,
  FocusReturnProvider,
} from '@wordpress/components'

import {
  EditorNotices,
  UnsavedChangesWarning,
} from '@wordpress/editor'

import { withSelect } from '@wordpress/data'
import { PreserveScrollInReorder } from '@wordpress/block-editor'

import Header from '../header'
import Sidebar from '../sidebar'

import { PluginArea } from '@wordpress/plugins'
import TextEditor from '@wordpress/edit-post/build-module/components/text-editor'
import VisualEditor from '@wordpress/edit-post/build-module/components/visual-editor'
import OptionsModal from '@wordpress/edit-post/build-module/components/options-modal'
import ManageBlocksModal from '@wordpress/edit-post/build-module/components/manage-blocks-modal'
import EditorModeKeyboardShortcuts from '@wordpress/edit-post/build-module/components/keyboard-shortcuts'

function Layout ({ mode, sidebarIsOpened }) {
  const className = classnames('edit-post-layout', {
    'is-sidebar-opened': sidebarIsOpened,
    'has-fixed-toolbar': true,
  })

  return (
    <FocusReturnProvider className={className}>
      <UnsavedChangesWarning />

      <Header />

      <div
        className="edit-post-layout__content"
        role="region"
        tabIndex="-1"
      >
        <EditorNotices />
        <PreserveScrollInReorder />
        <EditorModeKeyboardShortcuts />
        <ManageBlocksModal />
        <OptionsModal />

        {mode === 'text' && <TextEditor />}
        {mode === 'visual' && <VisualEditor />}
      </div>

      {sidebarIsOpened && <Sidebar />}

      <Popover.Slot />
      <PluginArea />
    </FocusReturnProvider>
  )
}

export default withSelect(select => ({
  mode: select('core/edit-post').getEditorMode(),
  sidebarIsOpened: select('core/edit-post').isEditorSidebarOpened(),
}))(Layout)
