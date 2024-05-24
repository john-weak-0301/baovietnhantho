/**
 * External dependencies
 */
import memize from 'memize'
import { size, map, without } from 'lodash'

/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data'
import { Component } from '@wordpress/element'
import { EditorProvider, ErrorBoundary } from '@wordpress/editor'

import {
  SlotFillProvider,
  DropZoneProvider,
} from '@wordpress/components'

/**
 * Internal dependencies
 */
import Layout from './components/layout'

class Editor extends Component {
  constructor () {
    super(...arguments)

    this.getEditorSettings = memize(this.getEditorSettings, {
      maxSize: 1,
    })
  }

  getEditorSettings (
    settings,
    hiddenBlockTypes,
    blockTypes,
  ) {
    // Omit hidden block types if exists and non-empty.
    if (size(hiddenBlockTypes) > 0) {
      // Defer to passed setting for `allowedBlockTypes` if provided as
      // anything other than `true` (where `true` is equivalent to allow
      // all block types).
      const defaultAllowedBlockTypes = (
        true === settings.allowedBlockTypes ?
          map(blockTypes, 'name') :
          (settings.allowedBlockTypes || [])
      )

      settings.allowedBlockTypes = without(
        defaultAllowedBlockTypes,
        ...hiddenBlockTypes,
      )
    }

    return settings
  }

  render () {
    const {
      post,
      settings,
      initialEdits,
      onError,
      hiddenBlockTypes,
      blockTypes,
      ...props
    } = this.props

    const editorSettings = this.getEditorSettings(
      settings,
      hiddenBlockTypes,
      blockTypes,
    )

    return (
      <>
        <SlotFillProvider>
          <DropZoneProvider>
            <EditorProvider
              post={post}
              settings={editorSettings}
              initialEdits={initialEdits}
              useSubRegistry={false}
              {...props}
            >
              <ErrorBoundary onError={onError}>
                <Layout />
              </ErrorBoundary>
            </EditorProvider>
          </DropZoneProvider>
        </SlotFillProvider>
      </>
    )
  }
}

export default withSelect((select) => {
  const { getPreference } = select('core/edit-post')
  const { getBlockTypes } = select('core/blocks')

  return {
    hiddenBlockTypes: getPreference('hiddenBlockTypes'),
    blockTypes: getBlockTypes(),
  }
})(Editor)
