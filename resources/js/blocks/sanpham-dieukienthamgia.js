(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component, Fragment } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { URLInput, RichText } = wpEditor;
  const { Dashicon, Tooltip, IconButton } = wpComponents;

  class SPDieuKien extends Component {
    componentDidUpdate() {
      const { contentItems, contentItems2 } = this.props.attributes;

      if (contentItems.length === 0) {
        this.props.setAttributes({
          contentItems: [
            {
              fileURL: '',
              fileText: 'Cần có ít nhất 1 item.',
            },
          ],
        });
      }
      if (contentItems2.length === 0) {
        this.props.setAttributes({
          contentItems2: [
            {
              header: 'Tiêu đề',
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

    updateItem2(value, index) {
      const { attributes, setAttributes } = this.props;
      const { contentItems2 } = attributes;

      const newItems = contentItems2.map((item, thisIndex) => {
        if (index === thisIndex) {
          item = { ...item, ...value };
        }

        return item;
      });

      setAttributes({ contentItems2: newItems });
    }

    addNewItem2 = () => {
      const { attributes, setAttributes } = this.props;
      const { contentItems2 } = attributes;

      setAttributes({
        contentItems2: [
          ...contentItems2,
          { icon: '', body: __('') },
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
        contentItems2,
        title,
        title2,
      } = attributes;

      return (
        <Fragment>
          <RichText
            tagName="h2"
            className="title-detail-min"
            value={title}
            onChange={this.valueUpdater('title')}
            placeholder={__('Nhập tiêu đề...')}
          />
          <div className="row">
            <div className="col-sm-8 col-md-8 col-lg-8 ">
              <table className="table-condition">
                <tbody>
                  {contentItems2.map((item, index) => (
                    <tr key={index}>
                      <td>
                        <RichText
                          value={item.header}
                          onChange={(value) => this.updateItem2({ header: value }, index)}
                          placeholder={__('Nhập tiêu đề')}
                        />
                        <Tooltip text={__('Xoá')}>
													<span
                            onClick={() => setAttributes({
                              contentItems2: contentItems2.filter((vl, idx) => idx !== index),
                            })}
                          >
														<Dashicon icon="no" />
													</span>
                        </Tooltip>
                      </td>
                      <td data-title={item.header}>
                        <RichText
                          value={item.body}
                          onChange={(value) => this.updateItem2({ body: value }, index)}
                          placeholder={__('Nhập tiêu đề')}
                        />
                      </td>
                    </tr>
                  ))}
                  <Tooltip text={__('Thêm')}>
										<span onClick={this.addNewItem2}>
											<Dashicon icon="plus-alt" />
										</span>
                  </Tooltip>
                </tbody>
              </table>
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
    contentItems2: {
      type: 'widgets',
      default: [
        {
          header: '',
          body: '',
        },
      ],
    },
    title: {
      type: 'string',
      default: 'Điều kiện tham gia',
    },
    title2: {
      type: 'string',
      default: 'Tài liệu và biểu mẫu',
    },
  };

  registerBlockType('cgb/block-sanpham-dieukienthamgia', {
    title: __('Sản phẩm - điều thện tham gia'),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('san pham'), __('Sản phẩm - điều thện tham gia'), __('bv san pham')],
    attributes: tabBlockAttrs,
    edit: SPDieuKien,
    save: function({ attributes }) {
      const {
        contentItems,
        contentItems2,
        title,
        title2,
      } = attributes;

      return (
        <Fragment>
          <RichText.Content
            tagName="h2"
            className="title-detail-min"
            value={title}
          />

          <div className="row">
            <div className="col-sm-8 col-md-8 col-lg-8 ">
              <table className="table-condition">
                <tbody>
                  {contentItems2.map((item, index) => (
                    <tr key={index}>
                      <td>
                        <RichText.Content
                          tagName="h3"
                          value={item.header}
                        />
                      </td>
                      <td data-title={item.header}>
                        <RichText.Content
                          value={item.body}
                        />
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
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
                      <RichText.Content
                        tagName="a"
                        value={'<i class="fa fa-file"></i>' + item.fileText}
                        target="_blank"
                        href={item.fileURL}
                      />
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
