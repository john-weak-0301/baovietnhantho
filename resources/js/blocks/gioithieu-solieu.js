(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component, Fragment } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { RichText, InspectorControls, MediaUpload } = wpEditor;
  const { Dashicon, Tooltip, Button, PanelBody } = wpComponents;

  class GTSoLieu extends Component {
    componentDidUpdate() {
      // const { contentItems: prevItems } = prevProps.attributes;
      const { contentItems } = this.props.attributes;

      if (contentItems.length === 0) {
        this.props.setAttributes({
          contentItems: [
            {
              body: 'Cần có ít nhất 1 item.',
            },
          ],
        });
      }
    }

    updateItem(value, index) {
      const { attributes, setAttributes } = this.props;
      const { contentItems } = attributes;

      const newItems = contentItems.map((item, thisIndex) => {
        if (index === thisIndex) {
          item = { ...item, ...value };
        }

        return item;
      });

      setAttributes({ contentItems: newItems });
    };

    addNewItem = () => {
      const { attributes, setAttributes } = this.props;
      const { contentItems } = attributes;

      setAttributes({
        contentItems: [
          ...contentItems,
          { number: '', body: __('') },
        ],
      });
    };

    addNewItem2 = () => {
      const { attributes, setAttributes } = this.props;
      const { contentItems } = attributes;

      setAttributes({
        contentItems: [
          { number: '', body: __('') },
          ...contentItems,
        ],
      });
    };

    valueUpdater = (field) => {
      const { setAttributes } = this.props;
      return (value) => setAttributes({ [field]: value });
    };

    render() {
      const { attributes, setAttributes } = this.props;
      const {
        contentItems,
        mediaURL,
      } = attributes;

      const onSelectImage = (media) => {
        setAttributes({
          mediaURL: media.sizes.full.url,
        });
      };

      return (
        <Fragment>
          <InspectorControls>
            <PanelBody title={__('Settings')}>
              <MediaUpload
                onSelect={onSelectImage}
                type="image"
                value={mediaURL} // make sure you destructured backgroundImage from props.attributes!
                render={({ open }) => (
                  <Button onClick={open} className="button button-large">Chọn ảnh nền</Button>
                )}
              />
            </PanelBody>
          </InspectorControls>
          <div className="featured-number-box" style={{ backgroundImage: `url(${mediaURL})` }}>
            <div className="featured-number-box__inner">
              <Tooltip text={__('Thêm')}>
								<span onClick={this.addNewItem2}>
									<Dashicon icon="plus-alt" />
								</span>
              </Tooltip>
              {contentItems.map((item, index) => (
                <div key={index} className="featured-number">
                  <RichText
                    tagName="span"
                    className="featured-number__number"
                    value={item.number}
                    onChange={(value) => this.updateItem({ number: value }, index)}
                    placeholder={__('50')}
                  />
                  <RichText
                    tagName="p"
                    className="featured-number__text"
                    value={item.body}
                    onChange={(value) => this.updateItem({ body: value }, index)}
                    placeholder={__('Nhập nội dung...')}
                  />
                  <Tooltip text={__('Xoá')}>
										<span
                      onClick={() => setAttributes({
                        contentItems: contentItems.filter((vl, idx) => idx !== index),
                      })}
                    >
											<Dashicon icon="no" />
										</span>
                  </Tooltip>
                </div>
              ))}
              <Tooltip text={__('Thêm')}>
								<span onClick={this.addNewItem}>
									<Dashicon icon="plus-alt" />
								</span>
              </Tooltip>
            </div>
          </div>
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
    contentItems: {
      type: 'array',
      default: [
        {
          number: '50',
          body: __(
            'Sản phẩm các loại nhằm đáp ứng tốt nhất nhu cầu bảo vệ, đầu tư tài chính của người dân Việt Nam'),
        },
        {
          number: '300+',
          body: __(
            'Hơn 300 điểm phục vụ khách hàng cũng là một ưu điểm vượt trội của Bảo Việt Nhân thọ'),
        },
        {
          number: '64',
          body: __('Mạng lưới gầm 64 tỉnh thành trên toàn quốc'),
        },
      ],
    },
    mediaURL: {
      type: 'string',
      default: '/img/image/keangnam.jpg',
    },
  };

  registerBlockType('cgb/block-gioithieu-solieu', {
    title: __('Giới thiệu - Số liệu'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('gioi thieu'), __('Giới thiệu - Số liệu'), __('bv gioi thieu')],
    attributes: tabBlockAttrs,
    edit: GTSoLieu,
    save: function({ attributes }) {
      const {
        contentItems,
        mediaURL,
      } = attributes;

      return (
        <Fragment>
          <div className="featured-number-box" style={{ backgroundImage: `url(${mediaURL})` }}>
            <div className="featured-number-box__inner">
              {contentItems.map((item, index) => (
                <div key={index} className="featured-number">
                  <RichText.Content
                    tagName="span"
                    className="featured-number__number"
                    value={item.number}
                  />
                  <RichText.Content
                    tagName="p"
                    className="featured-number__text"
                    value={item.body}
                  />
                </div>
              ))}
            </div>
          </div>
          <br />
        </Fragment>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
