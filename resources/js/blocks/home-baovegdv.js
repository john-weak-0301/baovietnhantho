(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { Component } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { URLInput, RichText, MediaUpload } = wpEditor;
  const { Dashicon, Button, IconButton } = wpComponents;

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
      const { attributes, setAttributes, isSelected } = this.props;
      const {
        tabItems,
        title,
        btnlink,
        btntext,
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
            <div className="col-xs-6 col-lg-2 col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-5 ">
              <img src="/img/image/logobaovegiadinh.png" alt="Bảo vệ gia đình" />
            </div>
          </div>
          <div className="row">
            <div className="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">

              <div className="title title__title-color md-text-center">
                <RichText
                  tagName="div"
                  className="title__title"
                  value={title}
                  onChange={this.valueUpdater('title')}
                  placeholder={__('Nhập tiêu đề...')}
                />
              </div>
            </div>
          </div>
          <div className="image-link-web">
            <div className="row">

              <div className="col-xs-6 col-lg-3">
                {tabItems.map((item, index) => (
                  <div key={index} className="image image__background no-overflow">
                    {isSelected && (
                      <form
                        className="block-library-button__inline-link"
                        onSubmit={(event) => event.preventDefault()}>
                        <Dashicon icon="admin-links" />
                        <URLInput
                          value={item.url}
                          onChange={(value) => this.updateTabs({ url: value },
                            index)}
                        />
                        <IconButton icon="editor-break" label={__(
                          'Apply')} type="submit" />
                      </form>
                    )}
                    <MediaUpload
                      onSelect={(media) => this.updateTabs(
                        { icon: media.url, iconId: media.id }, index)}
                      allowedTypes="image"
                      value={item.iconId}
                      render={({ open }) => (
                        <Button className={item.iconId
                          ? 'image-button'
                          : 'button button-large'} onClick={open}>
                          {!item.icon
                            ? __('Chọn icon', 'gutenberg-examples')
                            : <img src={item.icon} alt="" />}
                        </Button>
                      )}
                    />
                    <RichText
                      tagName="p"
                      className="image__caption"
                      value={item.title}
                      onChange={(value) => this.updateTabs({ title: value },
                        index)}
                      placeholder={__('Nhập nội dung...')}
                    />

                  </div>
                ))}
              </div>
            </div>
          </div>
          <RichText
            tagName="a"
            className="btn btn-secondary"
            href={btnlink}
            value={btntext}
            onChange={this.valueUpdater('btntext')}
            placeholder={__('Nhập tiêu đề...')}
          />
          {isSelected && (
            <form
              className="block-library-button__inline-link"
              onSubmit={(event) => event.preventDefault()}>
              <Dashicon icon="admin-links" />
              <URLInput
                value={btnlink}
                onChange={this.valueUpdater('btnlink')}
              />
              <IconButton icon="editor-break" label={__(
                'Apply')} type="submit" />
            </form>
          )}
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
          icon: '/img/image/infonew.jpg',
          title: __('Tập luyện'),
          url: '#',
        },
        {
          iconId: null,
          icon: '/img/image/infonew2.jpg',
          title: __('Cẩm nang sống khỏe'),
          url: '#',
        },
        {
          iconId: null,
          icon: '/img/image/infonew3.jpg',
          title: __('Bảo vệ cuộc sống'),
          url: '#',
        },
        {
          iconId: null,
          icon: '/img/image/infonew4.jpg',
          title: __('Phụ nữ 24h'),
          url: '#',
        },
      ],
    },

    title: {
      type: 'string',
      default: 'Trang cung cấp thông tin bảo hiểm,<br>sức khoẻ cho người Việt',
    },
    btntext: {
      type: 'string',
      default: 'Xem Trang Web',
    },
    btnlink: {
      type: 'string',
      default: '#',
    },
  };

  registerBlockType('cgb/block-home-baovegdv', {
    title: __('Home - Bảo vệ gia đình Việt'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('Home'), __('Home - Bao ve gia dinh viet'), __('bv home')],
    attributes: tabBlockAttrs,
    edit: ListTinhNang,
    save: function({ attributes }) {
      const {
        tabItems,
        title,
        btntext,
        btnlink,
      } = attributes;

      return (
        <div className="container">
          <div className="row">
            <div className="col-xs-6 col-lg-2 col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-5 ">
              <img src="/img/image/logobaovegiadinh.png" alt="Bảo vệ gia đình" />
            </div>
          </div>
          <div className="row">
            <div className="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">

              <div className="title title__title-color md-text-center">
                <RichText.Content
                  tagName="div"
                  className="title__title"
                  value={title}
                />
              </div>
            </div>
          </div>
          <div className="image-link-web">
            <div className="row">

              {tabItems.map((item, index) => (
                <div key={index} className="col-xs-6 col-lg-3">
                  <div className="image image__background">
                    <a className="image__image" href={item.url} style={{ 'backgroundImage': `url(${item.icon})` }}>
                      {item.icon && (
                        <img src={item.icon} alt={item.title} />
                      )}
                      <RichText.Content
                        tagName="span"
                        className="image__caption"
                        value={item.title}
                      />
                    </a>
                  </div>
                </div>
              ))}
            </div>
          </div>
          <RichText.Content
            tagName="a"
            className="btn btn-secondary"
            href={btnlink}
            value={btntext}
            target={'_blank'}
          />
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
