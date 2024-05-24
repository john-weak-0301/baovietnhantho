(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n
  const { Component, Fragment } = wpElement
  const { registerBlockType } = wpBlocks
  const { URLInput, RichText } = wpEditor
  const { Button, Dashicon } = wpComponents

  const attributes = {
    title: {
      type: 'string',
      default: 'Giới thiệu',
    },
    targetUrl: {
      type: 'string',
      default: '',
    },
    contentItems: {
      type: 'array',
      default: [
        {
          url: '',
          title: '',
        },
      ],
    },
  }

  class EditComponent extends Component {
    valueUpdater = (field) => {
      const { setAttributes } = this.props
      return (value) => setAttributes({ [field]: value })
    }

    addNewItem = () => {
      const { attributes, setAttributes } = this.props
      const { contentItems } = attributes

      setAttributes({
        contentItems: [
          ...contentItems,
          { url: '', title: '' },
        ],
      })
    }

    removeItem = (index) => {
      const { setAttributes } = this.props

      setAttributes({
        contentItems: contentItems.filter((_, i) => i !== index),
      })
    }

    updateItem(value, index) {
      const { attributes, setAttributes } = this.props
      const { contentItems } = attributes

      const newItems = contentItems.map((item, thisIndex) => {
        if (index === thisIndex) {
          item = { ...item, ...value }
        }

        return item
      })

      setAttributes({ contentItems: newItems })
    }

    render() {
      const { attributes, isSelected } = this.props

      const {
        title,
        targetUrl,
        contentItems,
      } = attributes

      return (
        <Fragment>
          <div className="list-menu">
            <div className="list-menu__title">
              <RichText
                tagName="a"
                value={title}
                onChange={this.valueUpdater('title')}
                placeholder={'Nhập tiêu đề...'}
              />

              {isSelected && (
                <URLInput
                  value={targetUrl}
                  onChange={this.valueUpdater('targetUrl')}
                />
              )}
            </div>

            <ul className="list-menu__list">
              {contentItems.map((item, index) => (
                <li key={index}>
                  <RichText
                    tagName="a"
                    value={item.title}
                    href={item.url}
                    onChange={
                      (value) => this.updateItem({ title: value }, index)
                    }
                    placeholder={'Nhập tiêu đề'}
                  />

                  {isSelected && (
                    <div style={{ display: 'inline-flex' }}>
                      <URLInput
                        value={item.url}
                        onChange={
                          (value) => this.updateItem({ url: value }, index)
                        }
                      />

                      <Button isLink onClick={() => this.removeItem(index)}>
                        <Dashicon icon="no" />
                      </Button>
                    </div>
                  )}
                </li>
              ))}
            </ul>

            {isSelected && (
              <Button isLink onClick={this.addNewItem}>
                <Dashicon icon="plus-alt" />
              </Button>
            )}
          </div>
        </Fragment>
      )
    }
  }

  const blockIcon = (
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="bi bi-menu-button-wide-fill" viewBox="0 0 16 16">
      <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v2A1.5 1.5 0 0 0 1.5 5h13A1.5 1.5 0 0 0 16 3.5v-2A1.5 1.5 0 0 0 14.5 0h-13zm1 2h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1zm9.927.427A.25.25 0 0 1 12.604 2h.792a.25.25 0 0 1 .177.427l-.396.396a.25.25 0 0 1-.354 0l-.396-.396zM0 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8zm1 3v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2H1zm14-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2h14zM2 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
    </svg>
  )

  registerBlockType('cgb/block-menu-links', {
    title: __('Menu - Links'),
    icon: { src: blockIcon },
    category: 'widgets',
    attributes,
    edit: EditComponent,
    save: function({ attributes }) {
      const {
        title,
        targetUrl,
        contentItems,
      } = attributes

      return (
        <div className="list-menu">
          <div className="list-menu__title">
            <RichText.Content
              tagName={targetUrl ? 'a' : 'span'}
              value={title}
              href={targetUrl}
            />
          </div>

          <ul className="list-menu__list">
            {contentItems.map((item, index) => (
              <li key={index}>
                <a href={item.url}>{item.title}</a>
              </li>
            ))}
          </ul>
        </div>
      )
    },
  })
}(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components))
