(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { URLInput, RichText } = wpEditor;
  const { Dashicon, Tooltip, IconButton } = wpComponents;

  class SPQuyenLoi2 extends Component {
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
          { icon: '', body: __('') },
        ],
      });
    };

    addNewItem2 = () => {
      const { attributes, setAttributes } = this.props;
      const { contentItems } = attributes;

      setAttributes({
        contentItems: [
          { icon: '', body: __('') },
          ...contentItems,
        ],
      });
    };

    valueUpdater = (field) => {
      const { setAttributes } = this.props;
      return (value) => setAttributes({ [field]: value });
    };

    render() {
      const { attributes, setAttributes, isSelected } = this.props;
      const {
        contentItems,
        title,
        desc,
        btnText,
        url,
      } = attributes;

      return (
        <div className="pt-30" id="sp-quyenloi">
          <RichText
            tagName="h2"
            className="section-title-min"
            value={title}
            onChange={this.valueUpdater('title')}
            placeholder={__('Nhập tiêu đề...')}
          />
          <div className="row">
            <div className="col-md-7">
              <RichText
                tagName="p"
                className="checklist__text"
                value={desc}
                onChange={this.valueUpdater('desc')}
                placeholder={__('Nhập mô tả...')}
              />
            </div>
          </div>
          <div className="ac-accordion" data-options="{}">
            <Tooltip text={__('Thêm')}>
							<span onClick={this.addNewItem2}>
								<Dashicon icon="plus-alt" />
							</span>
            </Tooltip>
            {contentItems.map((item, index) => (
              <div key={index} className="ac-accordion__panel">
                <div className="ac-accordion__header">
                  <h3 className="ac-accordion__title">
                    <RichText
                      tagName="a"
                      href="#"
                      value={item.title}
                      onChange={(value) => this.updateItem({ title: value }, index)}
                      placeholder={__('Nhập tiêu đề...')}
                    />
                  </h3>
                </div>
                <div className="ac-accordion__body">
                  <RichText
                    tagName="p"
                    value={item.body}
                    onChange={(value) => this.updateItem({ body: value }, index)}
                    placeholder={__('Nhập nội dung...')}
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
              </div>
            ))}
            <Tooltip text={__('Thêm')}>
							<span onClick={this.addNewItem}>
								<Dashicon icon="plus-alt" />
							</span>
            </Tooltip>
          </div>
          <div className="pt-30">
            <RichText
              tagName="a"
              className="btn btn-secondary"
              href={url}
              value={btnText}
              onChange={this.valueUpdater('btnText')}
            />
            {isSelected && (
              <form
                className="block-library-button__inline-link"
                onSubmit={(event) => event.preventDefault()}>
                <Dashicon icon="admin-links" />
                <URLInput
                  value={url}
                  onChange={this.valueUpdater('url')}
                />
                <IconButton icon="editor-break" label={__('Apply')} type="submit" />
              </form>
            )}
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
    contentItems: {
      type: 'array',
      default: [
        {
          title: 'Bảo vệ',
          body: __(''),
        },
        {
          title: 'Đầu tư tiết kiệm',
          body: __(''),
        },
        {
          title: 'Linh hoạt',
          body: __(''),
        },
        {
          title: 'Gia đình',
          body: __(''),
        },
      ],
    },
    title: {
      type: 'string',
      default: 'Bạn được quyền lợi gì',
    },
    desc: {
      type: 'string',
      default: '',
    },
    url: {
      type: 'string',
      default: '#',
    },
    btnText: {
      type: 'string',
      default: 'Tải về tài liệu',
    },
  };

  registerBlockType('cgb/block-sanpham-quyenloi2', {
    title: __('Sản phẩm - Quyền lợi 2'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('San pham'), __('Sản phẩm - Quyen loi 2'), __('bv san pham')],
    attributes: tabBlockAttrs,
    edit: SPQuyenLoi2,
    save: function({ attributes }) {
      const {
        contentItems,
        title,
        desc,
        btnText,
        url,
      } = attributes;

      return (
        <div className="pt-30" id="sp-quyenloi">
          <RichText.Content
            tagName="h2"
            className="section-title-min"
            value={title}
          />
          <div className="row">
            <div className="col-md-7">
              <RichText.Content
                tagName="p"
                className="checklist__text"
                value={desc}
              />
            </div>
          </div>
          <div className="row">
            <div className="col-md-9">
              <div className="ac-accordion" data-options="{}">
                {contentItems.map((item, index) => (
                  <div key={index} className="ac-accordion__panel">
                    <div className="ac-accordion__header">
                      <h6 className="ac-accordion__title">
                        <RichText.Content
                          tagName="a"
                          href="#"
                          value={item.title}
                        />
                      </h6>
                    </div>
                    <div className="ac-accordion__body">
                      <RichText.Content
                        tagName="p"
                        value={item.body}
                      />
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
          <div className="pt-30">
            <RichText.Content
              tagName="a"
              className="btn btn-secondary"
              target="_blank"
              href={url}
              value={btnText}
            />
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
