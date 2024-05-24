(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { URLInput, RichText, MediaUpload } = wpEditor;
  const { Dashicon, Tooltip, IconButton, Button } = wpComponents;

  class HomeAbout extends Component {
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

    valueUpdater2 = (field, value) => {
      const { setAttributes } = this.props;
      setAttributes({ [field]: value });
    };

    render() {
      const { attributes, setAttributes, isSelected } = this.props;
      const {
        contentItems,
        contentItems2,
        title,
        desc1,
        title2,
        title3,
        title4,
        tcImage,
        tcText,
        tcUrl,
        gtImage1,
        gtText1,
        gtUrl1,
        gtImage2,
        gtText2,
        gtUrl2,
      } = attributes;

      return (
        <div className="about-text">
          <div className="row row-eq-height">
            <div className="col-sm-7 col-md-8 col-lg-8 ">
              <div className="about-text__content">

                <div className="title title__style-02">
                  <RichText
                    tagName="div"
                    className="title__title"
                    value={title}
                    onChange={this.valueUpdater('title')}
                    placeholder={__('Nhập tiêu đề...')}
                  />
                  <RichText
                    tagName="p"
                    className="title__text"
                    value={desc1}
                    onChange={this.valueUpdater('desc1')}
                    placeholder={__('Nhập mô tả...')}
                  />
                </div>

                <div className="row">
                  <div className="col-sm-6 col-md-4 col-lg-4 ">
                    <div className="about-text__item">
                      <RichText
                        tagName="div"
                        className="about-text__titlemin"
                        value={title2}
                        onChange={this.valueUpdater('title2')}
                        placeholder={__('Nhập tiêu đề...')}
                      />
                      <ul className="about-text__list">
                        {contentItems.map((item, index) => (
                          <li key={index}>
                            <RichText
                              tagName="a"
                              value={item.fileText}
                              href={item.fileURL}
                              onChange={(value) => this.updateItem({ fileText: value }, index)}
                              placeholder={__('Nhập mô tả link...')}
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
                  <div className="col-sm-6 col-md-8 col-lg-7 ">
                    <div className="row">
                      <div className="col-xs-6 col-sm-12 col-md-6 col-lg-6  col-xsx-12">
                        <div className="about-text__item">
                          <RichText
                            tagName="div"
                            className="about-text__titlemin"
                            value={title3}
                            onChange={this.valueUpdater('title3')}
                            placeholder={__('Nhập tiêu đề...')}
                          />
                          <div className="row">
                            <div className="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">

                              <div className="report report__style-02">
                                <div className="report__media">
                                  <MediaUpload
                                    onSelect={(media) => this.valueUpdater2('tcImage', media.url)}
                                    allowedTypes="image"
                                    value={tcImage}
                                    render={({ open }) => (
                                      <Button className={tcImage
                                        ? 'image-button'
                                        : 'button button-large'} onClick={open}>
                                        {!tcImage ? __('Chọn ảnh', 'gutenberg-examples') :
                                          <img src={tcImage} alt="" />}
                                      </Button>
                                    )}
                                  />
                                </div>
                                <RichText
                                  tagName="div"
                                  className="report__title"
                                  value={tcText}
                                  onChange={this.valueUpdater('tcText')}
                                  placeholder={__('Nhập tiêu đề...')}
                                />
                                {isSelected && (
                                  <form
                                    className="block-library-button__inline-link"
                                    onSubmit={(event) => event.preventDefault()}>
                                    <Dashicon icon="admin-links" />
                                    <URLInput
                                      value={tcUrl}
                                      onChange={this.valueUpdater('tcUrl')}
                                    />
                                    <IconButton icon="editor-break" label={__(
                                      'Apply')} type="submit" />
                                  </form>
                                )}
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                      <div className="col-xs-6 col-sm-12 col-md-6 col-lg-6  col-xsx-12">
                        <div className="about-text__item">
                          <RichText
                            tagName="div"
                            className="about-text__titlemin"
                            value={title4}
                            onChange={this.valueUpdater('title4')}
                            placeholder={__('Nhập tiêu đề...')}
                          />
                          <div className="row">
                            <div className="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">

                              <div className="report report__style-02">
                                <div className="report__media">
                                  <MediaUpload
                                    onSelect={(media) => this.valueUpdater2('gtImage1', media.url)}
                                    allowedTypes="image"
                                    value={gtImage1}
                                    render={({ open }) => (
                                      <Button className={gtImage1
                                        ? 'image-button'
                                        : 'button button-large'} onClick={open}>
                                        {!gtImage1 ? __('Chọn ảnh', 'gutenberg-examples') :
                                          <img src={gtImage1} alt="" />}
                                      </Button>
                                    )}
                                  />
                                </div>
                                <RichText
                                  tagName="div"
                                  className="report__title"
                                  value={gtText1}
                                  onChange={this.valueUpdater('gtText1')}
                                  placeholder={__('Nhập tiêu đề...')}
                                />
                                {isSelected && (
                                  <form
                                    className="block-library-button__inline-link"
                                    onSubmit={(event) => event.preventDefault()}>
                                    <Dashicon icon="admin-links" />
                                    <URLInput
                                      value={gtUrl1}
                                      onChange={this.valueUpdater('gtUrl1')}
                                    />
                                    <IconButton icon="editor-break" label={__(
                                      'Apply')} type="submit" />
                                  </form>
                                )}
                              </div>

                            </div>
                            <div className="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">

                              <div className="report report__style-02">
                                <div className="report__media">
                                  <MediaUpload
                                    onSelect={(media) => this.valueUpdater2('gtImage2', media.url)}
                                    allowedTypes="image"
                                    value={gtImage2}
                                    render={({ open }) => (
                                      <Button className={gtImage2
                                        ? 'image-button'
                                        : 'button button-large'} onClick={open}>
                                        {!gtImage2 ? __('Chọn ảnh', 'gutenberg-examples') :
                                          <img src={gtImage2} alt="" />}
                                      </Button>
                                    )}
                                  />
                                </div>
                                <RichText
                                  tagName="div"
                                  className="report__title"
                                  value={gtText2}
                                  onChange={this.valueUpdater('gtText2')}
                                  placeholder={__('Nhập tiêu đề...')}
                                />
                                {isSelected && (
                                  <form
                                    className="block-library-button__inline-link"
                                    onSubmit={(event) => event.preventDefault()}>
                                    <Dashicon icon="admin-links" />
                                    <URLInput
                                      value={gtUrl2}
                                      onChange={this.valueUpdater('gtUrl2')}
                                    />
                                    <IconButton icon="editor-break" label={__(
                                      'Apply')} type="submit" />
                                  </form>
                                )}
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-sm-5 col-md-4 col-lg-4 ">
              <div className="about-text__sidebar">
                {contentItems2.map((item, index) => (
                  <div key={index} className="featured-number featured-number__style-02">
                    <RichText
                      tagName="span"
                      className="featured-number__number"
                      value={item.number}
                      onChange={(value) => this.updateItem2({ number: value }, index)}
                      placeholder={__('Nhập...')}
                    />
                    <RichText
                      tagName="p"
                      className="featured-number__text"
                      value={item.text}
                      onChange={(value) => this.updateItem2({ text: value }, index)}
                      placeholder={__('Nhập mô tả...')}
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
                  </div>
                ))}
                <Tooltip text={__('Thêm')}>
									<span onClick={this.addNewItem2}>
										<Dashicon icon="plus-alt" />
									</span>
                </Tooltip>
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
          fileURL: '#',
          fileText: 'Lịch sử phát triển',
        },
        {
          fileURL: '#',
          fileText: 'Tầm nhìn và sứ mệnh',
        },
        {
          fileURL: '#',
          fileText: 'Ban lãnh đạo',
        },
        {
          fileURL: '#',
          fileText: 'Giải thưởng và danh hiệu',
        },
        {
          fileURL: '#',
          fileText: 'Báo cáo tài chính',
        },
        {
          fileURL: '#',
          fileText: 'Hoạt động cộng đồng',
        },
      ],
    },
    contentItems2: {
      type: 'widgets',
      default: [
        {
          number: '32năm',
          text: 'Là đơn vị tiên phong trong lĩnh vực bảo hiểm nhân thọ tại Việt Nam',
        },
        {
          number: '2.500tỷ',
          text: 'Sự vững mạnh về nền tảng tài chính với số tổng vốn điều lệ',
        },
        {
          number: '300+',
          text: 'Điểm phục vụ là hệ thống mạng lưới phủ khắp 64 tỉnh thành',
        },
      ],
    },
    title: {
      type: 'string',
      default: 'Là doanh nghiệp phát hành hợp đồng bảo hiểm nhân thọ đầu tiên trên thị trường Việt Nam',
    },
    desc1: {
      type: 'string',
      default: 'Năm 1996, Bảo Việt Nhân thọ hiện giờ là một trong số ít các doanh nghiệp bảo hiểm nhân thọ Việt Nam có lịch sự hoạt động lâu năm và ngày càng khẳng định vị thế dẫn đầu thị trường.',
    },
    title2: {
      type: 'string',
      default: 'Giới thiệu về bảo việt',
    },
    title3: {
      type: 'string',
      default: 'Báo cáo tài chính',
    },
    tcImage: {
      type: 'string',
      default: '/img/image/book-pdf.jpg',
    },
    tcText: {
      type: 'string',
      default: 'Báo cáo thường niên 2018',
    },
    tcUrl: {
      type: 'string',
      default: '#',
    },
    title4: {
      type: 'string',
      default: 'Giải thưởng',
    },
    gtImage1: {
      type: 'string',
      default: '/img/image/giaithuong.jpg',
    },
    gtText1: {
      type: 'string',
      default: 'Công ty Bảo hiểm Nhân thọ Tốt nhất Việt Nam 2017',
    },
    gtUrl1: {
      type: 'string',
      default: '#',
    },
    gtImage2: {
      type: 'string',
      default: '/img/image/giaithuong2.jpg',
    },
    gtText2: {
      type: 'string',
      default: 'Top 500 doanh nghiệp lớn nhất Việt Nam',
    },
    gtUrl2: {
      type: 'string',
      default: '#',
    },
  };

  registerBlockType('cgb/block-home-about', {
    title: __('Home - about'),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('trang chu'), __('Home - about'), __('bv trang chu')],
    attributes: tabBlockAttrs,
    edit: HomeAbout,
    save: function({ attributes }) {
      const {
        contentItems,
        contentItems2,
        title,
        desc1,
        title2,
        title3,
        title4,
        tcImage,
        tcText,
        tcUrl,
        gtImage1,
        gtText1,
        gtUrl1,
        gtImage2,
        gtText2,
        gtUrl2,
      } = attributes;

      return (
        <div className="about-text">
          <div className="row row-eq-height">
            <div className="col-sm-7 col-md-8 col-lg-8 ">
              <div className="about-text__content">
                <div className="title title__style-02">
                  <RichText.Content
                    tagName="div"
                    className="title__title"
                    value={title}
                  />
                  <RichText.Content
                    tagName="p"
                    className="title__text"
                    value={desc1}
                  />
                </div>

                <div className="row">
                  <div className="col-sm-6 col-md-4 col-lg-4 ">
                    <div className="about-text__item">
                      <RichText.Content
                        tagName="div"
                        className="about-text__titlemin"
                        value={title2}
                      />
                      <ul className="about-text__list">
                        {contentItems.map((item, index) => (
                          <li key={index}>
                            <RichText.Content
                              tagName="a"
                              value={item.fileText}
                              href={item.fileURL}
                            />
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>
                  <div className="col-sm-6 col-md-8 col-lg-7 ">
                    <div className="row">
                      <div className="col-xs-6 col-sm-12 col-md-6 col-lg-6  col-xsx-12">
                        <div className="about-text__item">
                          <RichText.Content
                            tagName="div"
                            className="about-text__titlemin"
                            value={title3}
                          />
                          <div className="row">
                            <div className="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">

                              <div className="report report__style-02">
                                <div className="report__media">
                                  <a href={tcUrl}><img src={tcImage} alt={tcText} /></a>
                                </div>
                                <div className="report__title"><a href={tcUrl}>{tcText}</a></div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                      <div className="col-xs-6 col-sm-12 col-md-6 col-lg-6  col-xsx-12">
                        <div className="about-text__item">
                          <RichText.Content
                            tagName="div"
                            className="about-text__titlemin"
                            value={title4}
                          />
                          <div className="row">
                            <div className="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">

                              <div className="report report__style-02">
                                <div className="report__media">
                                  <a href={gtUrl1}><img src={gtImage1} alt={gtText1} /></a></div>
                                <div className="report__title"><a href={gtUrl1}>{gtText1}</a></div>
                              </div>

                            </div>
                            <div className="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">

                              <div className="report report__style-02">
                                <div className="report__media">
                                  <a href={gtUrl2}><img src={gtImage2} alt={gtText2} /></a></div>
                                <div className="report__title"><a href={gtUrl2}>{gtText2}</a></div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-sm-5 col-md-4 col-lg-4 ">
              <div className="about-text__sidebar">
                {contentItems2.map((item, index) => (
                  <div key={index} className="featured-number featured-number__style-02">
                    <RichText.Content
                      tagName="span"
                      className="featured-number__number"
                      value={item.number}
                    />
                    <RichText.Content
                      tagName="p"
                      className="featured-number__text"
                      value={item.text}
                    />
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
