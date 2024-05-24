import { assign } from 'lodash'

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n'
import { registerBlockType } from '@wordpress/blocks'

/**
 * Internal dependencies
 */
import edit from './edit'
import icon from './icon'
import metadata from './block.json'
import save from './save'

import backgroundSettings from '../block-background/data/attributes'

metadata.attributes = assign(metadata.attributes, backgroundSettings)

const { name } = metadata
const settings = {
  ...metadata,
  title: __('Section'),
  icon,
  description: __('A block that groups other blocks.'),
  keywords: [__('container'), __('wrapper'), __('row'), __('section')],
  supports: {
    align: ['wide', 'full'],
    anchor: true,
    html: false,
  },
  edit,
  save,
}

registerBlockType(name, settings)
