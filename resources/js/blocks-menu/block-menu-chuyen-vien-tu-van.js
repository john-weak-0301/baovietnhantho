(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component, Fragment } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { RichText, InspectorControls, MediaUpload } = wpEditor;
  const { PanelBody, Button } = wpComponents;

  const attributes = {
    imageUrl: {
      type: 'string',
    },
    title: {
      type: 'string',
      default: 'Chọn chuyên viên tư vấn cho riêng bạn',
    },
    desc: {
      type: 'string',
      default: 'Chủ động đặt hẹn với chuyên viên tài chính Bảo Việt để được lắng nghe và thấu hiểu',
    },
    buttonText: {
      type: 'string',
      default: 'Bắt đầu ngay',
    },
  };

  class EditComponent extends Component {
    valueUpdater = (field) => {
      const { setAttributes } = this.props;
      return (value) => setAttributes({ [field]: value });
    };

    onSelectImage = (media) => {
      const { setAttributes } = this.props;

      setAttributes({
        imageUrl: media.sizes.full.url,
      });
    };

    render() {
      const { attributes } = this.props;

      const {
        imageUrl,
        title,
        desc,
        buttonText,
      } = attributes;

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

          <div className="consultants">
            <div className="consultants__avatar">
              <img
                src={imageUrl || '/img/image/avatarchuyenvientuvan.png'}
                alt="chuyen vien tu van"
              />
            </div>

            <RichText
              tagName="div"
              className="consultants__title"
              value={title}
              onChange={this.valueUpdater('title')}
              placeholder={__('Nhập tiêu đề...')}
            />

            <RichText
              tagName="p"
              className="consultants__text"
              value={desc}
              onChange={this.valueUpdater('desc')}
              placeholder={__('Nhập nội dung...')}
            />

            <RichText
              tagName="a"
              className="btn btn-secondary"
              value={buttonText}
              onChange={this.valueUpdater('buttonText')}
            />
          </div>
        </Fragment>
      );
    }
  }

  const blockIcon = (
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="bi bi-menu-button-wide-fill" viewBox="0 0 16 16">
      <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v2A1.5 1.5 0 0 0 1.5 5h13A1.5 1.5 0 0 0 16 3.5v-2A1.5 1.5 0 0 0 14.5 0h-13zm1 2h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1zm9.927.427A.25.25 0 0 1 12.604 2h.792a.25.25 0 0 1 .177.427l-.396.396a.25.25 0 0 1-.354 0l-.396-.396zM0 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8zm1 3v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2H1zm14-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2h14zM2 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
    </svg>
  );

  registerBlockType('cgb/block-menu-chuyen-vien', {
    title: __('Menu - Chuyên viên tư vấn'),
    icon: { src: blockIcon },
    category: 'widgets',
    attributes,
    edit: EditComponent,
    save: function({ attributes }) {
      const {
        title,
        desc,
        buttonText,
        imageUrl,
      } = attributes;

      return (
        <div className="consultants">
          <div className="consultants__avatar">
            <img
              src={imageUrl || '/img/image/avatarchuyenvientuvan.png'}
              alt="chuyen vien tu van"
            />
          </div>

          <RichText.Content
            tagName="div"
            className="consultants__title"
            value={title}
          />

          <RichText.Content
            tagName="p"
            className="consultants__text"
            value={desc}
          />

          <a className="btn btn-secondary" href="/tu-van">{buttonText}</a>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
