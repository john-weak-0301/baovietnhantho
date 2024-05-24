/**
 * External dependencies
 */
import classnames from 'classnames'

/**
 * WordPress dependencies
 */
import { InnerBlocks, getColorClassName } from '@wordpress/block-editor'

import getStyle from '../block-background/utils/get-style'

export default function save ({ attributes }) {
  const {
    darkSkin,
    fluidContainer,
    backgroundColor,
    customBackgroundColor,
    backgroundType,
    solidColor,
    gradient,
    imageID,
  } = attributes

  let styles = {}

  const backgroundClass = getColorClassName(
    'background-color', backgroundColor,
  )

  if (backgroundColor || customBackgroundColor) {
    styles = {
      backgroundColor: backgroundClass
        ? undefined
        : customBackgroundColor,
    }
  }

  if (backgroundType && (solidColor || gradient || imageID)) {
    styles = { ...styles, ...getStyle(attributes) }
  }

  const className = classnames('md-section', backgroundClass, {
    'md-skin-dark': !!darkSkin,
    'has-background': !_.isEmpty(styles),
  })

  const containerClasses = !fluidContainer
    ? 'container'
    : 'container-fluid'

  return (
    <div className={className} style={styles}>
      <div className={containerClasses}>
        <InnerBlocks.Content />
      </div>
    </div>
  )
}
