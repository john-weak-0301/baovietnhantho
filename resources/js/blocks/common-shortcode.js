(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { registerBlockType } = wpBlocks;
  const { Component, Fragment } = wpElement;
  const { PanelBody, SelectControl } = wpComponents;
  const { RichText, InspectorControls } = wpEditor;

  class CMShortCode extends Component {
    valueUpdater = (field) => {
      const { setAttributes } = this.props;

      return (value) => setAttributes({ [field]: value });
    };

    render() {
      const { attributes } = this.props;

      const {
        title,
        headingTag,
        shortcode,
      } = attributes;

      const headingSelections = [
        { label: 'H2', value: 'H2' },
        { label: 'H3', value: 'H3' },
        { label: 'H4', value: 'H4' },
        { label: 'div', value: 'div' },
      ];

      return (
        <Fragment>
          <InspectorControls>
            <PanelBody title={__('Settings')}>
              <SelectControl
                label="Heading"
                value={headingTag}
                options={headingSelections}
                onChange={this.valueUpdater('headingTag')}
              />
            </PanelBody>
          </InspectorControls>

          <RichText
            tagName="div"
            className="title__title"
            value={title}
            onChange={this.valueUpdater('title')}
            placeholder={__('Nhập tiêu đề...')}
          />

          <RichText
            tagName="div"
            value={shortcode}
            onChange={this.valueUpdater('shortcode')}
            placeholder={__('Nhập shortcode...')}
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
    title: {
      type: 'string',
      default: '',
    },
    headingTag: {
      type: 'string',
      default: 'h2',
    },
    shortcode: {
      type: 'string',
      default: '[]',
    },
  };

  registerBlockType('cgb/block-common-shortcode', {
    title: __('Chung - Shortcode'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: { src: tabsBlockIcon },
    category: 'widgets',
    keywords: [__('Common'), __('Chung - Shortcode'), __('bv chung')],
    attributes: tabBlockAttrs,
    edit: CMShortCode,
    save: function({ attributes }) {
      const {
        title,
        headingTag,
        shortcode,
      } = attributes;

      return (
        <Fragment>
          {title && (
            <div className="row">
              <div className="col-lg-9 ">
                <div className="title title__title-page">
                  <RichText.Content
                    tagName={headingTag}
                    className="title__title"
                    value={title}
                  />
                </div>
              </div>
            </div>
          )}

          <RichText.Content value={shortcode} />
        </Fragment>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
