/**
 * BLOCK: bv-blocks
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const {
  URLInput,
  RichText,
  BlockControls,
  MediaUpload,
} = wp.editor;

const { Dashicon, Button, IconButton } = wp.components;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType('cgb/block-home-dichvukhachhang', {
  // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
  title: __('Home - Dịch vụ khách hàng'), // Block title.
  icon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
  category: 'widgets', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  keywords: [
    __('Home - Dịch vụ khách hàng'),
    __('Home - dich vu khach hang'),
    __('bv home'),
  ],

  attributes: {
    title: {
      type: 'string',
      source: 'html',
      selector: '.title__title',
      default: 'Dịch vụ khách hàng',
    },
    media1ID: {
      type: 'number',
    },
    media1URL: {
      type: 'string',
      source: 'attribute',
      selector: '.service1 img',
      attribute: 'src',
      default: 'https://via.placeholder.com/150',
    },
    btn_url1: {
      type: 'string',
      source: 'attribute',
      selector: '.service1 a',
      attribute: 'href',
      default: '#',
    },
    btn_text1: {
      type: 'array',
      source: 'children',
      selector: '.service1 a',
      default: 'Giải quyết quyền lợi bảo hiểm',
    },
    desc1: {
      type: 'array',
      source: 'children',
      selector: '.service1 .service__text',
      default: 'Hãy chuẩn bị cho bản thân và gia đình thân thương những giải pháp tài chính an toàn nhất',
    },
    media2ID: {
      type: 'number',
    },
    media2URL: {
      type: 'string',
      source: 'attribute',
      selector: '.service2 img',
      attribute: 'src',
      default: 'https://via.placeholder.com/250',
    },
    btn_url2: {
      type: 'string',
      source: 'attribute',
      selector: '.service2 a',
      attribute: 'href',
      default: '#',
    },
    btn_text2: {
      type: 'array',
      source: 'children',
      selector: '.service2 a',
      default: 'Thay đổi thông tin bảo hiểm',
    },
    desc2: {
      type: 'array',
      source: 'children',
      selector: '.service2 .service__text',
      default: 'Hãy chuẩn bị cho bản thân và gia đình thân thương những giải pháp tài chính an toàn nhất',
    },
    media3ID: {
      type: 'number',
    },
    media3URL: {
      type: 'string',
      source: 'attribute',
      selector: '.service3 img',
      attribute: 'src',
      default: 'https://via.placeholder.com/350',
    },
    btn_url3: {
      type: 'string',
      source: 'attribute',
      selector: '.service3 a',
      attribute: 'href',
      default: '#',
    },
    btn_text3: {
      type: 'array',
      source: 'children',
      selector: '.service3 a',
      default: 'Hướng dẫn đóng phí bảo hiểm',
    },
    desc3: {
      type: 'array',
      source: 'children',
      selector: '.service3 .service__text',
      default: 'Hãy chuẩn bị cho bản thân và gia đình thân thương những giải pháp tài chính an toàn nhất',
    },
  },

  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function(props) {
    const {
      attributes: {
        title,
        media1ID,
        media1URL,
        btn_url1,
        btn_text1,
        desc1,
        media2ID,
        media2URL,
        btn_url2,
        btn_text2,
        desc2,
        media3ID,
        media3URL,
        btn_url3,
        btn_text3,
        desc3,
      },
      setAttributes,
      isSelected,
      className,
    } = props;

    const valueUpdater = (field) => {
      return (value) => setAttributes({ [field]: value });
    };

    const onSelectImage1 = (media) => {
      setAttributes({
        media1URL: media.url,
        media1ID: media.id,
      });
    };

    const onSelectImage2 = (media) => {
      setAttributes({
        media2URL: media.url,
        media2ID: media.id,
      });
    };

    const onSelectImage3 = (media) => {
      setAttributes({
        media3URL: media.url,
        media3ID: media.id,
      });
    };

    // const onChangeAlignment = ( newAlignment ) => {
    // 	setAttributes( { alignment: newAlignment === undefined ? 'none' : newAlignment } );
    // };

    return (
      <div className={className + ' container'}>
        {
          <BlockControls>
          </BlockControls>
        }
        <div class="row">
          <div class="col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-3 ">
            <div class="title title__title-page md-text-center">
              <RichText tagName="div" className="title__title" value={props.attributes.title} onChange={valueUpdater(
                'title')} />
            </div>

          </div>
        </div>
        <div class="row row-eq-height">
          <div class="col-sm-6 col-md-4 col-lg-4 ">
            <div class="service service1">
              <div class="service__inner">
                <MediaUpload
                  onSelect={onSelectImage1}
                  allowedTypes="image"
                  value={media1ID}
                  render={({ open }) => (
                    <Button className={media1ID
                      ? 'image-button'
                      : 'button button-large'} onClick={open}>
                      {!media1ID ? __('Upload Image', 'gutenberg-examples') :
                        <div class="service__icon">
                          <img src={media1URL} alt={props.attributes.btn_text1} /></div>}
                    </Button>
                  )}
                />
                <h3 class="service__title">
                  <RichText
                    tagName="a"
                    href={props.attributes.btn_url1}
                    value={props.attributes.btn_text1}
                    onChange={valueUpdater('btn_text1')}
                  />
                  {isSelected && (
                    <form
                      className="block-library-button__inline-link"
                      onSubmit={(event) => event.preventDefault()}>
                      <Dashicon icon="admin-links" />
                      <URLInput
                        value={btn_url1}
                        onChange={valueUpdater('btn_url1')}
                      />
                      <IconButton icon="editor-break" label={__('Apply')} type="submit" />
                    </form>
                  )}
                </h3>
                <RichText tagName="p" className="service__text" value={props.attributes.desc1} onChange={valueUpdater(
                  'desc1')} />
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4 ">
            <div class="service service2">
              <div class="service__inner">
                <MediaUpload
                  onSelect={onSelectImage2}
                  allowedTypes="image"
                  value={media2ID}
                  render={({ open }) => (
                    <Button className={media2ID
                      ? 'image-button'
                      : 'button button-large'} onClick={open}>
                      {!media2ID ? __('Upload Image', 'gutenberg-examples') :
                        <div class="service__icon">
                          <img src={media2URL} alt={props.attributes.btn_text2} /></div>}
                    </Button>
                  )}
                />
                <h3 class="service__title">
                  <RichText
                    tagName="a"
                    href={props.attributes.btn_url2}
                    value={props.attributes.btn_text2}
                    onChange={valueUpdater('btn_text2')}
                  />
                  {isSelected && (
                    <form
                      className="block-library-button__inline-link"
                      onSubmit={(event) => event.preventDefault()}>
                      <Dashicon icon="admin-links" />
                      <URLInput
                        value={btn_url2}
                        onChange={valueUpdater('btn_url2')}
                      />
                      <IconButton icon="editor-break" label={__('Apply')} type="submit" />
                    </form>
                  )}
                </h3>
                <RichText tagName="p" className="service__text" value={props.attributes.desc2} onChange={valueUpdater(
                  'desc2')} />
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4 ">
            <div class="service service3">
              <div class="service__inner">
                <MediaUpload
                  onSelect={onSelectImage3}
                  allowedTypes="image"
                  value={media3ID}
                  render={({ open }) => (
                    <Button className={media3ID
                      ? 'image-button'
                      : 'button button-large'} onClick={open}>
                      {!media3ID ? __('Upload Image', 'gutenberg-examples') :
                        <div class="service__icon">
                          <img src={media3URL} alt={props.attributes.btn_text3} /></div>}
                    </Button>
                  )}
                />
                <h3 class="service__title">
                  <RichText
                    tagName="a"
                    href={props.attributes.btn_url3}
                    value={props.attributes.btn_text3}
                    onChange={valueUpdater('btn_text3')}
                  />
                  {isSelected && (
                    <form
                      className="block-library-button__inline-link"
                      onSubmit={(event) => event.preventDefault()}>
                      <Dashicon icon="admin-links" />
                      <URLInput
                        value={btn_url3}
                        onChange={valueUpdater('btn_url3')}
                      />
                      <IconButton icon="editor-break" label={__('Apply')} type="submit" />
                    </form>
                  )}
                </h3>
                <RichText tagName="p" className="service__text" value={props.attributes.desc3} onChange={valueUpdater(
                  'desc3')} />
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  },

  /**
   * The save function defines the way in which the different attributes should be combined
   * into the final markup, which is then serialized by Gutenberg into post_content.
   *
   * The "save" property must be specified and must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  save: function(props) {
    const {
      attributes,
      setAttributes,
      className,
    } = props;

    return (
      <div className={className + ' container'}>
        <div class="row">
          <div class="col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-3 ">
            <div class="title title__title-page md-text-center">
              <RichText.Content tagName="h2" className="title__title" value={props.attributes.title} />
            </div>
          </div>
        </div>
        <div class="row row-eq-height">
          <div class="col-sm-6 col-md-4 col-lg-4 ">
            <div class="service service1">
              <div class="service__inner">
                {props.attributes.media1URL && (
                  <div class="service__icon">
                    <img src={props.attributes.media1URL} alt={props.attributes.btn_text1} />
                  </div>
                )}
                <h3 class="service__title">
                  <RichText.Content
                    tagName="a"
                    href={props.attributes.btn_url1}
                    value={props.attributes.btn_text1}
                  />
                </h3>
                <RichText.Content tagName="p" className="service__text" value={props.attributes.desc1} />
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4 ">
            <div class="service service2">
              <div class="service__inner">
                {props.attributes.media2URL && (
                  <div class="service__icon">
                    <img src={props.attributes.media2URL} alt={props.attributes.btn_text2} />
                  </div>
                )}
                <h3 class="service__title">
                  <RichText.Content
                    tagName="a"
                    href={props.attributes.btn_url2}
                    value={props.attributes.btn_text2}
                  />
                </h3>
                <RichText.Content tagName="p" className="service__text" value={props.attributes.desc2} />
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4 ">
            <div class="service service3">
              <div class="service__inner">
                {props.attributes.media3URL && (
                  <div class="service__icon">
                    <img src={props.attributes.media3URL} alt={props.attributes.btn_text3} />
                  </div>
                )}
                <h3 class="service__title">
                  <RichText.Content
                    tagName="a"
                    href={props.attributes.btn_url3}
                    value={props.attributes.btn_text3}
                  />
                </h3>
                <RichText.Content tagName="p" className="service__text" value={props.attributes.desc3} />
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  },
});
