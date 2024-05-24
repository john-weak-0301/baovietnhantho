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
	URLInput,
	RichText,
	InspectorControls,
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
registerBlockType( 'cgb/block-giaiphap-khuyenmai', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Giải pháp - chương trình khuyến mãi' ), // Block title.
	icon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'widgets', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'giai phap - khuyen mai' ),
		__( 'bv giaiphap' ),
	],

	attributes: {
		title: {
			type: 'array',
			source: 'children',
			selector: '.section-title-min',
			default: 'Chương trình khuyến mãi',
		},
		mediaURL: {
			type: 'string',
			default: 'null',
		},
		ctaTitle: {
			type: 'array',
			source: 'children',
			selector: '.cta__title',
			default: 'Trọn đời yêu thương',
		},
		content: {
			type: 'array',
			source: 'children',
			selector: '.cta__text',
			default: 'Bảo vệ cả gia đình chỉ trong một hợp đồng',
		},
		url1: {
			type: 'string',
			source: 'attribute',
			selector: 'a.btn-secondary',
			attribute: 'href',
			default: '#',
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
				mediaID,
				mediaURL,
				ctaTitle,
				content,
				url1,
			},
			setAttributes,
			isSelected,
			className,
		} = props;

		const onSelectImage = ( media ) => {
			setAttributes( {
				mediaURL: media.sizes.full.url,
			} );
		};

		const valueUpdater = ( field ) => {
			return ( value ) => setAttributes( { [ field ]: value } );
		};

		const style = {
			backgroundImage: `url(${ mediaURL })`,
		};

		return ([
			<InspectorControls>
				<MediaUpload
					onSelect={ onSelectImage }
					type="image"
					value={mediaURL} // make sure you destructured backgroundImage from props.attributes!
					render={({ open }) => (
						<Button onClick={open} className='button button-large'>
							Chọn ảnh nền
						</Button>
					)}
				/>
			</InspectorControls>,
			<div className={ className + ' container' }>
				<div className="row">
					<div className="col-lg-5">
						<RichText
							tagName="h2"
							onChange={ valueUpdater( 'title' ) }
							className="section-title-min"
							value={ title }
						/>
					</div>
				</div>
				<div className="cta" style={ style }>
					<div className="row">
						<div className="col-lg-5">
							<RichText
								tagName="h3"
								onChange={ valueUpdater( 'ctaTitle' ) }
								className="cta__title"
								value={ ctaTitle }
							/>
							<RichText
								tagName="p"
								onChange={ valueUpdater( 'content' ) }
								className="cta__text"
								value={ content }
							/>
							<a className="btn btn-secondary" href={ url1 }>Tìm hiểu</a>
							{ isSelected && (
								<form
									className="block-library-button__inline-link"
									onSubmit={ ( event ) => event.preventDefault() }>
									<Dashicon icon="admin-links" />
									<URLInput
										value={ url1 }
										onChange={ valueUpdater( 'url1' ) }
									/>
									<IconButton icon="editor-break" label={ __( 'Apply' ) } type="submit" />
								</form>
							) }
						</div>
					</div>
				</div>
			</div>
		]);
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
				mediaURL,
				ctaTitle,
				content,
				url1,
			},
			className,
		} = props;

		const style = {
			backgroundImage: `url(${ mediaURL })`,
		};

		return (
			<div className={ className + ' container' }>
				<div className="row">
					<div className="col-lg-5">
						<RichText.Content
							tagName="h2"
							className="section-title-min"
							value={ title }
						/>
					</div>
				</div>
				<div className="cta" style={ style }>
					<div className="row">
						<div className="col-lg-5">
							<RichText.Content
								tagName="h3"
								className="cta__title"
								value={ ctaTitle }
							/>
							<RichText.Content
								tagName="p"
								className="cta__text"
								value={ content }
							/>
							<a className="btn btn-secondary" href={ url1 }>Tìm hiểu</a>
						</div>
					</div>
				</div>
			</div>
		);
	},
} );
