(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n
  const { Component, Fragment } = wpElement
  const { registerBlockType } = wpBlocks
  const { RichText, InspectorControls, MediaUpload, URLInput } = wpEditor
  const { PanelBody, Button } = wpComponents

  const attributes = {
    imageUrl: {
      type: 'string',
      default: '/img/svg/iconbox3.svg',
    },
    title: {
      type: 'string',
      default: 'Bảo vệ và chăm sóc y tế',
    },
    desc: {
      type: 'string',
      default: '"Sức khỏe là tài sản quý giá nhất" - Hãy sống tích cực, sống chủ động, lựa chọn các giải pháp tài chính tối ưu để bảo vệ cuộc sống và sức khỏe của chính mình và những người thân yêu từ ngay hôm nay.',
    },
    targetUrl: {
      type: 'string',
      default: '',
    },
  }

  class EditComponent extends Component {
    valueUpdater = (field) => {
      const { setAttributes } = this.props
      return (value) => setAttributes({ [field]: value })
    }

    onSelectImage = (media) => {
      const { setAttributes } = this.props

      setAttributes({
        imageUrl: media.sizes.full.url,
      })
    }

    render() {
      const { attributes } = this.props

      const {
        imageUrl,
        title,
        desc,
        targetUrl,
      } = attributes

      return (
        <Fragment>
          <InspectorControls>
            <PanelBody title={__('Settings')}>
              <MediaUpload
                onSelect={this.onSelectImage}
                type="image"
                value={imageUrl}
                render={({ open }) => (
                  <Button onClick={open}>Chọn ảnh</Button>
                )}
              />
            </PanelBody>
          </InspectorControls>

          <div className="iconbox-02">
            <div className="iconbox-02__inner" style={{
              paddingLeft: !imageUrl ? 0 : undefined,
            }}>
              {imageUrl && (
                <span className="iconbox-02__icon">
                  <img
                    src={imageUrl}
                    alt=""
                  />
                </span>
              )}

              <div className="iconbox-02__title">
                <RichText
                  tagName="a"
                  value={title}
                  onChange={this.valueUpdater('title')}
                  placeholder={__('Nhập tiêu đề...')}
                />

                <URLInput
                  value={targetUrl}
                  onChange={this.valueUpdater('targetUrl')}
                />
              </div>

              <RichText
                tagName="p"
                className="iconbox-02__text"
                value={desc}
                onChange={this.valueUpdater('desc')}
                placeholder={__('Nhập nội dung...')}
              />
            </div>
          </div>
        </Fragment>
      )
    }
  }

  const blockIcon = (
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="bi bi-menu-button-wide-fill" viewBox="0 0 16 16">
      <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v2A1.5 1.5 0 0 0 1.5 5h13A1.5 1.5 0 0 0 16 3.5v-2A1.5 1.5 0 0 0 14.5 0h-13zm1 2h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1zm9.927.427A.25.25 0 0 1 12.604 2h.792a.25.25 0 0 1 .177.427l-.396.396a.25.25 0 0 1-.354 0l-.396-.396zM0 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8zm1 3v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2H1zm14-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2h14zM2 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
    </svg>
  )

  registerBlockType('cgb/block-menu-icon-box', {
    title: __('Menu - Icon Box'),
    icon: { src: blockIcon },
    category: 'widgets',
    attributes,
    edit: EditComponent,
    save: function({ attributes }) {
      const {
        title,
        desc,
        targetUrl,
        imageUrl,
      } = attributes

      return (
        <div className="iconbox-02">
          <div className="iconbox-02__inner" style={{
            paddingLeft: !imageUrl
              ? 0
              : undefined,
          }}>
            {imageUrl && (
              <span className="iconbox-02__icon">
                <img src={imageUrl} alt={title} />
              </span>
            )}

            <div className="iconbox-02__title">
              <RichText.Content
                tagName={targetUrl ? 'a' : 'span'}
                value={title}
                href={targetUrl}
              />
            </div>

            <RichText.Content
              tagName="p"
              className="iconbox-02__text"
              value={desc}
            />
          </div>
        </div>
      )
    },
  })
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components))
