import Sortable from 'gutenberg-sortable';

(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { RichText, MediaUpload } = wpEditor;
  const { Dashicon, Button, Tooltip } = wpComponents;

  class GTGiaiThuong extends Component {
    componentDidUpdate() {
      // const { contentItems: prevItems } = prevProps.attributes;
      const { contentItems } = this.props.attributes;

      if (contentItems.length === 0) {
        this.props.setAttributes({
          contentItems: [
            {
              imageId: '',
              image: '/img/image/awards.jpg',
              title: __('Cần có ít nhất 1 item.'),
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
      const { attributes, setAttributes } = this.props;
      const {
        contentItems,
        title,
      } = attributes;

      return (
        <div className="container">
          <div className="row">
            <div className="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">
              <div className="text-center">
                <RichText
                  tagName="h2"
                  className="section-title-min mb-40"
                  value={title}
                  onChange={this.valueUpdater('title')}
                  placeholder={__('Nhập tiêu đề...')}
                />
              </div>
            </div>
          </div>
          <div className="row row-eq-height">
            <Tooltip text={__('Thêm')}>
							<span onClick={this.addNewItem2}>
								<Dashicon icon="plus-alt" />
							</span>
            </Tooltip>
            {contentItems.map((item, index) => (
              <div key={index} className="col-lg-3 ">
                <div className="awards">
                  <div className="awards__inner">
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
                      tagName="h3"
                      className="awards__title"
                      value={item.title}
                      onChange={(value) => this.updateItem({ title: value }, index)}
                      placeholder={__('Nhập tên giải thưởng...')}
                    />
                  </div>
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
          <h3>Sắp xếp thứ tự</h3>
          <ul>
            <Sortable
              items={contentItems}
              onSortEnd={(values) => setAttributes({ contentItems: values })}
            >
              {contentItems.map((item, index) => (
                <li key={index}>
                  <img src={item.image} alt="" width="70px" />
                </li>
              ))}
            </Sortable>
          </ul>
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
          imageId: '',
          image: '/img/image/awards.jpg',
          title: __('Công Ty Bảo Hiểm Nhân Thọ Tốt Nhất Việt Nam 2017'),
        },
        {
          imageId: '',
          image: '/img/image/awards-02.jpg',
          title: __('Vị Trí Số 1 Trong Các Doanh Nghiệp Bảo Hiểm Nộp Thuế Lớn Nhất Việt Nan'),
        },
        {
          imageId: '',
          image: '/img/image/awards-03.jpg',
          title: __('Top 500 Doanh Nghiệp Lớn Nhất Việt Nam'),
        },
        {
          imageId: '',
          image: '/img/image/awards-02.jpg',
          title: __('Thư Khen Của Chủ Tịch Nước'),
        },
      ],
    },

    title: {
      type: 'string',
      default: 'Giải thưởng và danh hiệu',
    },
  };

  registerBlockType('cgb/block-gioithieu-giaithuong', {
    title: __('Giới thiệu - Giải thưởng'),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('gioi thieu'), __('Gioi thieu - giai thuong'), __('bv gioi thieu')],
    attributes: tabBlockAttrs,
    edit: GTGiaiThuong,
    save: function({ attributes }) {
      const {
        contentItems,
        title,
      } = attributes;

      return (
        <div className="container">
          <div className="row">
            <div className="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">
              <div className="text-center">
                <RichText.Content
                  tagName="h2"
                  className="section-title-min mb-40"
                  value={title}
                />
              </div>
            </div>
          </div>
          <div className="row row-eq-height">
            {contentItems.map((item, index) => (
              <div key={index} className="col-lg-3 ">
                <div className="awards">
                  <div className="awards__inner">
                    {item.image && (
                      <img src={item.image} alt={item.title} />
                    )}

                    <RichText.Content
                      tagName="h3"
                      className="awards__title"
                      value={item.title}
                    />
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
