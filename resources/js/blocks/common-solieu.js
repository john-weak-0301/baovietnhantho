(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const {__} = wpI18n;
  const {Component, Fragment} = wpElement;
  const {registerBlockType} = wpBlocks;
  const {RichText} = wpEditor;
  const {Dashicon, Tooltip} = wpComponents;

  class SPBoTro extends Component {
    componentDidMount() {
      // if (!this.props.attributes.blockID) {
      // 	this.props.setAttributes( { blockID: this.props.clientId } );
      // }
    }

    componentDidUpdate() {
      // const { contentItems: prevItems } = prevProps.attributes;
      const {contentItems} = this.props.attributes;

      if (contentItems.length === 0) {
        this.props.setAttributes({
          contentItems: [
            {
              title: 'Tiêu đề',
              body: 'Cần có ít nhất 1 item.',
            },
          ],
        });
      }
    }

    updateItem(value, index) {
      const {attributes, setAttributes} = this.props;
      const {contentItems} = attributes;

      const newItems = contentItems.map((item, thisIndex) => {
        if (index === thisIndex) {
          item = {...item, ...value};
        }

        return item;
      });

      setAttributes({contentItems: newItems});
    }

    addNewItem = () => {
      const {attributes, setAttributes} = this.props;
      const {contentItems} = attributes;

      setAttributes({
        contentItems: [
          ...contentItems,
          {icon: '', body: __('')},
        ],
      });
		}

		addNewItem2 = () => {
      const {attributes, setAttributes} = this.props;
      const {contentItems} = attributes;

      setAttributes({
        contentItems: [
          {icon: '', body: __('')},
          ...contentItems,
        ],
      });
    }

    valueUpdater = (field) => {
      const {setAttributes} = this.props;
      return (value) => setAttributes({[field]: value});
    };

    render() {
      const {attributes, setAttributes} = this.props;
      const {
        contentItems,
        title,
      } = attributes;

      return (
        <Fragment>
          <div className="row">
            <div className="col-xs-8 col-sm-6 col-md-6 col-lg-6 ">
              <RichText
                tagName="h2"
                className="section-title-min"
                value={title}
                onChange={this.valueUpdater('title')}
                placeholder={__('Nhập tiêu đề...')}
              />
            </div>
          </div>

          <div className="row row-eq-height">
						<Tooltip text={__('Thêm')}>
							<span onClick={this.addNewItem2}>
								<Dashicon icon="plus-alt"/>
							</span>
            </Tooltip>
            {contentItems.map((item, index) => (
              <div key={index} className="col-xs-6 col-sm-6 col-md-6 col-lg-3 col-xsx-12">
                <div className="featured-number featured-number__style-03">
                  <RichText
                    tagName="span"
                    className="featured-number__number"
                    value={item.number}
                    onChange={(value) => this.updateItem({number: value}, index)}
                    placeholder={__('Nhập số liệu...')}
                  />
                  <RichText
                    tagName="p"
                    className="featured-number__text"
                    value={item.text}
                    onChange={(value) => this.updateItem({text: value}, index)}
                    placeholder={__('Nhập nội dung...')}
                  />
                </div>
                <Tooltip text={__('Xoá')}>
									<span
                    onClick={() => setAttributes({
                      contentItems: contentItems.filter((vl, idx) => idx !== index),
                    })}
                  >
										<Dashicon icon="no"/>
									</span>
                </Tooltip>
              </div>
            ))}

            <Tooltip text={__('Thêm')}>
							<span onClick={this.addNewItem}>
								<Dashicon icon="plus-alt"/>
							</span>
            </Tooltip>
          </div>
        </Fragment>
      )
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
    contentItems: {
      type: 'array',
      default: [
        {
          number: '50%',
          text: __('Quyền lợi bảo hiểm tử vong được ứng trước tối đa lên đến 500 triệu đồng'),
        },
        {
          number: '19-22',
          text: __('Có thể thanh toán nhiều lần khi con đạt 19 -22 tuổi'),
        },
        {
          number: '50 triệu',
          text: __('Giá trị học bổng tối đa lên đến 50 triệu đồng'),
        },
      ],
    },

    title: {
      type: 'string',
      default: 'Vì sao bạn nên quan tâm sản phẩm này',
    },
  };

  registerBlockType('cgb/block-common-solieu', {
    title: __('Chung - Số liệu'),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'widgets',
    keywords: [__('Common'), __('Chung - Số liệu'), __('bv chung')],
    attributes: tabBlockAttrs,
    edit: SPBoTro,
    save: function({attributes}) {
      const {
        contentItems,
        title,
      } = attributes;

      return (
        <Fragment>
          <div className="row">
            <div className="col-xs-8 col-sm-6 col-md-6 col-lg-6 ">
              <RichText.Content
                tagName="h2"
                className="section-title-min"
                value={title}
              />
            </div>
          </div>

          <div className="row row-eq-height">
            {contentItems.map((item, index) => (
              <div key={index} className="col-xs-6 col-sm-6 col-md-6 col-lg-3 col-xsx-12">
                <div className="featured-number featured-number__style-03">
                  <RichText.Content
                    tagName="span"
                    className="featured-number__number"
                    value={item.number}
                  />
                  <RichText.Content
                    tagName="p"
                    className="featured-number__text"
                    value={item.text}
                  />
                </div>
              </div>
            ))}
          </div>
        </Fragment>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
