(function(wpI18n, wpBlocks, wpElement, wpEditor) {
  const { __ } = wpI18n;
  const { Component } = wpElement;
  const { registerBlockType } = wpBlocks;
  const { InnerBlocks } = wpEditor;

  class CMShortCode extends Component {
    valueUpdater = (field) => {
      const { setAttributes } = this.props;
      return (value) => setAttributes({ [field]: value });
    };

    render() {
      return (
        <div className="row">
          <div className="col-md-8 col-lg-8">
            <InnerBlocks />
          </div>

          <div className="col-md-4 col-lg-3 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1">
            <div className="layout-sidebar">[GTVCT]</div>
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

  registerBlockType('cgb/block-layout-gioithieu', {
    title: __('Layout - Giới thiệu'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'layout',
    keywords: [__('Layout'), __('Layout - Giới thiệu'), __('bv layout')],
    attributes: {},
    edit: CMShortCode,
    save: function() {
      return (
        <div className="container">
          <div className="layout">
            <div className="row">
              <div className="col-md-8 col-lg-8 ">
                <InnerBlocks.Content />
              </div>
              <div className="col-md-4 col-lg-3 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1 ">
                <div className="layout-sidebar">[GTVCT]</div>
              </div>
            </div>
          </div>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
