(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const {__} = wpI18n;
  const {Component, Fragment} = wpElement;
  const {registerBlockType} = wpBlocks;
  const {RichText} = wpEditor;
  const {Dashicon, Tooltip} = wpComponents;

  class CMTable1 extends Component {
    componentDidUpdate() {
      // const { contentItems: prevItems } = prevProps.attributes;
      const {contentItems} = this.props.attributes;

      if (contentItems.length === 0) {
        this.props.setAttributes({
          contentItems: [
            {
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
          {number: '', body: __('')},
        ],
      });
		}

    addNewItem2 = () => {
      const {attributes, setAttributes} = this.props;
      const {contentItems} = attributes;

      setAttributes({
        contentItems: [
          {number: '', body: __('')},
          ...contentItems,
        ],
      });
    }

    valueUpdater = (field) => {
      const {setAttributes} = this.props;
      return (value) => setAttributes({[field]: value});
    }

    render() {
      const {attributes, setAttributes} = this.props;
      const {
        contentItems,
      } = attributes;

      return (
        <Fragment>
          <table className="table-tomtat">
            <thead>
              <th></th>
              <th></th>
            </thead>
            <tbody>
							<tr>
                <td>
                  <Tooltip text={__('Thêm')}>
										<span onClick={this.addNewItem2}>
											<Dashicon icon="plus-alt"/>
										</span>
                  </Tooltip>
                </td>
                <td></td>
              </tr>
              {contentItems.map((item, index) => (
                <tr key={index}>
                  <td>
                    <RichText
                      value={item.title}
                      onChange={(value) => this.updateItem({title: value}, index)}
                      placeholder={__('50')}
                    />
                    <Tooltip text={__('Xoá')}>
											<span
                        onClick={() => setAttributes({
                          contentItems: contentItems.filter((vl, idx) => idx !== index),
                        })}
                      >
												<Dashicon icon="no"/>
											</span>
                    </Tooltip>
                  </td>
                  <td>
                    <RichText
                      tagName="p"
                      value={item.body}
                      onChange={(value) => this.updateItem({body: value}, index)}
                      placeholder={__('Nhập nội dung...')}
                    />
                  </td>
                </tr>
              ))}

              <tr>
                <td>
                  <Tooltip text={__('Thêm')}>
										<span onClick={this.addNewItem}>
											<Dashicon icon="plus-alt"/>
										</span>
                  </Tooltip>
                </td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </Fragment>
      );
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
          title: 'Tên giao dịch',
          body: __('Tổng Công ty Bảo Việt Nhân thọ (Bảo Việt Nhân Thọ) Tiếng Anh: BAOVIET LIFE'),
        },
        {
          title: 'Trụ sở chính',
          body: __('Tầng 37, Keangnam Ha Noi Landmark Tower, Đường Phạm Hùng, Quận Nam Từ Liêm, Thành phố Hà Nội.'),
        },
        {
          title: 'Đơn vị trực thuộc',
          body: __('76 công ty hạch toán phụ thuộc trên toàn quốc'),
        },
        {
          title: 'Vốn điều lệ',
          body: __('2.500 tỷ đồng'),
        },
        {
          title: 'Điện thoại',
          body: __('(+84 24) 6251 7777- (+84 24) 3577 0946'),
        },
        {
          title: 'Fax',
          body: __('	(+84 24) 3577 0958'),
        },
      ],
    },
  };

  registerBlockType('cgb/block-common-table1', {
    title: __('Chung - (Table) Bảng 1'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'common',
    keywords: [__('table'), __('Chung - Bảng 1'), __('bv chung')],
    attributes: tabBlockAttrs,
    edit: CMTable1,
    save: function({attributes}) {
      const {
        contentItems,
      } = attributes;

      return (
        <Fragment>
          <table className="table-tomtat">
            <thead>
              <th></th>
              <th></th>
            </thead>
            <tbody>
              {contentItems.map((item, index) => (
                <tr key={index}>
                  <td>
                    <RichText.Content
                      value={item.title}
                    />
                  </td>
                  <td>
                    <RichText.Content
                      tagName="p"
                      value={item.body}
                    />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </Fragment>
      );
    },
  });
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components));
