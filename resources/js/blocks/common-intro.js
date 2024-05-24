import classnames from 'classnames';

(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const {__} = wpI18n;
  const {Component, Fragment} = wpElement;
  const {registerBlockType} = wpBlocks;
  const {RichText, InspectorControls, PanelColorSettings} = wpEditor;
  const {PanelBody, SelectControl, ToggleControl} = wpComponents;

  class CMIntro extends Component {
    valueUpdater = (field) => {
      const {setAttributes} = this.props;
      return (value) => setAttributes({[field]: value});
    };

    render() {
      const {attributes, setAttributes} = this.props;
      const {
        colorMain,
        colorHighLight,
        colorText,
        secClass,
        title,
        desc,
        fluidContainer,
      } = attributes;

      const listBorderStyles = [
        {label: __('Không tuỳ biến'), value: ''},
        {label: __('Top 0'), value: 'pt-0'},
        {label: __('Bottom 0'), value: 'pb-0'},
        {label: __('Top & Bottom 0'), value: 'pt-0 pb-0'},
      ];

      const onChangeFluidContainer = (value) => {
        this.props.setAttributes({fluidContainer: !!value});
      };

      const wrapClass = classnames({
        'col-lg-9': !fluidContainer,
        'col-lg-12': !!fluidContainer,
      });

      return (
        <Fragment>
          <style>
            {`
						.title__title strong {
							color: ${colorHighLight};
						}
					`}
          </style>
          <InspectorControls>
            <PanelBody title={__('Khoảng cách')}>
              <ToggleControl
                key="togglecontrol"
                label={__('Full Container')}
                checked={!!fluidContainer}
                onChange={onChangeFluidContainer}
              />

              <SelectControl
                label={__('Khoảng cách')}
                value={secClass}
                options={listBorderStyles}
                onChange={this.valueUpdater('secClass')}
              />
            </PanelBody>

            <PanelColorSettings
              title={__('Mầu chữ')}
              initialOpen={false}
              colorSettings={[
                {
                  label: __('Chọn mầu chữ tiêu đề'),
                  value: colorMain,
                  onChange: (value) => setAttributes({colorMain: value}),
                },
                {
                  label: __('Chọn mầu chữ nổi bật'),
                  value: colorHighLight,
                  onChange: (value) => setAttributes({colorHighLight: value}),
                },
                {
                  label: __('Chọn mầu chữ nội dung'),
                  value: colorText,
                  onChange: (value) => setAttributes({colorText: value}),
                },
              ]}
            />
          </InspectorControls>

          <div className="row">
            <div className={wrapClass}>
              <div className="title title__title-page">
                <RichText
                  tagName="div"
                  className="title__title"
                  style={{color: colorMain}}
                  value={title}
                  onChange={this.valueUpdater('title')}
                  placeholder={__('Nhập tiêu đề...')}
                />

                <RichText
                  tagName="p"
                  className="title__text"
                  style={{color: colorText}}
                  value={desc}
                  onChange={this.valueUpdater('desc')}
                  placeholder={__('Nhập nội dung...')}
                />
              </div>
            </div>
          </div>
        </Fragment>
      );
    }
  }

  const tabsBlockIcon = (
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
      <path fill="none" d="M0,0h24v24H0V0z"/>
      <path fill="none" d="M0,0h24v24H0V0z"/>
      <path d="M21,3H3C1.9,3,1,3.9,1,5v14c0,1.1,0.9,2,2,2h18c1.1,0,2-0.9,2-2V5C23,3.9,22.1,3,21,3z M21,19H3V5h10v4h8V19z"/>
    </svg>
  );

  const tabBlockAttrs = {
    fluidContainer: {
      type: 'boolean',
      default: false,
    },
    secClass: {
      type: 'string',
      default: '',
    },
    colorMain: {
      type: 'string',
      default: '#153976',
    },
    colorHighLight: {
      type: 'string',
      default: '#dda307',
    },
    colorText: {
      type: 'string',
      default: null,
    },
    title: {
      type: 'string',
      default: 'Cho dù bạn đang bắt đầu cuộc sống hay chuẩn bị tăng quỹ hưu trí, Bảo Việt có các lựa chọn bảo hiểm và đầu tư được thiết kế để giúp bạn đạt được mục tiêu của mình<strong> — cho ngày hôm nay và tương lai viên mãn.</strong>',
    },
    desc: {
      type: 'string',
      default: 'Chúng tôi cam kết đồng hành cùng bạn qua từng giai đoạn như bắt đầu một gia đình, mua nhà, lập kế hoạch chuyến đi, lập kế hoạch cho chặng đường dài phía trước.',
    },
  };

  registerBlockType('cgb/block-common-intro', {
    title: __('Chung - Intro'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('Common'), __('Chung - Intro'), __('bv chung')],
    attributes: tabBlockAttrs,
    edit: CMIntro,
    save: function({attributes}) {
      const {
        colorMain,
        colorHighLight,
        colorText,
        secClass,
        title,
        desc,
        fluidContainer,
      } = attributes;

      const wrapClass = classnames({
        'col-lg-9': !fluidContainer,
        'col-lg-12': !!fluidContainer,
      });

      return (
        <div className="row">
          <style>
            {`
						.title__title strong {
							color: ${colorHighLight};
						}
					`}
          </style>
          <div className={wrapClass}>
            <div className="title title__title-page">
              <RichText.Content
                tagName="div"
                className="title__title"
                style={{color: colorMain}}
                value={title}
              />

              <RichText.Content
                tagName="p"
                className="title__text"
                style={{color: colorText}}
                value={desc}
              />
            </div>
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
