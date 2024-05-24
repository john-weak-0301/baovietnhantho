( function( wpI18n, wpBlocks, wpElement, wpEditor, wpComponents ) {
	const { __ } = wpI18n;
	const { Component, Fragment } = wpElement;
	const { registerBlockType } = wpBlocks;
	const { URLInput, RichText } = wpEditor;
	const { Dashicon, Tooltip, IconButton } = wpComponents;

	class SPBoTro extends Component {
		componentDidUpdate( ) {
			// const { contentItems: prevItems } = prevProps.attributes;
			const { contentItems } = this.props.attributes;

			if ( contentItems.length === 0 ) {
				this.props.setAttributes( {
					contentItems: [
						{
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
			const { attributes, setAttributes, isSelected } = this.props;
			const {
				contentItems,
				title,
				url,
				btnText,
			} = attributes;

			return (
				<div className="pt-30" id="sp-quyenloi">
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
							<div key={ index } className="col-md-5">
								<div className="checklist">
                  <span className="checklist__icon"><img src="/img/icon/icon-check.png" alt="check" /></span>
									<RichText
										tagName="p"
										className="checklist__text"
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
					<div className="pt-30">
						<RichText
							tagName="a"
							className="btn btn-secondary"
							href={ url }
							value={ btnText }
							onChange={ this.valueUpdater( 'btnText' ) }
						/>
						{ isSelected && (
							<form
								className="block-library-button__inline-link"
								onSubmit={ ( event ) => event.preventDefault() }>
								<Dashicon icon="admin-links" />
								<URLInput
									value={ url }
									onChange={ this.valueUpdater( 'url' ) }
								/>
								<IconButton icon="editor-break" label={ __( 'Apply' ) } type="submit" />
							</form>
						) }
					</div>
				</div>

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
					body: __( 'Linh hoạt xây dựng kế hoạch tài chính cho tương lai của con trẻ phù hợp với các nhu cầu thay đổi khác nhau trong suốt quá trình tham gia' ),
				},
				{
					body: __( 'Quyền lợi bảo hiểm đem lại sự bảo vệ tài chính một cách hoàn hảo trước những rủi ro bất ngờ' ),
				},
				{
					body: __( 'Quyền lợi bảo vệ toàn diện nhất cho cha mẹ và con cái trong cùng Hợp đồng bảo hiểm' ),
				},
				{
					body: __( 'Quyền lợi Học bổng Bảo Việt vinh danh' ),
				},
				{
					body: __( 'Quyền lợi Thưởng gia tăng Giá trị tài khoản' ),
				},
				{
					body: __( 'Đầu tư an toàn và hiệu quả' ),
				},
			],
		},

		title: {
			type: 'string',
			default: 'Bạn được quyền lợi gì',
		},
		url: {
			type: 'string',
			default: '#',
		},
		btnText: {
			type: 'string',
			default: 'Tải về tài liệu',
		},
	};

	registerBlockType( 'cgb/block-sanpham-quyenloi', {
		title: __( 'Sản phẩm - Quyền lợi' ),
		// description: __( 'Create your own tabs never easy like this.' ),
		icon: {
			src: tabsBlockIcon,
		},
		category: 'widgets',
		keywords: [ __( 'San pham' ), __( 'Sản phẩm - Quyền lợi' ), __( 'bv san pham' ) ],
		attributes: tabBlockAttrs,
		edit: SPBoTro,
		save: function( { attributes } ) {
			const {
				contentItems,
				title,
				url,
				btnText,
			} = attributes;

			return (
				<div className="pt-30" id="sp-quyenloi">
					<RichText.Content
						tagName="h2"
						className="section-title-min"
						value={ title }
					/>

					<div className="row row-eq-height">
						{ contentItems.map( ( item, index ) => (
							<div key={ index } className="col-md-5">
								<div className="checklist">
                  <span className="checklist__icon"><img src="/img/icon/icon-check.png" alt="check" /></span>
									<RichText.Content
										tagName="p"
										className="checklist__text"
										value={ item.body }
									/>
								</div>
							</div>
						) ) }
					</div>
					<div className="pt-30">
						<RichText.Content
							tagName="a"
              className="btn btn-secondary"
              target="_blank"
							href={ url }
							value={ btnText }
						/>
					</div>
				</div>
			);
		},
	} );
}( wp.i18n, wp.blocks, wp.element, wp.editor, wp.components ) );
