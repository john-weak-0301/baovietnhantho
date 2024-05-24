(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const {__} = wpI18n;
  const {Component, Fragment} = wpElement;
  const {registerBlockType} = wpBlocks;
  const {InspectorControls, MediaUpload} = wpEditor;
  const {PanelBody, Button, TextControl} = wpComponents;

  class CMVideo extends Component {
    valueUpdater = (field) => {
      const {setAttributes} = this.props;
      return (value) => setAttributes({[field]: value});
    };

    onSelectImage = (media) => {
      const {setAttributes} = this.props;
      setAttributes({
        mediaURL: media.sizes.full.url,
      });
    };

    render() {
      const {attributes} = this.props;
      const {
        mediaURL,
        videoLink,
      } = attributes;

      return (
        <Fragment>
          <InspectorControls>
            <PanelBody title={__('Settings')}>
              <MediaUpload
                onSelect={this.onSelectImage}
                type="image"
                value={mediaURL} // make sure you destructured backgroundImage from props.attributes!
                render={({open}) => (
                  <Button onClick={open} className="button button-large">Chọn ảnh nền</Button>
                )}
              />

              <TextControl
                label="Video URL"
                value={videoLink}
                onChange={this.valueUpdater('videoLink')}
              />
            </PanelBody>
          </InspectorControls>
          <div className="video video-style2">
            <div className="video__bg" style={{backgroundImage: `url(${mediaURL})`, height: '350px'}}></div>
            <a className="video__btn" href={videoLink} data-init="magnificPopupVideo" data-effect="mfp-zoom-in"><i className="fa fa-play"></i></a>
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
    mediaURL: {
      type: 'string',
      default: 'assets/img/image/keangnam.jpg',
    },
    videoLink: {
      type: 'string',
      default: '',
    },
  };

  registerBlockType('cgb/block-common-video', {
    title: __('Chung - Video'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('Common'), __('Chung - Video'), __('bv chung')],
    attributes: tabBlockAttrs,
    edit: CMVideo,
    save: function({attributes}) {
      const {
        mediaURL,
        videoLink,
      } = attributes;

      return (
        <div className="video video-style2">
          <div className="video__bg" style={{backgroundImage: `url(${mediaURL})`}}></div>
          <a className="video__btn" href={videoLink} data-init="magnificPopupVideo" data-effect="mfp-zoom-in"><i className="fa fa-play"></i></a>
        </div>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
