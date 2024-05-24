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
      } = attributes;

      return (
        <div className="about-page__entry">
          <div className="infobox-wrapper">
            <Tooltip text={__('Thêm')}>
							<span onClick={this.addNewItem2}>
								<Dashicon icon="plus-alt" />
							</span>
            </Tooltip>
            {contentItems.map((item, index) => (
              <div key={index} className="infobox">
                <div className="infobox__media">
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
                </div>
                <div className="infobox__body">
                  <RichText
                    tagName="h3"
                    className="infobox__title"
                    value={item.name}
                    onChange={(value) => this.updateItem({ name: value }, index)}
                    placeholder={__('Nhập tên...')}
                  />
                  <RichText
                    tagName="span"
                    className="infobox__subtitle"
                    value={item.title}
                    onChange={(value) => this.updateItem({ title: value }, index)}
                    placeholder={__('Nhập tên...')}
                  />
                  <RichText
                    tagName="p"
                    className="infobox__text"
                    value={item.body}
                    onChange={(value) => this.updateItem({ body: value }, index)}
                    placeholder={__('Nhập tên...')}
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
          image: '',
          name: 'THS. Thân Hiền Anh',
          title: 'Chủ tịch Hội đồng Thành viên',
          body: __(
            'Thành lập thêm 05 công ty thành viên (CTTV) nâng tổng số CTTV lên 70 CTTV tại 63 tỉnh/thành, là công ty bảo hiểm nhân thọ duy nhất trên thị trường sở hữu một mạng lưới CTTV vững chắc rộng khắp trên toàn quốc. Tổng Công ty Bảo Việt Nhân thọ cũng đã tăng vốn điều lệ lên 2.500 tỷ đồng, trở thành doanh nghiệp đứng đầu về quy mô vốn điều lệ trong lĩnh vực bảo hiểm nhân thọ tại Việt Nam'),
        },
      ],
    },
  };

  registerBlockType('cgb/block-gioithieu-thanhvien', {
    title: __('Giới thiệu - Thành viên'),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('gioi thieu'), __('Giới thiệu - Thành viên'), __('bv gioi thieu')],
    attributes: tabBlockAttrs,
    edit: GTGiaiThuong,
    save: function({ attributes }) {
      const {
        contentItems,
      } = attributes;

      return (
        <div className="about-page__entry">
          <div className="infobox-wrapper">
            {contentItems.map((item, index) => (
              <div key={index} className="infobox">
                <div className="infobox__media">
                  {item.image && (
                    <a href={item.url}><img src={item.image} alt={item.name} /></a>
                  )}
                </div>
                <div className="infobox__body">
                  <RichText.Content
                    tagName="h3"
                    className="infobox__title"
                    value={item.name}
                  />
                  <RichText.Content
                    tagName="span"
                    className="infobox__subtitle"
                    value={item.title}
                  />
                  <RichText.Content
                    tagName="p"
                    className="infobox__text"
                    value={item.body}
                  />
                </div>
              </div>
            ))}
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
