(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component, Fragment } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { RichText, MediaUpload } = wpEditor;
  const { Button } = wpComponents;

  class CMImage extends Component {
    valueUpdater = (field) => {
      const { setAttributes } = this.props;
      return (value) => setAttributes({ [field]: value });
    };

    onSelectImage = (media) => {
      const { setAttributes } = this.props;
      setAttributes({
        mediaURL: media.url,
        mediaId: media.id,
      });
    };

    render() {
      const { attributes } = this.props;
      const {
        mediaURL,
        mediaId,
        caption,
      } = attributes;

      return (
        <Fragment>
          <MediaUpload
            onSelect={this.onSelectImage}
            allowedTypes="image"
            value={mediaId}
            render={({ open }) => (
              <Button className={mediaId ? 'image-button' : 'button button-large'} onClick={open}>
                {!mediaId ? __('Chọn ảnh', 'gutenberg-examples') : <img src={mediaURL} alt="" />}
              </Button>
            )}
          />
          <RichText
            tagName="p"
            className="caption-image"
            value={caption}
            onChange={this.valueUpdater('caption')}
            placeholder={__('Nhập nội dung...')}
          />
          <br />
        </Fragment>
      );
    }
  }

  const tabsBlockIcon = (
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
      <path fill="none" d="M0,0h24v24H0V0z" />
      <path fill="none" d="M0,0h24v24H0V0z" />
      <path d="M21,3H3C1.9,3,1,3.9,1,5v14c0,1.1,0.9,2,2,2h18c1.1,0,2-0.9,2-2V5C23,3.9,22.1,3,21,3z M21,19H3V5h10v4h8V19z" />
    </svg>
  );

  const tabBlockAttrs = {
    mediaId: {
      type: 'number',
      default: 0,
    },
    mediaURL: {
      type: 'string',
      default: '',
    },
    caption: {
      type: 'string',
      default: '',
    },
  };

  registerBlockType('cgb/block-common-image', {
    title: __('Chung - Ảnh kèm caption'),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('Common'), __('Chung - anh kem caption'), __('bv chung')],
    attributes: tabBlockAttrs,
    edit: CMImage,
    save: function({ attributes }) {
      const {
        mediaURL,
        caption,
      } = attributes;

      return (
        <Fragment>
          {
            mediaURL && (
              <img src={mediaURL} alt={caption} />
            )
          }
          <RichText.Content
            tagName="p"
            className="caption-image"
            value={caption}
          />
          <br />
        </Fragment>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
