(function(wpI18n, wpBlocks, wpElement, wpEditor) {
  const { __ } = wpI18n;
  const { Component } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { RichText } = wpEditor;

  class ListTinhNang extends Component {
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

    valueUpdater = (field) => {
      const { setAttributes } = this.props;
      return (value) => setAttributes({ [field]: value });
    };

    render() {
      const { attributes } = this.props;
      const {
        title,
        desc,
        btntext,
        phone,
        phone2,
        text1,
        text2,
        text2m,
      } = attributes;

      // const onSelectImage = ( media ) => {
      // 	setAttributes( {
      // 		mediaURL: media.url,
      // 		mediaID: media.id,
      // 	} );
      // };

      return (
        <div className="consultants-section">
          <div className="consultants-section__item">
            <div className="consultants-section__info">
              <div className="consultants-section__avatar">
                <img src="/img/image/avatarchuyenvientuvan.png" alt="Chuyên viên" /></div>
              <RichText
                tagName="div"
                className="consultants-section__title"
                value={title}
                onChange={this.valueUpdater('title')}
                placeholder={__('Nhập tiêu đề...')}
              />
              <RichText
                tagName="p"
                className="consultants-section__text"
                value={desc}
                onChange={this.valueUpdater('desc')}
                placeholder={__('Nhập mô tả...')}
              />
              <RichText
                tagName="a"
                className="btn btn-secondary"
                value={btntext}
                onChange={this.valueUpdater('btntext')}
                placeholder={__('Nhập mô tả...')}
              />
            </div>
          </div>
          <div className="consultants-section__item">
            <ul className="consultants-section__contact">
              <li className="first">
                <RichText
                  tagName="div"
                  value={phone}
                  onChange={this.valueUpdater('phone')}
                  placeholder={__('Nhập số điện thoại')}
                />
                <RichText
                  tagName="p"
                  className="text1"
                  value={text1}
                  onChange={this.valueUpdater('text1')}
                  placeholder={__('Nhập mô tả...')}
                />
                <RichText
                  tagName="p"
                  className="text2"
                  value={text2}
                  onChange={this.valueUpdater('text2')}
                  placeholder={__('Nhập mô tả...')}
                />
              </li>
              <li className="last">
                <RichText
                  tagName="div"
                  value={phone2}
                  onChange={this.valueUpdater('phone2')}
                  placeholder={__('Nhập số điện thoại (mobile)...')}
                />
                <RichText
                  tagName="p"
                  className="text2"
                  value={text2m}
                  onChange={this.valueUpdater('text2m')}
                  placeholder={__('Nhập mô tả...')}
                />
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
    title: {
      type: 'string',
      default: 'Chọn chuyên viên tư vấn cho riêng bạn',
    },
    desc: {
      type: 'string',
      default: 'Chủ động đặt hẹn với chuyên viên tài chính Bảo Việt để được lắng nghe và thấu hiểu',
    },
    btntext: {
      type: 'string',
      default: 'Bắt đầu ngay',
    },
    phone: {
      type: 'string',
      default: '1900 558899',
    },
    text1: {
      type: 'string',
      default: 'Tổng đài chăm sóc khách hàng',
    },
    text2: {
      type: 'string',
      default: 'Mọi thắc mắc của bạn được chuyên gia giải đáp một cách thấu đáo nhất.Hãy liên lạc cho chúng tôi',
    },
    text2m: {
      type: 'string',
      default: '* Miễn phí với số điện thoại đã đăng ký trên hợp đồng Bảo Việt Nhân thọ',
    },
    phone2: {
      type: 'string',
      default: '1800 558899',
    },
  };

  registerBlockType('cgb/block-home-chonchuyenvien', {
    title: __('Home - Chọn chuyên viên tư vấn'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('Home'), __('Home - Chon chuyen vien'), __('bv home')],
    attributes: tabBlockAttrs,
    edit: ListTinhNang,
    save: function({ attributes }) {
      const {
        title,
        desc,
        btntext,
        phone,
        phone2,
        text1,
        text2,
        text2m,
      } = attributes;

      return (
        <div className="consultants-section">
          <div className="consultants-section__item">
            <div className="consultants-section__info">
              <div className="consultants-section__avatar">
                <img src="/img/image/avatarchuyenvientuvan.png" alt="Chuyên viên" />
              </div>
              <RichText.Content
                tagName="div"
                className="consultants-section__title"
                value={title}
              />
              <RichText.Content
                tagName="p"
                className="consultants-section__text"
                value={desc}
              />
              <RichText.Content
                tagName="a"
                href={'/chuyen-vien'}
                className="btn btn-secondary"
                value={btntext}
              />
            </div>
          </div>
          <div className="consultants-section__item">
            <ul className="consultants-section__contact">
              <li className="first">
                <RichText.Content
                  tagName="div"
                  value={phone}
                />
                <RichText.Content
                  tagName="p"
                  className="text1"
                  value={text1}
                />
                <RichText.Content
                  tagName="p"
                  className="text2"
                  value={text2}
                />
              </li>
              <li className="last">
                <RichText.Content
                  tagName="div"
                  value={phone2}
                />
                <RichText.Content
                  tagName="p"
                  className="text2"
                  value={text2m}
                />
              </li>
            </ul>
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
