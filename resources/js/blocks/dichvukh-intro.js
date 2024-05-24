(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component, Fragment } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { URLInput, RichText } = wpEditor;
  const { Dashicon, Tooltip, IconButton, CheckboxControl } = wpComponents;

  class DVIntro extends Component {
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
        title2,
        text,
        button,
        buttonURL,
      } = attributes;

      return (
        <Fragment>
          <div className="row">
            <div className="col-sm-8 col-md-8 col-lg-8 ">
              <RichText
                tagName="h3"
                className="title-detail-min"
                value={title}
                onChange={this.valueUpdater('title')}
                placeholder={__('Nhập tiêu đề...')}
              />
              <RichText
                tagName="p"
                value={text}
                onChange={this.valueUpdater('text')}
                placeholder={__('Nhập nội dung...')}
              />
              <br />
              <RichText
                tagName="a"
                value={button}
                href={buttonURL}
                className="btn btn-secondary"
                onChange={this.valueUpdater('button')}
              />
              {isSelected && (
                <form
                  className="block-library-button__inline-link"
                  onSubmit={(event) => event.preventDefault()}>
                  <Dashicon icon="admin-links" />
                  <URLInput
                    value={buttonURL}
                    onChange={this.valueUpdater('buttonURL')}
                  />
                  {/* <CheckboxControl
										label="Open new tag"
									/> */}
                  <IconButton icon="editor-break" label={__('Apply')} type="submit" />
                </form>
              )}
              <br /><br />
            </div>
            <div className="col-sm-4 col-md-4 col-lg-3 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1">
              <RichText
                tagName="h3"
                className="title-detail-min"
                value={title2}
                onChange={this.valueUpdater('title2')}
                placeholder={__('Nhập tiêu đề...')}
              />
              <div className="widget">
                <ul className="widget-file">
                  <Tooltip text={__('Thêm')}>
										<span onClick={this.addNewItem2}>
											<Dashicon icon="plus-alt" />
										</span>
                  </Tooltip>
                  {contentItems.map((item, index) => (
                    <li key={index}>
                      <RichText
                        tagName="a"
                        value={item.fileText}
                        href={item.fileURL}
                        onChange={(value) => this.updateItem({ fileText: value }, index)}
                        placeholder={__('Nhập mô tả tài liệu...')}
                      />

                      {isSelected && (
                        <form
                          className="block-library-button__inline-link"
                          onSubmit={(event) => event.preventDefault()}>
                          <Dashicon icon="admin-links" />
                          <URLInput
                            value={item.fileURL}
                            onChange={(value) => this.updateItem({ fileURL: value }, index)}
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
            </div>
          </div>
          <br /><br /><br />
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
          fileURL: '',
          fileText: '',
        },
      ],
    },

    title: {
      type: 'string',
      default: 'Giải quyết quyền lợi bảo hiểm 15 phút',
    },
    title2: {
      type: 'string',
      default: 'Tài liệu và biểu mẫu',
    },
    text: {
      type: 'string',
      default: '',
    },
    button: {
      type: 'string',
      default: 'Gửi yêu cầu giải quyết',
    },
    buttonURL: {
      type: 'string',
      default: '#',
    },
  };

  registerBlockType('cgb/block-dichvu-intro', {
    title: __('Dịch vụ khách hàng - intro'),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'common',
    keywords: [__('dich vu'), __('Dịch vụ khách hàng - intro'), __('bv dich vu')],
    attributes: tabBlockAttrs,
    edit: DVIntro,
    save: function({ attributes }) {
      const {
        contentItems,
        title,
        title2,
        text,
        button,
        buttonURL,
      } = attributes;

      return (
        <Fragment>
          <div className="row">
            <div className="col-sm-8 col-md-8 col-lg-8">
              <RichText.Content
                tagName="h3"
                className="title-detail-min"
                value={title}
              />
              <RichText.Content
                tagName="p"
                value={text}
              />
              <br />
              <RichText.Content
                tagName="a"
                value={button}
                href={buttonURL}
                className="btn btn-secondary"
              />
            </div>

            <div className="col-sm-4 col-md-4 col-lg-3 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1">
              <RichText.Content
                tagName="div"
                className="title-detail-min"
                value={title2}
              />

              <div className="widget">
                <ul className="widget-file">
                  {contentItems.map((item, index) => (
                    <li key={index}>
                      <a href={item.fileURL} target={'_blank'}>
                        <i className="fa fa-file"></i>{item.fileText}
                      </a>
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>
        </Fragment>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
