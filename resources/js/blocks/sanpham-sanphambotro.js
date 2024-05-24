( function( wpI18n, wpBlocks, wpElement, wpEditor, wpComponents ) {
	const { __ } = wpI18n;
	const { Component, Fragment } = wpElement;
	const { registerBlockType } = wpBlocks;
	const { RichText } = wpEditor;
	const { Dashicon, Tooltip } = wpComponents;

	class SPBoTro extends Component {
		componentDidUpdate( ) {
			// const { contentItems: prevItems } = prevProps.attributes;
			const { contentItems } = this.props.attributes;

			if ( contentItems.length === 0 ) {
				this.props.setAttributes( {
					contentItems: [
						{
							title: 'Tiêu đề',
							body: 'Cần có ít nhất 1 item.',
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
			const { attributes, setAttributes } = this.props;
			const {
				contentItems,
				title,
			} = attributes;

			return (
				<Fragment>
					<RichText
						tagName="h2"
						className="section-title-min"
						value={ title }
						onChange={ this.valueUpdater( 'title' ) }
						placeholder={ __( 'Nhập tiêu đề...' ) }
					/>
					<div className="row row-eq-height">
						<Tooltip text={ __( 'Thêm' ) }>
							<span onClick={ this.addNewItem2 }>
								<Dashicon icon="plus-alt" />
							</span>
						</Tooltip>
						{ contentItems.map( ( item, index ) => (
							<div key={ index } className="text-box">
								<div className="text-box__inner">
									<RichText
										tagName="h4"
										className="text-box__title"
										value={ item.title }
										onChange={ ( value ) => this.updateItem( { title: value }, index ) }
										placeholder={ __( 'Nhập tiêu đề...' ) }
									/>
									<RichText
										tagName="p"
										className="text-box__text"
										value={ item.body }
										onChange={ ( value ) => this.updateItem( { body: value }, index ) }
										placeholder={ __( 'Nhập nội dung...' ) }
									/>
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
				</Fragment>

			)
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
					title: 'Bảo hiểm tử vong và thương tật toàn bộ vĩnh viễn do tai nạn',
					body: __( 'Trả STBH trong trường hợp NĐBH bị tử vong và thương tật toàn bộ vĩnh viễn do tai nạn' ),
				},
				{
					title: 'Bảo hiểm Tai nạn toàn diện',
					body: __( 'Trả bảo hiểm cho các rủi ro do tai nạn, chi phí nằm viện và phẫu thuật do tai nạn' ),
				},
				{
					title: 'Quyền lợi miễn đóng phí',
					body: __( 'Miễn đóng phí cho hợp đồng chính nếu NĐBH tử vong hoặc TTTBVV do tai nạn' ),
				},
				{
					title: 'Bảo hiểm thương tật bộ phận vĩnh viễn do tai nạn',
					body: __( 'Trả STBH trong trường hợp NĐBH bị thương tật bộ phận vĩnh viễn do tai nạn' ),
				},
			],
		},

		title: {
			type: 'string',
			default: 'Các sản phẩm bổ trợ',
		},
	};

	registerBlockType( 'cgb/block-sanpham-sanphambotro', {
		title: __( 'Sản phẩm - Sản phẩm bổ trợ' ),
		// description: __( 'Create your own tabs never easy like this.' ),
		icon: {
			src: tabsBlockIcon,
		},
		category: 'widgets',
		keywords: [ __( 'San pham' ), __( 'Sản phẩm - Sản phẩm bổ trợ' ), __( 'bv san pham' ) ],
		attributes: tabBlockAttrs,
		edit: SPBoTro,
		save: function( { attributes } ) {
			const {
				contentItems,
				title,
			} = attributes;

			return (
				<Fragment>
					<h2 className="section-title-min">{ title }</h2>
					<div className="row row-eq-height">
						{ contentItems.map( ( item, index ) => (
							<div key={ index } className="col-xsx-12 col-xs-6 col-md-4 col-lg-3">
								<div className="text-box">
									<div className="text-box__inner">
										<RichText.Content
											tagName="h4"
											className="text-box__title"
											value={ item.title }
										/>
										<RichText.Content
											tagName="p"
											className="text-box__text"
											value={ item.body }
										/>
									</div>
								</div>
							</div>
						) ) }
					</div>
				</Fragment>
			);
		},
	} );
}( wp.i18n, wp.blocks, wp.element, wp.editor, wp.components ) );
