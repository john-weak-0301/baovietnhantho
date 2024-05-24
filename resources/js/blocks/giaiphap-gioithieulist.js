(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { RichText, MediaUpload } = wpEditor;
  const { Dashicon, Button, Tooltip } = wpComponents;

  class ListTinhNang extends Component {
    componentDidMount() {
      // if (!this.props.attributes.blockID) {
      // 	this.props.setAttributes( { blockID: this.props.clientId } );
      // }
    }

    componentDidUpdate() {
      // const { tabItems: prevItems } = prevProps.attributes;
      const { tabItems } = this.props.attributes;

      if (tabItems.length === 0) {
        this.props.setAttributes({
          tabItems: [
            {
              header: 'Tab 1',
              body: 'Cần có ít nhất 1 item.',
            },
          ],
        });
      }
    }

    updateTabs(value, index) {
      const { attributes, setAttributes } = this.props;
      const { tabItems } = attributes;

      const newItems = tabItems.map((item, thisIndex) => {
        if (index === thisIndex) {
          item = { ...item, ...value };
        }

        return item;
      });

      setAttributes({ tabItems: newItems });
    }

    addNewItem = () => {
      const { attributes, setAttributes } = this.props;
      const { tabItems } = attributes;

      setAttributes({
        tabItems: [
          ...tabItems,
          { icon: '', body: __('') },
        ],
      });
    };

    addNewItem2 = () => {
      const { attributes, setAttributes } = this.props;
      const { tabItems } = attributes;

      setAttributes({
        tabItems: [
          { icon: '', body: __('') },
          ...tabItems,
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
        tabItems,
        title,
      } = attributes;

      // const onSelectImage = ( media ) => {
      // 	setAttributes( {
      // 		mediaURL: media.url,
      // 		mediaID: media.id,
      // 	} );
      // };

      return (
        <div className="container">
          <div className="row">
            <div className="col-lg-6 ">
              <RichText
                tagName="h2"
                className="section-title-min mb-50 text-sm-center"
                value={title}
                onChange={this.valueUpdater('title')}
                placeholder={__('Nhập tiêu đề...')}
              />
            </div>
          </div>
          <div className="row">
            <div className="col-lg-11">
              <div className="row row-eq-height">
                <Tooltip text={__('Thêm')}>
									<span onClick={this.addNewItem2}>
										<Dashicon icon="plus-alt" />
									</span>
                </Tooltip>
                {tabItems.map((item, index) => (
                  <div key={index} className="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xsx-12">
                    <div className={`iconbox iconbox-${index}`}>
                      <MediaUpload
                        onSelect={(media) => this.updateTabs({ icon: media.url, iconId: media.id }, index)}
                        allowedTypes="image"
                        value={item.iconId}
                        render={({ open }) => (
                          <Button className={item.iconId
                            ? 'image-button'
                            : 'button button-large'} onClick={open}>
                            {!item.iconId ? __('Chọn icon', 'gutenberg-examples') :
                              <span className="iconbox__icon"><img src={item.icon} alt="" /></span>}
                          </Button>
                        )}
                      />

                      <RichText
                        tagName="p"
                        className="iconbox__text"
                        value={item.body}
                        onChange={(value) => this.updateTabs({ body: value }, index)}
                        placeholder={__('Nhập nội dung...')}
                      />
                    </div>
                    <Tooltip text={__('Xoá')}>
											<span
                        onClick={() => setAttributes({
                          tabItems: tabItems.filter((vl, idx) => idx !== index),
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
    tabItems: {
      type: 'array',
      default: [
        {
          iconId: null,
          icon: '',
          body: __('Đưa thời gian dành riêng cho con vào trong kế hoạch hàng ngày'),
        },
        {
          iconId: null,
          icon: '',
          body: __('Thường xuyên trò chuyện để hiểu về sở thích và ước mơ của con'),
        },
        {
          iconId: null,
          icon: '',
          body: __('Khen ngợi và động viên khi con làm được việc tốt'),
        },
        {
          iconId: null,
          icon: '',
          body: __('Đọc sách cùng con trước khi ngủ'),
        },
      ],
    },

    title: {
      type: 'string',
      default: 'Dành thời gian cho con một cách hiệu quả',
    },
  };

  registerBlockType('cgb/block-giaiphap-gioithieulist', {
    title: __('Giải pháp - List tính năng'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('Giai phap'), __('Giải pháp - List tính năng'), __('bv giai phap')],
    attributes: tabBlockAttrs,
    edit: ListTinhNang,
    save: function({ attributes }) {
      const {
        tabItems,
        title,
      } = attributes;

      return (
        <div className="container">
          <div className="row">
            <div className="col-lg-6 ">
              <h2 className="section-title-min mb-50 text-sm-center">{title}</h2>
            </div>
          </div>
          <div className="row">
            <div className="col-lg-11">
              <div className="row row-eq-height">
                {tabItems.map((item, index) => (
                  <div key={index} className="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xsx-12">
                    <div className={`iconbox iconbox-${index}`}>
                      {item.icon && (
                        <span className="iconbox__icon"><img src={item.icon} alt="Giải pháp cho bạn" /></span>
                      )}
                      <RichText.Content
                        tagName="p"
                        className="iconbox__text"
                        value={item.body}
                      />
                    </div>
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
