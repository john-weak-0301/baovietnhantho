( function( wpI18n, wpBlocks, wpElement, wpEditor, wpComponents ) {
	const { __ } = wpI18n;
	const { Component } = wpElement;
	const { registerBlockType } = wpBlocks;
	const { URLInput, RichText, MediaUpload } = wpEditor;
	const { Dashicon, Button, Tooltip, IconButton } = wpComponents;

	class GTBaoCaoTC2 extends Component {
		componentDidUpdate( ) {
			// const { contentItems: prevItems } = prevProps.attributes;
			const { contentItems } = this.props.attributes;

			if ( contentItems.length === 0 ) {
				this.props.setAttributes( {
					contentItems: [
						{
							imageId: '',
							image: '/img/image/awards.jpg',
							title: __( 'Cần có ít nhất 1 item.' ),
						},
					],
				} );
			}
		}

		updateItem( value, index ) {
			const { attributes, setAttributes } = this.props;
			const { contentItems } = attributes;

			const newItems = contentItems.map( ( item, thisIndex ) => {
				if ( index === thisIndex ) {
					item = { ...item, ...value };
				}

				return item;
			} );

			setAttributes( { contentItems: newItems } );
		}

		addNewItem = () => {
			const { attributes, setAttributes } = this.props;
			const { contentItems } = attributes;

			setAttributes( {
				contentItems: [
					...contentItems,
					{ icon: '', body: __( '' ) },
				],
			} );
		}

		addNewItem2 = () => {
			const { attributes, setAttributes } = this.props;
			const { contentItems } = attributes;

			setAttributes( {
				contentItems: [
					{ icon: '', body: __( '' ) },
					...contentItems,
				],
			} );
		}

		valueUpdater = ( field ) => {
			const { setAttributes } = this.props;
			return ( value ) => setAttributes( { [ field ]: value } );
		};

		render() {
			const { attributes, setAttributes, isSelected } = this.props;
			const {
				contentItems,
				title,
			} = attributes;

			return (
				<div className="row">
					<div className="col-lg-10 ">
						<RichText
							tagName="h2"
							className="section-title-min section-title-min--style-02 mb-30"
							value={ title }
							onChange={ this.valueUpdater( 'title' ) }
							placeholder={ __( 'Nhập tiêu đề...' ) }
						/>
						<div className="slide-download-pdf">
							<div className="swiper__module swiper-container" data-options='{"slidesPerView":3,"spaceBetween":30,"breakpoints":{"550":{"slidesPerView":1,"spaceBetween":10},"991":{"slidesPerView":2,"spaceBetween":30},"1200":{"slidesPerView":3}}}'>
								<div className="swiper-wrapper">
									<Tooltip text={ __( 'Thêm' ) }>
										<span onClick={ this.addNewItem2 }>
											<Dashicon icon="plus-alt" />
										</span>
									</Tooltip>
									{ contentItems.map( ( item, index ) => (
										<div key={ index } className="download-pdf">
											<RichText
												tagName="h3"
												className="download-pdf__title"
												value={ item.title }
												onChange={ ( value ) => this.updateItem( { title: value }, index ) }
												placeholder={ __( 'Nhập tiêu đề...' ) }
											/>
											<div className="download-pdf__img">
												<MediaUpload
													onSelect={ ( media ) => this.updateItem( { image: media.url, imageId: media.id }, index ) }
													allowedTypes="image"
													value={ item.imageId }
													render={ ( { open } ) => (
														<Button className={ item.imageId ? 'image-button' : 'button button-large' } onClick={ open }>
															{ ! item.imageId ? __( 'Chọn ảnh', 'gutenberg-examples' ) : <img src={ item.image } alt="" /> }
														</Button>
													) }
												/>
											</div>
											<a className="btn btn-secondary" href={ item.url }><i className="fa fa-download"></i> Tải về file PDF</a>
											{ isSelected && (
												<form
													className="block-library-button__inline-link"
													onSubmit={ ( event ) => event.preventDefault() }>
													<Dashicon icon="admin-links" />
													<URLInput
														value={ item.url }
														onChange={ ( value ) => this.updateItem( { url: value }, index ) }
													/>
													<IconButton icon="editor-break" label={ __( 'Apply' ) } type="submit" />
												</form>
											) }
											<Tooltip text={ __( 'Xoá' ) }>
												<span
													onClick={ () => setAttributes( {
														contentItems: contentItems.filter( (vl, idx) => idx !== index )
													} ) }
												>
													<Dashicon icon="no" />
												</span>
											</Tooltip>
										</div>
									) ) }
									<Tooltip text={ __( 'Thêm' ) }>
										<span onClick={ this.addNewItem }>
											<Dashicon icon="plus-alt" />
										</span>
									</Tooltip>

								</div>
								<div className="swiper-button-custom">
									<div className="swiper-button-prev-custom"><img src="/img/svg/arrow-left.svg" alt="" /></div>
									<div className="swiper-button-next-custom"><img src="/img/svg/arrow-right.svg" alt="" /></div>
								</div>
							</div>

						</div>
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

	const tabBlockAttrs = {
		contentItems: {
			type: 'array',
			default: [
				{
					imageId: '',
					image: '/img/image/book-pdf.jpg',
					url: '#',
					title: __( 'Báo cáo thường niên 2018' ),
				},
				{
					imageId: '',
					image: '/img/image/book-pdf2.jpg',
					url: '#',
					title: __( 'Báo cáo thường niên 2017' ),
				},
				{
					imageId: '',
					image: '/img/image/book-pdf3.jpg',
					url: '#',
					title: __( 'Báo cáo thường niên 2016' ),
				},
				{
					imageId: '',
					image: '/img/image/book-pdf4.jpg',
					url: '#',
					title: __( 'Báo cáo thường niên 2015' ),
				},
				{
					imageId: '',
					image: '/img/image/book-pdf5.jpg',
					url: '#',
					title: __( 'Báo cáo thường niên 2014' ),
				},
			],
		},

		title: {
			type: 'string',
			default: 'Báo cáo tài chính các năm',
		},
	};

	registerBlockType( 'cgb/block-gioithieu-baocaotaichinh2', {
		title: __( 'Giới thiệu - Báo cáo tài chính 2' ),
		icon: {
			src: tabsBlockIcon,
		},
		category: 'common',
		keywords: [ __( 'gioi thieu' ), __( 'Giới thiệu - Báo cáo tài chính 2' ), __( 'bv gioi thieu' ) ],
		attributes: tabBlockAttrs,
		edit: GTBaoCaoTC2,
		save: function( { attributes } ) {
			const {
				contentItems,
				title,
			} = attributes;

			return (
				<div className="row">
					<div className="col-lg-10 ">
						<RichText.Content
							tagName="h2"
							className="section-title-min section-title-min--style-02 mb-30"
							value={ title }
						/>
						<div className="slide-download-pdf">
							<div className="swiper__module swiper-container" data-options='{"slidesPerView":3,"spaceBetween":30,"breakpoints":{"550":{"slidesPerView":1,"spaceBetween":10},"991":{"slidesPerView":2,"spaceBetween":30},"1200":{"slidesPerView":3}}}'>
								<div className="swiper-wrapper">

									{ contentItems.map( ( item, index ) => (
										<div key={ index } className="download-pdf">
											<RichText.Content
												tagName="h3"
												className="download-pdf__title"
												value={ item.title }
											/>
											<div className="download-pdf__img">
                        {item.image && (
                          <img src={item.image} alt={item.title} />
                        )}
											</div>
											<a className="btn btn-secondary" href={ item.url }><i className="fa fa-download"></i> Tải về file PDF</a>
											) }
										</div>
									) ) }

								</div>
								<div className="swiper-button-custom">
									<div className="swiper-button-prev-custom"><img src="/img/svg/arrow-left.svg" alt="Prev" /></div>
									<div className="swiper-button-next-custom"><img src="/img/svg/arrow-right.svg" alt="Next" /></div>
								</div>
							</div>

						</div>
					</div>
				</div>
			);
		},
	} );
}( wp.i18n, wp.blocks, wp.element, wp.editor, wp.components ) );
