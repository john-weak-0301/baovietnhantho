(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { URLInput, RichText, MediaUpload } = wpEditor;
  const { Dashicon, Button, Tooltip, IconButton } = wpComponents;

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
      const { attributes, setAttributes, isSelected } = this.props;
      const {
        contentItems,
        title,
        desc,
        btnText,
        btnUrl,
      } = attributes;

      return (
        <div className="container">
          <div className="row">
            <div className="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">
              <div className="title title__style-02 md-text-center gioithieu-fix-title">
                <RichText
                  tagName="h2"
                  className="title__title"
                  value={title}
                  onChange={this.valueUpdater('title')}
                  placeholder={__('Nhập tiêu đề...')}
                />
                <RichText
                  tagName="p"
                  className="title__text"
                  value={desc}
                  onChange={this.valueUpdater('desc')}
                  placeholder={__('Nhập mô tả...')}
                />
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1 ">
              <div className="report-pdf-slide">
                <Tooltip text={__('Thêm')}>
									<span onClick={this.addNewItem2}>
										<Dashicon icon="plus-alt" />
									</span>
                </Tooltip>
                {contentItems.map((item, index) => (
                  <div key={index} className="report">
                    <div className="report__media">
                      <MediaUpload
                        onSelect={(media) => this.updateItem(
                          { image: media.url, imageId: media.id }, index)}
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
                    </div>
                    <h4 className="report__title">
                      <RichText
                        tagName="a"
                        value={item.title}
                        href={item.url}
                        onChange={(value) => this.updateItem({ title: value }, index)}
                        placeholder={__('Nhập tiêu đề...')}
                      />
                    </h4>
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
                  </div>
                ))}
                <Tooltip text={__('Thêm')}>
									<span onClick={this.addNewItem}>
										<Dashicon icon="plus-alt" />
									</span>
                </Tooltip>
              </div>
              <div className="text-center mt-30">
                <RichText
                  tagName="a"
                  className="btn btn-secondary"
                  value={btnText}
                  href={btnUrl}
                  onChange={this.valueUpdater('btnText')}
                />
                {isSelected && (
                  <form
                    className="block-library-button__inline-link"
                    onSubmit={(event) => event.preventDefault()}>
                    <Dashicon icon="admin-links" />
                    <URLInput
                      value={btnUrl}
                      onChange={this.valueUpdater('btnUrl')}
                    />
                    <IconButton icon="editor-break" label={__('Apply')} type="submit" />
                  </form>
                )}
              </div>
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
    contentItems: {
      type: 'array',
      default: [
        {
          imageId: '',
          image: 'img/image/book-pdf.jpg',
          url: '#',
          title: __('Báo cáo thường niên 2018'),
        },
        {
          imageId: '',
          image: 'img/image/book-pdf2.jpg',
          url: '#',
          title: __('Báo cáo thường niên 2017'),
        },
        {
          imageId: '',
          image: 'img/image/book-pdf3.jpg',
          url: '#',
          title: __('Báo cáo thường niên 2016'),
        },
        {
          imageId: '',
          image: 'img/image/book-pdf4.jpg',
          url: '#',
          title: __('Báo cáo thường niên 2015'),
        },
        {
          imageId: '',
          image: 'img/image/book-pdf5.jpg',
          url: '#',
          title: __('Báo cáo thường niên 2014'),
        },
      ],
    },

    title: {
      type: 'string',
      default: 'Với những hoạt động tích cực mang lại giá trị thiết thực cho các khách hàng và cộng đồng',
    },
    desc: {
      type: 'string',
      default: 'Bảo Việt Nhân thọ vinh dự đón nhận giải thưởng “Công ty bảo hiểm nhân thọ vì Sức khỏe cộng đồng” do Tạp chí Tài chính và Ngân hàng toàn cầu uy tín của Anh Quốc (Global Banking and Finance Review) đánh giá và bình chọn.',
    },
    btnUrl: {
      type: 'string',
      default: '#',
    },
    btnText: {
      type: 'string',
      default: 'Chi tiết báo cáo tài chính',
    },
  };

  registerBlockType('cgb/block-gioithieu-baocaotaichinh', {
    title: __('Giới thiệu - Báo cáo tài chính'),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('gioi thieu'), __('Giới thiệu - Báo cáo tài chính'), __('bv gioi thieu')],
    attributes: tabBlockAttrs,
    edit: GTGiaiThuong,
    save: function({ attributes }) {
      const {
        contentItems,
        title,
        desc,
        btnText,
        btnUrl,
      } = attributes;

      return (
        <div className="container">
          <div className="row">
            <div className="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">
              <div className="title title__style-02 md-text-center gioithieu-fix-title">
                <RichText.Content
                  tagName="div"
                  className="title__title"
                  value={title}
                />
                <RichText.Content
                  tagName="p"
                  className="title__text"
                  value={desc}
                />
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1 ">
              <div className="report-pdf-slide">
                {contentItems.map((item, index) => (
                  <div key={index} className="report">
                    <div className="report__media">
                      {item.image && (
                        <a href={item.url}><img src={item.image} alt={item.title} /></a>
                      )}
                    </div>
                    <h4 className="report__title">
                      <RichText.Content
                        tagName="a"
                        value={item.title}
                        href={item.url}
                      />
                    </h4>
                  </div>
                ))}
              </div>
              <div className="text-center mt-30">
                <RichText.Content
                  tagName="a"
                  className="btn btn-secondary"
                  value={btnText}
                  href={btnUrl}
                />
              </div>
            </div>
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
