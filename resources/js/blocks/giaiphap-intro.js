/**
 * BLOCK: bv-blocks
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
// import './style.scss';
// import './editor.scss';

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
registerBlockType( 'cgb/block-giaiphap-intro', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Giải pháp - intro' ), // Block title.
	icon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'widgets', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'giai phap - intro' ),
		__( 'bv giaiphap' ),
	],

	attributes: {
		title: {
			type: 'string',
			source: 'html',
			selector: '.title__title',
			default: 'Người ta thường nói "Con hơn cha, nhà có phúc". Hẳn bạn cũng luôn mong con có được một tương lai tươi sáng.<strong> Điều đó bắt đầu từ chính sự chuẩn bị chu đáo của bạn hôm nay.</strong>',
		},
		content: {
			type: 'array',
			source: 'children',
			selector: '.title__text',
			default: 'Chúng tôi cam kết đồng hành cùng bạn qua từng giai đoạn như bắt đầu một gia đình, mua nhà, lập kế hoạch chuyến đi, lập kế hoạch cho chặng đường dài phía trước.',
		},
		tthiTitle: {
			type: 'array',
			source: 'children',
			selector: '.section-title-min',
			default: 'Thông tin hữu ích',
		},
		number1: {
			type: 'array',
			source: 'children',
			selector: '.featured-number__number1',
			default: '15%',
		},
		number1Desc: {
			type: 'array',
			source: 'children',
			selector: '.featured-number__text1',
			default: 'Từ 2012 -2013 số lượng du học sinh Việt Nam tăng lên 15%',
		},
		number2: {
			type: 'array',
			source: 'children',
			selector: '.featured-number__number2',
			default: '90%',
		},
		number2Desc: {
			type: 'array',
			source: 'children',
			selector: '.featured-number__text2',
			default: 'Có đến 90% du học sinh Việt Nam tự túc',
		},
		number3: {
			type: 'array',
			source: 'children',
			selector: '.featured-number__number3',
			default: '31%',
		},
		number3Desc: {
			type: 'array',
			source: 'children',
			selector: '.featured-number__text3',
			default: 'đang yêu cầu bắt buộc tuyển dụng bằng tiếng Anh',
		},
		number4: {
			type: 'array',
			source: 'children',
			selector: '.featured-number__number4',
			default: '66%',
		},
		number4Desc: {
			type: 'array',
			source: 'children',
			selector: '.featured-number__text4',
			default: 'Là mức tăng học phí ước tính cho đến 2021',
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
	edit: function( props ) {
		const {
			attributes: {
				title,
				content,
				tthiTitle,
				number1,
				number1Desc,
				number2,
				number2Desc,
				number3,
				number3Desc,
				number4,
				number4Desc,
			},
			setAttributes,
			className,
		} = props;

		const valueUpdater = ( field ) => {
			return ( value ) => setAttributes( { [ field ]: value } );
		};

		// const onChangeAlignment = ( newAlignment ) => {
		// 	setAttributes( { alignment: newAlignment === undefined ? 'none' : newAlignment } );
		// };

		return (
			<div className={ className + ' container' }>
				{
					<BlockControls>
					</BlockControls>
				}
				<div className="row">
					<div className="col-lg-9 ">
						<div className="title title__title-page">
							<RichText
								tagName="div"
								onChange={ valueUpdater( 'title' ) }
								className="title__title"
								value={ title }
							/>
							<RichText
								tagName="p"
								onChange={ valueUpdater( 'content' ) }
								className="title__text"
								value={ content }
							/>
						</div>
					</div>
				</div>
				<div className="mt-20">
					<RichText
						tagName="h2"
						onChange={ valueUpdater( 'tthiTitle' ) }
						className="section-title-min"
						value={ tthiTitle }
					/>
				</div>
				<div className="row row-eq-height">
					<div className="col-xs-6 col-sm-6 col-md-6 col-lg-3 ">
						<div className="featured-number featured-number__style-03">
							<RichText
								tagName="span"
								onChange={ valueUpdater( 'number1' ) }
								className="featured-number__number featured-number__number1"
								value={ number1 }
							/>
							<RichText
								tagName="p"
								onChange={ valueUpdater( 'number1Desc' ) }
								className="featured-number__text featured-number__text1"
								value={ number1Desc }
							/>
						</div>
					</div>
					<div className="col-xs-6 col-sm-6 col-md-6 col-lg-3 ">
						<div className="featured-number featured-number__style-03">
							<RichText
								tagName="span"
								onChange={ valueUpdater( 'number2' ) }
								className="featured-number__number featured-number__number2"
								value={ number2 }
							/>
							<RichText
								tagName="p"
								onChange={ valueUpdater( 'number2Desc' ) }
								className="featured-number__text featured-number__text2"
								value={ number2Desc }
							/>
						</div>
					</div>
					<div className="col-xs-6 col-sm-6 col-md-6 col-lg-3 ">
						<div className="featured-number featured-number__style-03">
							<RichText
								tagName="span"
								onChange={ valueUpdater( 'number3' ) }
								className="featured-number__number featured-number__number3"
								value={ number3 }
							/>
							<RichText
								tagName="p"
								onChange={ valueUpdater( 'number3Desc' ) }
								className="featured-number__text featured-number__text3"
								value={ number3Desc }
							/>
						</div>
					</div>
					<div className="col-xs-6 col-sm-6 col-md-6 col-lg-3 ">
						<div className="featured-number featured-number__style-03">
							<RichText
								tagName="span"
								onChange={ valueUpdater( 'number4' ) }
								className="featured-number__number featured-number__number4"
								value={ number4 }
							/>
							<RichText
								tagName="p"
								onChange={ valueUpdater( 'number4Desc' ) }
								className="featured-number__text featured-number__text4"
								value={ number4Desc }
							/>
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
	save: function( props ) {
		const {
			attributes: {
				title,
				content,
				tthiTitle,
				number1,
				number1Desc,
				number2,
				number2Desc,
				number3,
				number3Desc,
				number4,
				number4Desc,
			},
			className,
		} = props;

		return (
			<div className={ className + ' container' }>
				<div className="row">
					<div className="col-lg-9 ">
						<div className="title title__title-page">
							<RichText.Content
								tagName="div"
								className="title__title"
								value={ title }
							/>
							<RichText.Content
								tagName="p"
								className="title__text"
								value={ content }
							/>
						</div>
					</div>
				</div>
				<div className="mt-20">
					<RichText.Content
						tagName="h2"
						className="section-title-min"
						value={ tthiTitle }
					/>
				</div>
				<div className="row row-eq-height">
					<div className="col-xs-6 col-sm-6 col-md-6 col-lg-3 ">
						<div className="featured-number featured-number__style-03">
							<RichText.Content
								tagName="span"
								className="featured-number__number featured-number__number1"
								value={ number1 }
							/>
							<RichText.Content
								tagName="p"
								className="featured-number__text featured-number__text1"
								value={ number1Desc }
							/>
						</div>
					</div>
					<div className="col-xs-6 col-sm-6 col-md-6 col-lg-3 ">
						<div className="featured-number featured-number__style-03">
							<RichText.Content
								tagName="span"
								className="featured-number__number featured-number__number2"
								value={ number2 }
							/>
							<RichText.Content
								tagName="p"
								className="featured-number__text featured-number__text2"
								value={ number2Desc }
							/>
						</div>
					</div>
					<div className="col-xs-6 col-sm-6 col-md-6 col-lg-3 ">
						<div className="featured-number featured-number__style-03">
							<RichText.Content
								tagName="span"
								className="featured-number__number featured-number__number3"
								value={ number3 }
							/>
							<RichText.Content
								tagName="p"
								className="featured-number__text featured-number__text3"
								value={ number3Desc }
							/>
						</div>
					</div>
					<div className="col-xs-6 col-sm-6 col-md-6 col-lg-3 ">
						<div className="featured-number featured-number__style-03">
							<RichText.Content
								tagName="span"
								className="featured-number__number featured-number__number4"
								value={ number4 }
							/>
							<RichText.Content
								tagName="p"
								className="featured-number__text featured-number__text4"
								value={ number4Desc }
							/>
						</div>
					</div>
				</div>
			</div>
		);
	},
} );
