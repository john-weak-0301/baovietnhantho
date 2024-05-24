(function(wpI18n, wpBlocks, wpElement, wpEditor) {
  const { __ } = wpI18n;
  const { Component, Fragment } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { RichText } = wpEditor;

  class CMIntro extends Component {
    valueUpdater = (field) => {
      const { setAttributes } = this.props;
      return (value) => setAttributes({ [field]: value });
    };

    render() {
      const { attributes } = this.props;
      const {
        desc,
      } = attributes;

      return (
        <Fragment>
          <RichText
            tagName="p"
            className="text-featured"
            value={desc}
            onChange={this.valueUpdater('desc')}
            placeholder={__('Nhập nội dung...')}
          />
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
    desc: {
      type: 'string',
      default: '',
    },
  };

  registerBlockType('cgb/block-tintuc-featuredtext', {
    title: __('Tin tức - chữ nổi bật'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('tin tuc'), __('Tin tức - Featured text'), __('bv tin tuc')],
    attributes: tabBlockAttrs,
    edit: CMIntro,
    save: function({ attributes }) {
      const {
        desc,
      } = attributes;

      return (
        <Fragment>
          <RichText.Content
            tagName="p"
            className="text-featured"
            value={desc}
          />
        </Fragment>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
