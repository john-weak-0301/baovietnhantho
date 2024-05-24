(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component, Fragment } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { RichText, MediaUpload } = wpEditor;
  const { Dashicon, Tooltip, Button } = wpComponents;

  class SPBoTro extends Component {
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
    }

    addNewItem = () => {
      const { attributes, setAttributes } = this.props;
      const { contentItems } = attributes;

      setAttributes({
        contentItems: [
          ...contentItems,
          { title: 'aaaaa', body: __('') },
        ],
      });
    };

    addNewItem2 = () => {
      const { attributes, setAttributes } = this.props;
      const { contentItems } = attributes;

      setAttributes({
        contentItems: [
          { title: 'aaaaa', body: __('') },
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
        title,
      } = attributes;

      return (
        <div className="tabs__module" data-options="{}">
          <div className="ac-tab__nav-wrapper">
            <RichText
              tagName="h2"
              className="section-title-min"
              value={title}
              onChange={this.valueUpdater('title')}
              placeholder={__('Nhập tiêu đề...')}
            />
            <ul className="ac-tab__nav clearfix">
              <li>
                <Tooltip text={__('Thêm')}>
									<span onClick={this.addNewItem2}>
										<Dashicon icon="plus-alt" />
									</span>
                </Tooltip>
              </li>
              {contentItems.map((item, index) => (
                <li key={index}>
                  <a href={`#`}>
                    <RichText
                      tagName="span"
                      className="ac-tab__title"
                      value={item.title}
                      onChange={(value) => this.updateItem({ title: value }, index)}
                      placeholder={__('Nhập tiêu đề...')}
                    />
                  </a>
                  <div className="ac-tab__panel" id={`tab${index}`}>
                    <RichText
                      tagName="p"
                      value={item.body1}
                      onChange={(value) => this.updateItem({ body1: value }, index)}
                      placeholder={__('Nhập mô tả...')}
                    />
                    <MediaUpload
                      onSelect={(media) => this.updateItem({ image: media.url, imageId: media.id },
                        index)}
                      allowedTypes="image"
                      value={item.imageId}
                      render={({ open }) => (
                        <Button className={item.imageId
                          ? 'image-button'
                          : 'button button-large'} onClick={open}>
                          {!item.imageId ? __('Chọn ảnh', 'gutenberg-examples') :
                            <img src={item.image} alt="" />}
                        </Button>
                      )}
                    />
                    <RichText
                      tagName="p"
                      value={item.body2}
                      onChange={(value) => this.updateItem({ body2: value }, index)}
                      placeholder={__('Nhập mô tả...')}
                    />
                  </div>
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
              <li>
                <Tooltip text={__('Thêm')}>
									<span onClick={this.addNewItem}>
										<Dashicon icon="plus-alt" />
									</span>
                </Tooltip>
              </li>
            </ul>
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
    bgColor: {
      type: 'string',
      default: '#eff3f3',
    },
    contentItems: {
      type: 'array',
      default: [
        {
          title: 'Quyền lợi học vấn cho con – Dành cho con MÔI TRƯỜNG PHÁT TRIỂN TỐT NHẤT, một nền giáo dục tốt nhất',
          body1: __(''),
          imageId: '',
          image: '',
          body2: __(''),
        },
        {
          title: 'Quyền lợi Học bổng Bảo Việt vinh danh',
          body1: __(''),
          imageId: '',
          image: '',
          body2: __(''),
        },
        {
          title: 'Quyền lợi bảo vệ toàn diện nhất cho cha mẹ và con cái trong cùng Hợp đồng bảo hiểm',
          body1: __(''),
          imageId: '',
          image: '',
          body2: __(''),
        },
      ],
    },

    title: {
      type: 'string',
      default: 'Minh hoạ quyền lợi',
    },
  };

  registerBlockType('cgb/block-sanpham-minhhoaquyenloi', {
    title: __('Sản phẩm - Minh hoạ quyền lợi'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'common',
    keywords: [__('San pham'), __('Sản phẩm - Minh hoạ quyền lợi'), __('bv san pham')],
    attributes: tabBlockAttrs,
    edit: SPBoTro,
    save: function({ attributes }) {
      const {
        contentItems,
        title,
      } = attributes;

      return (
        <div className="tabs__module" data-options="{}">
          <div className="ac-tab__nav-wrapper">
            <RichText.Content
              tagName="h2"
              className="section-title-min"
              value={title}
            />
            <ul className="ac-tab__nav clearfix">
              {contentItems.map((item, index) => (
                <li key={index}>
                  <a href={`#tab${index}`}>
                    <RichText.Content
                      tagName="span"
                      className="ac-tab__title"
                      value={item.title}
                    />
                  </a>
                </li>
              ))}
            </ul>
          </div>
          <div className="ac-tab__content">
            {contentItems.map((item, index) => (
              <div key={index} className="ac-tab__panel" id={`tab${index}`}>
                <RichText.Content
                  tagName="p"
                  value={item.body1}
                />
                {item.image && (
                  <img src={item.image} alt={item.title} />
                )}
                <RichText.Content
                  tagName="p"
                  value={item.body2}
                />
              </div>
            ))}
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
