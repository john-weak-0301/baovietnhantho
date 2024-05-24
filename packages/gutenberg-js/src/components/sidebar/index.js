import { BlockInspector } from '@wordpress/block-editor'
import { Panel, PanelBody } from '@wordpress/components'

function Sidebar () {
  return (
    <div className="edit-post-sidebar">
      <Panel>
        <PanelBody className="edit-post-settings-sidebar__panel-block">
          <BlockInspector />
        </PanelBody>
      </Panel>
    </div>
  )
}

export default Sidebar
