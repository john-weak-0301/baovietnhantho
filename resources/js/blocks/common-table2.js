(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const {__} = wpI18n;
  const {Component, Fragment} = wpElement;
  const {registerBlockType} = wpBlocks;
  const {RichText} = wpEditor;
  const {Dashicon, Tooltip} = wpComponents;

  class CMTable2 extends Component {
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
          <table className="table-phamviapdung">
            <thead>
              <th>STT</th>
              <th>Rủi ro</th>
              <th>Quyền lợi bảo hiểm</th>
              <th>Đặc điểm hồ sơ</th>
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
                      value={item.stt}
                      onChange={(value) => this.updateItem({stt: value}, index)}
                      placeholder={__('1')}
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
                      value={item.ruiro}
                      onChange={(value) => this.updateItem({ruiro: value}, index)}
                      placeholder={__('Nhập nội dung...')}
                    />
                  </td>
                  <td>
                    <RichText
                      value={item.quyenloi}
                      onChange={(value) => this.updateItem({quyenloi: value}, index)}
                      placeholder={__('Nhập nội dung...')}
                    />
                  </td>
                  <td>
                    <RichText
                      tagName="p"
                      value={item.dacdiem}
                      onChange={(value) => this.updateItem({dacdiem: value}, index)}
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
          stt: '',
          ruiro: __(''),
          quyenloi: __(''),
          dacdiem: __(''),
        },
      ],
    },
  };

  registerBlockType('cgb/block-common-table2', {
    title: __('Chung - (Table) Bảng 2'),
    // description: __( 'Create your own tabs never easy like this.' ),
    icon: {
      src: tabsBlockIcon,
    },
    category: 'common',
    keywords: [__('table'), __('Chung - Bảng 2'), __('bv chung')],
    attributes: tabBlockAttrs,
    edit: CMTable2,
    save: function({attributes}) {
      const {
        contentItems,
      } = attributes;

      return (
        <Fragment>
          <table className="table-phamviapdung">
            <thead>
              <tr>
                <th>STT</th>
                <th>Rủi ro</th>
                <th>Quyền lợi bảo hiểm</th>
                <th>Đặc điểm hồ sơ</th>
              </tr>
            </thead>
            <tbody>
              {contentItems.map((item, index) => (
                <tr key={index}>
                  <td>
                    <RichText.Content
                      value={item.stt}
                    />
                  </td>
                  <td>
                    <RichText.Content
                      value={item.ruiro}
                    />
                  </td>
                  <td>
                    <RichText.Content
                      value={item.quyenloi}
                    />
                  </td>
                  <td>
                    <RichText.Content
                      tagName="p"
                      value={item.dacdiem}
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
