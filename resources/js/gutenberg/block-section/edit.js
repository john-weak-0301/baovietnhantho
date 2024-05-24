/**
 * External dependencies
 */
import classnames from 'classnames'

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n'
import { compose } from '@wordpress/compose'
import { withSelect } from '@wordpress/data'

import {
  InspectorControls,
  InnerBlocks,
  PanelColorSettings,
  withColors,
} from '@wordpress/block-editor'

import {
  PanelBody,
  ToggleControl,
} from '@wordpress/components'

import getStyle from '../block-background/utils/get-style'
import BackgroundInspector from '../block-background/components/inspector'
import '../block-background/style.scss'

function GroupEdit (props) {
  const {
    className,
    setBackgroundColor,
    backgroundColor,
    hasInnerBlocks,
    attributes: {
      darkSkin,
      fluidContainer,
    },
  } = props

  const styles = {
    backgroundColor: backgroundColor.color,
    ...getStyle(props.attributes),
  }

  const classes = classnames(className, backgroundColor.class, {
    'md-skin-dark': !!darkSkin,
    'has-background': !!backgroundColor.color || !!styles,
  })

  const containerClasses = classnames({
    'container': !fluidContainer,
    'container-fluid': !!fluidContainer,
  })

  const onChangeFluidContainer = (value) => {
    props.setAttributes({ fluidContainer: !!value })
  }

  const onChangeDarkSkin = (value) => {
    props.setAttributes({ darkSkin: !!value })
  }

  return (
    <>
      <BackgroundInspector {...{ ...props }} />

      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen={true}>
          <ToggleControl
            key="togglecontrol"
            label={__('Full Container')}
            checked={!!fluidContainer}
            onChange={onChangeFluidContainer}
          />

          <ToggleControl
            key="togglecontrol1"
            label={__('Dark Skin')}
            checked={!!darkSkin}
            onChange={onChangeDarkSkin}
          />
        </PanelBody>

        <PanelColorSettings
          title={__('Color Settings')}
          initialOpen={false}
          colorSettings={[
            {
              value: backgroundColor.color,
              onChange: setBackgroundColor,
              label: __('Background Color'),
            },
          ]}
        />
      </InspectorControls>

      <div className={classes} style={styles}>
        <div className={containerClasses}>
          <InnerBlocks renderAppender={
            !hasInnerBlocks && InnerBlocks.ButtonBlockAppender
          } />
        </div>
      </div>
    </>
  )
}

export default compose([
  withColors('backgroundColor'),
  withSelect((select, { clientId }) => {
    const { getBlock } = select('core/block-editor')
    const block = getBlock(clientId)

    return {
      hasInnerBlocks: !!(block && block.innerBlocks.length),
    }
  }),
])(GroupEdit)
