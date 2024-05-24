( function( wpI18n, wpBlocks, wpElement, wpEditor, wpComponents ) {
	const { __ } = wpI18n;
	const { Component, Fragment } = wpElement;
	const { registerBlockType } = wpBlocks;
	const { RichText } = wpEditor;
	const { Dashicon, Tooltip } = wpComponents;

	class GTSoLieu extends Component {
		componentDidUpdate( ) {
			// const { contentItems: prevItems } = prevProps.attributes;
			const { contentItems } = this.props.attributes;

			if ( contentItems.length === 0 ) {
				this.props.setAttributes( {
					contentItems: [
						{
							year: '2019',
							data: [
								{
									month: '',
									body: '',
								}
							]
						},
					],
				} );
			}
		}

		updateItem = ( value, index ) => {
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

		updateItemData = ( value, index, dataIndex ) => {
			const { attributes, setAttributes } = this.props;
			const { contentItems } = attributes;

			const index1 = contentItems.findIndex( ( vl, idx ) => idx === index );
			const monthData = contentItems[ index1 ].data;
			const index2 = monthData.findIndex( ( vl, idx ) => idx === dataIndex );

			const newData = [
				...monthData.slice( 0, index2 ),
				Object.assign( {}, monthData[ index2 ], value ),
				...monthData.slice( index2 + 1 ),
			];

			const newItems = [
				...contentItems.slice( 0, index1 ),
				Object.assign( {}, contentItems[ index1 ], { data: newData } ),
				...contentItems.slice( index1 + 1 ),
			];

			setAttributes( { contentItems: newItems } );
		}

		addNewItem = () => {
			const { attributes, setAttributes } = this.props;
			const { contentItems } = attributes;

			setAttributes( {
				contentItems: [
					...contentItems,
					{ year: '', data: [ { month: '', body: '' } ] },
				],
			} );
		}

		addNewItem2 = () => {
			const { attributes, setAttributes } = this.props;
			const { contentItems } = attributes;

			setAttributes( {
				contentItems: [
					{ year: '', data: [ { month: '', body: '' } ] },
					...contentItems,
				],
			} );
		}

		addNewItemData = ( index ) => {
			const { attributes, setAttributes } = this.props;
			const { contentItems } = attributes;

			const index1 = contentItems.findIndex( ( vl, idx ) => idx === index );
			const monthData = contentItems[ index1 ].data;

			const newData = [
				...monthData,
				{
					month: 'Tháng 1',
					body: '',
				},
			];

			const newItems = [
				...contentItems.slice( 0, index1 ),
				Object.assign( {}, contentItems[ index1 ], { data: newData } ),
				...contentItems.slice( index1 + 1 ),
			];
			setAttributes( { contentItems: newItems } );
		}

		removeItemData = ( index, dataIndex ) => {
			const { attributes, setAttributes } = this.props;
			const { contentItems } = attributes;

			const index1 = contentItems.findIndex( ( vl, idx ) => idx === index );
			const monthData = contentItems[ index1 ].data;
			const index2 = monthData.findIndex( ( vl, idx ) => idx === dataIndex );

			const newData = [
				...monthData.slice( 0, index2 ),
				...monthData.slice( index2 + 1 ),
			];

			const newItems = [
				...contentItems.slice( 0, index1 ),
				Object.assign( {}, contentItems[ index1 ], { data: newData } ),
				...contentItems.slice( index1 + 1 ),
			];

			setAttributes( { contentItems: newItems } );
		}

		valueUpdater = ( field ) => {
			const { setAttributes } = this.props;
			return ( value ) => setAttributes( { [ field ]: value } );
		}

		render() {
			const { attributes, setAttributes } = this.props;
			const {
				contentItems,
				title,
			} = attributes;

			return (
				<Fragment>
					<div className="container">
						<div className="row">
							<div className="col-lg-4 ">
								<RichText
									tagName="h2"
									className="section-title-min"
									value={ title }
									onChange={ this.valueUpdater( 'title' ) }
									placeholder={ __( 'Nhập tiêu đề' ) }
								/>
							</div>
						</div>
					</div>
					<div className="slide-year-process-wrapper">
						<div className="container container-slide-year-process">
							<div className="slide-year-process swiper-container">
								<div className="swiper-wrapper">
									<Tooltip text={ __( 'Thêm' ) }>
										<span onClick={ this.addNewItem2 }>
											<Dashicon icon="plus-alt" />
										</span>
									</Tooltip>
									{ contentItems.map( ( item, index ) => (
										<div key={ index } className="swiper-slide">
											<div className="year-process year-process__style-02">
												<div className="year-process__inner">
													<RichText
														tagName="span"
														className="year-process__year"
														value={ item.year }
														onChange={ ( value ) => this.updateItem( { year: value }, index ) }
														placeholder={ __( '2019' ) }
													/>
													{ item.data.map( ( event, index2 ) => (
														<div key={ index2 } className="year-process__item">
															<RichText
																tagName="h4"
																className="year-process__title"
																value={ event.month }
																onChange={ ( value ) => this.updateItemData( { month: value }, index, index2 ) }
																placeholder={ __( 'Tháng 01/2016' ) }
															/>
															<RichText
																tagName="p"
																className="year-process__text"
																value={ event.body }
																onChange={ ( value ) => this.updateItemData( { body: value }, index, index2 ) }
																placeholder={ __( 'Nhập nội dung...' ) }
															/>
															<Tooltip text={ __( 'Xoá tháng' ) }>
																<span onClick={ this.removeItemData.bind( this, index, index2 ) }>
																	<Dashicon icon="no" />
																</span>
															</Tooltip>
														</div>
													) ) }
													<Tooltip text={ __( 'Thêm tháng' ) }>
														<span onClick={ this.addNewItemData.bind( this, index ) }>
															<Dashicon icon="plus-alt" />
														</span>
													</Tooltip>
												</div>
											</div>
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
							</div>
						</div>
						<div className="container">
							<div className="swiper-pagination-scrollbar"></div>
						</div>
					</div>
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
		contentItems: {
			type: 'array',
			default: [
				{
					year: '50',
					data: [
						{
							month: 'Tháng 01/2016',
							body: __( 'Bảo Việt Nhân thọ thành lập thêm 5 công ty thành viên, nâng số lượng công ty thành viên trên toàn quốc lên 65 công ty' ),
						},
						{
							month: 'Tháng 02/2016',
							body: __( '' ),
						},
					],
				},
			],
		},
		title: {
			type: 'string',
			default: 'Lịch sử phát triển',
		},
	};

	registerBlockType( 'cgb/block-gioithieu-lichsu', {
		title: __( 'Giới thiệu - Lịch sử phát triển' ),
		// description: __( 'Create your own tabs never easy like this.' ),
		icon: {
			src: tabsBlockIcon,
		},
		category: 'widgets',
		keywords: [ __( 'gioi thieu' ), __( 'Giới thiệu - Lịch sử phát triển' ), __( 'bv gioi thieu' ) ],
		attributes: tabBlockAttrs,
		edit: GTSoLieu,
		save: function( { attributes } ) {
			const {
				contentItems,
				title,
			} = attributes;

			return (
				<Fragment>
					<div className="container">
						<div className="row">
							<div className="col-lg-4 ">
								<RichText.Content
									tagName="h2"
									className="section-title-min"
									value={ title }
								/>
							</div>
						</div>
					</div>
					<div className="slide-year-process-wrapper">
						<div className="container container-slide-year-process">
							<div className="slide-year-process swiper-container">
								<div className="swiper-wrapper">
									{ contentItems.map( ( item, index ) => (
										<div key={ index } className="swiper-slide">
											<div className="year-process year-process__style-02">
												<div className="year-process__inner">
													<RichText.Content
														tagName="span"
														className="year-process__year"
														value={ item.year }
													/>
													{ item.data.map( ( event, index2 ) => (
														<div key={ index2 } className="year-process__item">
															<RichText.Content
																tagName="h4"
																className="year-process__title"
																value={ event.month }
															/>
															<RichText.Content
																tagName="p"
																className="year-process__text"
																value={ event.body }
															/>
														</div>
													) ) }
												</div>
											</div>
										</div>
									) ) }
								</div>
							</div>
						</div>
						<div className="container">
							<div className="swiper-pagination-scrollbar"></div>
						</div>
					</div>
				</Fragment>
			);
		},
	} );
}( wp.i18n, wp.blocks, wp.element, wp.editor, wp.components ) );
