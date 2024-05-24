(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component, Fragment } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { URLInput, RichText, MediaUpload, InspectorControls } = wpEditor;
  const { Dashicon, Button, Tooltip, IconButton } = wpComponents;

  class SPLapKeHoach extends Component {
    componentDidUpdate() {
      // const { contentItems: prevItems } = prevProps.attributes;
      const { contentItems } = this.props.attributes;

      if (contentItems.length === 0) {
        this.props.setAttributes({
          contentItems: [
            {
              title: 'Tiêu đề',
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
    }

    addNewItem = () => {
      const { attributes, setAttributes } = this.props;
      const { contentItems } = attributes;

      setAttributes({
        contentItems: [
          ...contentItems,
          { url: '#', icon: '', body: __('') },
        ],
      });
    };

    addNewItem2 = () => {
      const { attributes, setAttributes } = this.props;
      const { contentItems } = attributes;

      setAttributes({
        contentItems: [
          { url: '#', icon: '', body: __('') },
          ...contentItems,
        ],
      });
    };

    valueUpdater = (field) => {
      const { setAttributes } = this.props;
      return (value) => setAttributes({ [field]: value });
    };

    onSelectImage = (media) => {
      const { setAttributes } = this.props;
      setAttributes({
        mediaURL: media.sizes.full.url,
      });
    };

    render() {
      const { attributes, setAttributes, isSelected } = this.props;
      const {
        contentItems,
        title,
        mediaURL,
      } = attributes;

      return (
        <div className="display-flex-solution">
          <div className="solution-icon">
            <RichText
              tagName="h2"
              className="solution-icon__title"
              value={title}
              onChange={this.valueUpdater('title')}
              placeholder={__('Nhập tiêu đề...')}
            />
            <ul className="solution-icon__list">
              <Tooltip text={__('Thêm')}>
                  <span onClick={this.addNewItem2}>
                    <Dashicon icon="plus-alt" />
                  </span>
              </Tooltip>
              {contentItems.map((item, index) => (
                <li key={index} className="solution-icon__icon">
                  <a href={item.url}>
                    <MediaUpload
                      onSelect={(media) => this.updateItem({ icon: media.url, iconId: media.id },
                        index)}
                      allowedTypes="image"
                      value={item.iconId}
                      render={({ open }) => (
                        <Button className={item.iconId
                          ? 'image-button'
                          : 'button button-large'} onClick={open}>
                          {!item.iconId ? __('Chọn icon', 'gutenberg-examples') :
                            <img src={item.icon} alt="" />}
                        </Button>
                      )}
                    />
                    <RichText
                      tagName="h3"
                      value={item.body}
                      onChange={(value) => this.updateItem({ body: value }, index)}
                      placeholder={__('Nhập nội dung...')}
                    />
                  </a>
                  {isSelected && (
                    <form
                      className="block-library-button__inline-link"
                      onSubmit={(event) => event.preventDefault()}>
                      <Dashicon icon="admin-links" />
                      <URLInput
                        value={item.url}
                        onChange={(value) => this.updateItem({ url: value }, index)}
                      />
                      <IconButton icon="editor-break" label={__('Apply')} type="submit" />
                    </form>
                  )}
                  <Tooltip text={__('Xoá')}>
                      <span
                        onClick={() => setAttributes({
                          contentItems: contentItems.filter((vl, idx) => idx !== index),
                        })}
                      >
                        <Dashicon icon="no" />
                      </span>
                  </Tooltip>
                </li>
              ))}

              <Tooltip text={__('Thêm')}>
                  <span onClick={this.addNewItem}>
                    <Dashicon icon="plus-alt" />
                  </span>
              </Tooltip>
            </ul>
          </div>
          <div className="row">
            <div className="col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-3 ">
              [LKHTC]
            </div>
          </div>
        </div>
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
    title: {
      type: 'string',
      default: 'Giải pháp cho bạn',
    },
    mediaURL: {
      type: 'string',
      default: 'null',
    },
    contentItems: {
      type: 'array',
      default: [
        {
          url: '#',
          iconId: null,
          icon: '/img/svg/iconbox1-light.svg',
          body: 'Chăm sóc sức khoẻ',
        },
        {
          url: '#',
          iconId: null,
          icon: '/img/svg/iconbox2-light.svg',
          body: 'Vượt qua biến cố<br>bệnh tật',
        },
        {
          url: '#',
          iconId: null,
          icon: '/img/svg/iconbox3-light.svg',
          body: 'Bảo vệ người<br> trụ cột',
        },
        {
          url: '#',
          iconId: null,
          icon: '/img/svg/iconbox4-light.svg',
          body: 'Tương lai cho<br>con trẻ',
        },
        {
          url: '#',
          iconId: null,
          icon: '/img/svg/iconbox5-light.svg',
          body: 'Tạo dựng tài sản',
        },
        {
          url: '#',
          iconId: null,
          icon: '/img/svg/iconbox6-light.svg',
          body: 'Chuẩn bị giai đoạn<br>hưu trí',
        },
      ],
    },
  };

  registerBlockType('cgb/block-sanpham-lapkehoachtaichinh', {
    title: __('Sản phẩm - Lập kế hoạch tài chính'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('San pham'), __('Sản phẩm - Lập kế hoạch tài chính'), __('bv san pham')],
    attributes: tabBlockAttrs,
    edit: SPLapKeHoach,
    save: function({ attributes }) {
      const {
        contentItems,
        title,
        mediaURL,
      } = attributes;

      return (
        <div className="display-flex-solution">
          <div className="solution-icon">
            <RichText.Content
              tagName="h2"
              className="solution-icon__title"
              value={title}
            />
            <ul className="solution-icon__list">
              {contentItems.map((item, index) => (
                <li key={index} className="solution-icon__icon">
                  <a href={item.url}>
                    {item.icon && (
                      <img src={item.icon} alt={item.body} />
                    )}

                    <RichText.Content
                      tagName="h3"
                      value={item.body}
                    />
                  </a>
                </li>
              ))}
            </ul>
          </div>
          <div className="row">
            <div className="col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-3 ">
              [LKHTC]
            </div>
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
