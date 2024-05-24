(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component, Fragment } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { RichText, InspectorControls, PanelColorSettings } = wpEditor;
  const { PanelBody, SelectControl } = wpComponents;

  class SanPhamForm extends Component {
    valueUpdater = (field) => {
      const { setAttributes } = this.props;
      return (value) => setAttributes({ [field]: value });
    };

    render() {
      const { attributes, setAttributes } = this.props;
      const {
        secClass,
        bgColor,
        title,
        chuyenVienTitle,
        chuyenVienText,
        chuyenVienBTN,
        hotlineText,
        hotlineNhanh,
        soHoTro,
        chuyenVienTextM1,
        chuyenVienTextM2,
      } = attributes;

      const sectionSkin = [
        { label: __('Chữ trên nền sáng'), value: '' },
        { label: __('Chữ trên nền tối'), value: 'md-skin-dark' },
      ];

      return (
        <div className="row">
          <div className="col-sm-6 col-md-6 col-lg-8 ">
            <div className="contact-form2">
              <div className="row">
                <div className="col-lg-9 ">
                  <RichText
                    tagName="div"
                    className="contact-form2__title"
                    value={title}
                    onChange={this.valueUpdater('title')}
                    placeholder={__('Nhập tiêu đề...')}
                  />
                </div>
              </div>
              [FORMLIENHE]
            </div>
            <div className="clearfix"></div>
          </div>
          <div className="col-sm-6 col-md-6 col-lg-4 ">

            <div className="consultants">
              <div className="consultants__avatar">
                <figure>
                  <img src="/img/image/avatarchuyenvientuvan.png" alt="chuyên viên tư vấn" />
                </figure>
              </div>
              <RichText
                tagName="div"
                className="consultants__title"
                value={chuyenVienTitle}
                onChange={this.valueUpdater('chuyenVienTitle')}
                placeholder={__('Nhập tiêu đề...')}
              />
              <RichText
                tagName="p"
                className="consultants__text"
                value={chuyenVienText}
                onChange={this.valueUpdater('chuyenVienText')}
                placeholder={__('Nhập tiêu đề...')}
              />
              <RichText
                tagName="a"
                className="btn btn-secondary"
                href="/chuyen-vien"
                value={chuyenVienBTN}
                onChange={this.valueUpdater('chuyenVienBTN')}
                placeholder={__('Nhập tiêu đề...')}
              />

              <div className="consultants__hotline">
                <RichText
                  tagName="small"
                  value={hotlineText}
                  onChange={this.valueUpdater('hotlineText')}
                  placeholder={__('Hotline')}
                />
                <a href="#">
                  <span>{soHoTro}</span>
                  <RichText
                    value={hotlineNhanh}
                    onChange={this.valueUpdater('hotlineNhanh')}
                    placeholder={__('Nhánh số 4')}
                  />
                </a>
              </div>
              <div className="consultants__mobile">
                <div className="h4">
                  <RichText
                    tagName="a"
                    className={`tel:${soHoTro}`}
                    href="/chuyen-vien"
                    value={soHoTro}
                    onChange={this.valueUpdater('soHoTro')}
                    placeholder={__('Nhập số điện thoại...')}
                  />
                </div>
                <RichText
                  tagName="p"
                  className="text1"
                  value={chuyenVienTextM1}
                  onChange={this.valueUpdater('chuyenVienTextM1')}
                  placeholder={__('Nhập mô tả...')}
                />
                <RichText
                  tagName="p"
                  className="text2"
                  value={chuyenVienTextM2}
                  onChange={this.valueUpdater('chuyenVienTextM2')}
                  placeholder={__('Nhập mô tả...')}
                />
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
    bgColor: {
      type: 'string',
      default: '#f2f2f2',
    },
    title: {
      type: 'string',
      default: 'Nếu bạn phân vân giữa các sản phẩm bảo hiểm, hãy để lại lời nhắn cho chúng tôi',
    },
    chuyenVienTitle: {
      type: 'string',
      default: 'Chọn chuyên viên tư vấn cho riêng bạn',
    },
    chuyenVienText: {
      type: 'string',
      default: 'Chủ động đặt hẹn với chuyên viên tài chính Bảo Việt để được lắng nghe và thấu hiểu',
    },
    chuyenVienBTN: {
      type: 'string',
      default: 'Bắt đầu ngay',
    },
    hotlineText: {
      type: 'string',
      default: 'Hotline',
    },
    hotlineNhanh: {
      type: 'string',
      default: 'Nhánh số 4',
    },
    soHoTro: {
      type: 'string',
      default: '1900 558899',
    },
    chuyenVienTextM1: {
      type: 'string',
      default: 'Tổng đài chăm sóc khách hàng',
    },
    chuyenVienTextM2: {
      type: 'string',
      default: 'Mọi thắc mắc của bạn được chuyên gia giải đáp một cách thấu đáo nhất.Hãy liên lạc cho chúng tôi',
    },

  };

  registerBlockType('cgb/block-sanpham-form', {
    title: __('Sản phẩm - Form'),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [
      __('San pham'),
      __('Sản phẩm - Form'),
      __('bv san pham'),
    ],
    attributes: tabBlockAttrs,
    edit: SanPhamForm,
    save: function({ attributes }) {
      const {
        secClass,
        bgColor,
        title,
        chuyenVienTitle,
        chuyenVienText,
        chuyenVienBTN,
        hotlineText,
        hotlineNhanh,
        soHoTro,
        chuyenVienTextM1,
        chuyenVienTextM2,
      } = attributes;

      return (
        <div className="row">
          <div className="col-sm-6 col-md-6 col-lg-8 ">
            <div className="contact-form2">
              <div className="row">
                <div className="col-lg-9 ">
                  <RichText.Content
                    tagName="div"
                    className="contact-form2__title"
                    value={title}
                  />
                </div>
              </div>
              [FORMLIENHE]
            </div>
            <div className="clearfix"></div>
          </div>
          <div className="col-sm-6 col-md-6 col-lg-4 ">

            <div className="consultants">
              <div className="consultants__avatar">
                <figure>
                  <img src="/img/image/avatarchuyenvientuvan.png" alt="chuyên viên tư vấn" />
                </figure>
              </div>
              <RichText.Content
                tagName="div"
                className="consultants__title"
                value={chuyenVienTitle}
              />
              <RichText.Content
                tagName="p"
                className="consultants__text"
                value={chuyenVienText}
              />
              <RichText.Content
                tagName="a"
                className="btn btn-secondary"
                href="/chuyen-vien"
                value={chuyenVienBTN}
              />

              <div className="consultants__hotline">
                <RichText.Content
                  tagName="small"
                  value={hotlineText}
                />
                <a href={`tel:${soHoTro}`}>
                  <span>{soHoTro}</span>
                  <RichText.Content
                    value={hotlineNhanh}
                  />
                </a>
              </div>
              <div className="consultants__mobile">
                <div className="h4">
                  <RichText.Content
                    tagName="a"
                    className={`tel:${soHoTro}`}
                    href="/chuyen-vien"
                    value={soHoTro}
                  />
                </div>
                <RichText.Content
                  tagName="p"
                  className="text1"
                  value={chuyenVienTextM1}
                />
                <RichText.Content
                  tagName="p"
                  className="text2"
                  value={chuyenVienTextM2}
                />
              </div>
            </div>

          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
