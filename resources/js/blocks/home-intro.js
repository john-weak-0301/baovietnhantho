/**
 * BLOCK: bv-blocks
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const {
  RichText,
  BlockControls,
} = wp.editor;

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
registerBlockType('cgb/block-home-intro', {
  // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
  title: __('Home - intro'), // Block title.
  icon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
  category: 'widgets', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  keywords: [
    __('Home - intro'),
    __('bv home'),
  ],

  attributes: {
    title: {
      type: 'string',
      source: 'html',
      selector: '.title__title',
      default: 'Cho dù bạn đang bắt đầu cuộc sống hay chuẩn bị tăng quỹ hưu trí, Bảo Việt có các lựa chọn bảo hiểm và đầu tư được thiết kế để giúp bạn đạt được mục tiêu của mình <strong>- cho ngày hôm nay và tương lai viên mãn.</strong>',
    },
    content: {
      type: 'array',
      source: 'children',
      selector: '.title__text',
      default: 'Chúng tôi cam kết đồng hành cùng bạn qua từng giai đoạn như bắt đầu một gia đình, mua nhà, lập kế hoạch chuyến đi, lập kế hoạch cho chặng đường dài phía trước.',
    },
    sppbTitle: {
      type: 'array',
      source: 'children',
      selector: '.section-title-min',
      default: 'Sản phẩm phổ biến nhất',
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
        content,
        sppbTitle,
      },
      setAttributes,
      className,
    } = props;

    const valueUpdater = (field) => {
      return (value) => setAttributes({ [field]: value });
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
        <div className="row">
          <div className="col-lg-9">
            <div className="title title__title-page">
              <RichText
                tagName="div"
                onChange={valueUpdater('title')}
                className="title__title"
                value={title}
              />
              <RichText
                tagName="p"
                onChange={valueUpdater('content')}
                className="title__text"
                value={content}
              />
            </div>
          </div>
        </div>
        <div className="mt-20">
          <RichText
            tagName="h2"
            onChange={valueUpdater('sppbTitle')}
            className="section-title-min"
            value={sppbTitle}
          />
        </div>
        <div className="row row-eq-height">
          [SPPB]
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
      attributes: {
        title,
        content,
        sppbTitle,
      },
      className,
    } = props;

    return (
      <div className={className + ' container'}>
        <div className="row">
          <div className="col-lg-9">
            <div className="title title__title-page">
              <RichText.Content tagName="div" className="title__title" value={title} />
              <RichText.Content tagName="p" className="title__text" value={content} />
            </div>
          </div>
        </div>
        <div className="mt-20">
          <RichText.Content tagName="h2" className="section-title-min" value={sppbTitle} />
        </div>
        <div className="row row-eq-height">
          [SPPB]
        </div>
      </div>
    );
  },
});
