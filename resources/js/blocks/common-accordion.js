( function( wpI18n, wpBlocks, wpElement, wpEditor, wpComponents ) {
	const { __ } = wpI18n;
	const { Component } = wpElement;
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
			} = attributes;

			return (
				<div className="ac-accordion" data-options="{}">
					<Tooltip text={ __( 'Thêm' ) }>
						<span onClick={ this.addNewItem2 }>
							<Dashicon icon="plus-alt" />
						</span>
					</Tooltip>
					{ contentItems.map( ( item, index ) => (
						<div key={ index } className="ac-accordion__panel">
							<div className="ac-accordion__header">
								<h6 className="ac-accordion__title">
									<RichText
										tagName="a"
										href="#"
										value={ item.title }
										onChange={ ( value ) => this.updateItem( { title: value }, index ) }
										placeholder={ __( 'Nhập tiêu đề...' ) }
									/>
								</h6>
							</div>
							<div className="ac-accordion__body">
								<RichText
									tagName="p"
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
					title: 'Triết lý kinh doanh',
					body: __( 'Khách hàng là trung tâm: Dự đoán trước những mối quan tâm của khách hàng, đáp ứng hơn cả những gì khách hàng mong đợi.Con người là tài sản vô giá: Kiến thức, kỹ năng, và thái độ được hòa quyện thành thói quen tốt, trong một môi trường cạnh tranh trên tinh thần hợp tác, đầy thách thức nhưng nhiều cơ hội phát triển, mọi đóng góp đều được thừa nhận và tôn vinh. Phát triển bền vững: Hành động luôn dựa trên sự thấu hiểu, tôn trọng, và bảo vệ lợi ích của các bên tham gia lợi ích trước mắt phải đi đôi với lợi ích lâu dài. Trách nhiệm với cộng đồng: Sự phát triển của Bảo Việt Nhân Thọ luôn gắn liền với các hoạt từ thiện, nâng cao chất lượng cuộc sống đóng góp tích cực vào sự bình an, sự thịnh vượng của cộng đồng.' ),
				},
				{
					title: 'mục tiêu đến năm 2020',
					body: __( 'Là doanh nghiệp bảo hiểm nhân thọ hàng đầu tại Việt Nam, chuyên cung cấp những sản phẩm bảo hiểm nhân thọ tin cậy, thân thiện và chuyên nghiệp.' ),
				},
				{
					title: 'Công nghệ quản lý',
					body: __( 'Hệ thống các phần mềm có mối liên kết chặt chẽ với nhau, vận hàng trên nền mạng chất lượng tốt, đáp ứng những nhu cầu khác nhau của các bộ phận, khách hàng và đại lý.' ),
				},
				{
					title: 'Mô hình tổ chức và nhân sự',
					body: __( 'Hệ thống các phần mềm có mối liên kết chặt chẽ với nhau, vận hàng trên nền mạng chất lượng tốt, đáp ứng những nhu cầu khác nhau của các bộ phận, khách hàng và đại lý.' ),
				},
				{
					title: 'Mô hình kinh doanh',
					body: __( 'Hệ thống các phần mềm có mối liên kết chặt chẽ với nhau, vận hàng trên nền mạng chất lượng tốt, đáp ứng những nhu cầu khác nhau của các bộ phận, khách hàng và đại lý.' ),
				},
			],
		},
	};

	registerBlockType( 'cgb/block-chung-accordion', {
		title: __( 'Chung - Accordion' ),
		// description: __( 'Create your own tabs never easy like this.' ),
		icon: {
			src: tabsBlockIcon,
		},
		category: 'widgets',
		keywords: [ __( 'Common' ), __( 'Chung - Accordion' ), __( 'Gioi thieu' ) ],
		attributes: tabBlockAttrs,
		edit: SPBoTro,
		save: function( { attributes } ) {
			const {
				contentItems,
			} = attributes;

			return (
				<div className="ac-accordion" data-options="{}">
					{ contentItems.map( ( item, index ) => (
						<div key={ index } className="ac-accordion__panel">
							<div className="ac-accordion__header">
								<h6 className="ac-accordion__title">
									<RichText.Content
										tagName="a"
										href="#"
										value={ item.title }
									/>
								</h6>
							</div>
							<div className="ac-accordion__body">
								<RichText.Content
									tagName="p"
									value={ item.body }
								/>
							</div>
						</div>
					) ) }
				</div>
			);
		},
	} );
}( wp.i18n, wp.blocks, wp.element, wp.editor, wp.components ) );
