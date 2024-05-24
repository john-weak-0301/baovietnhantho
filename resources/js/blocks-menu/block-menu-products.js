(function(wpI18n, wpBlocks, wpElement) {
  const { __ } = wpI18n
  const { Component, Fragment } = wpElement
  const { registerBlockType } = wpBlocks

  const attributes = {}

  class EditComponent extends Component {
    valueUpdater = (field) => {
      const { setAttributes } = this.props
      return (value) => setAttributes({ [field]: value })
    }

    render() {
      return (
        <Fragment>
          <p>Danh sách sản phẩm sẽ được render tự động</p>
        </Fragment>
      )
    }
  }

  const blockIcon = (
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="bi bi-menu-button-wide-fill" viewBox="0 0 16 16">
      <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v2A1.5 1.5 0 0 0 1.5 5h13A1.5 1.5 0 0 0 16 3.5v-2A1.5 1.5 0 0 0 14.5 0h-13zm1 2h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1zm9.927.427A.25.25 0 0 1 12.604 2h.792a.25.25 0 0 1 .177.427l-.396.396a.25.25 0 0 1-.354 0l-.396-.396zM0 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8zm1 3v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2H1zm14-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2h14zM2 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
    </svg>
  )

  registerBlockType('cgb/block-menu-products', {
    title: __('Menu - Danh sách sản phẩm'),
    icon: { src: blockIcon },
    category: 'widgets',
    attributes,
    edit: EditComponent,
    save: function({ attributes }) {
      return (
        <Fragment>
          [MENUDSSP]
        </Fragment>
      )
    },
  })
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components))
